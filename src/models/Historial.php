<?php
class Historial {
    private $conn;
    private $table = "audit_log";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get the audit records with pagination and filters.
    public function listar($search = "", $modulo = "", $accionCrud = "", $limit = 50, $offset = 0) {
        $params = [];

        // Expression for the user name according to the real fields of the users table
        $userNameExpr = $this->getUserNameExpr();
        $effectiveUserIdExpr = $this->getEffectiveUserIdExpr();
        $userRoleExpr = $this->getUserRoleExpr($effectiveUserIdExpr);

        $sql = "
            SELECT
                a.id_audit,
                a.tabla_nombre,
                a.accion,
                a.pk_valor,
                {$effectiveUserIdExpr} AS usuario_id,
                a.old_values,
                a.new_values,
                a.fecha,
                CASE
                    WHEN {$effectiveUserIdExpr} IS NULL THEN 'Sistema'
                    WHEN {$userNameExpr} IS NOT NULL AND {$userNameExpr} != '' THEN {$userNameExpr}
                    ELSE CONCAT('Usuario #', {$effectiveUserIdExpr})
                END AS usuario_nombre,
                {$userRoleExpr} AS usuario_cargo
            FROM {$this->table} a
            LEFT JOIN usuarios u ON u.id_usuario = {$effectiveUserIdExpr}
            LEFT JOIN roles r ON r.id_rol = u.id_rol
            WHERE 1=1
        ";

        // General search
        if (!empty($search)) {
            $sql .= " AND (
                a.tabla_nombre LIKE :search
                OR a.pk_valor LIKE :search
                OR {$userNameExpr} LIKE :search
            )";
            $params[':search'] = "%$search%";
        }

        // Filter by module (table)
        if (!empty($modulo)) {
            $sql .= " AND a.tabla_nombre = :modulo";
            $params[':modulo'] = $modulo;
        }

        // Filter by action CRUD
        if (!empty($accionCrud)) {
            $sql .= " AND a.accion = :accion";
            $params[':accion'] = strtoupper($accionCrud);
        }

        $sql .= " ORDER BY a.fecha DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count the total number of records with the applied filters
    public function contar($search = "", $modulo = "", $accionCrud = "") {
        $params = [];
        $userNameExpr = $this->getUserNameExpr();
        $effectiveUserIdExpr = $this->getEffectiveUserIdExpr();

        $sql = "
            SELECT COUNT(*) AS total
            FROM {$this->table} a
            LEFT JOIN usuarios u ON u.id_usuario = {$effectiveUserIdExpr}
            WHERE 1=1
        ";

        if (!empty($search)) {
            $sql .= " AND (
                a.tabla_nombre LIKE :search
                OR a.pk_valor LIKE :search
                OR {$userNameExpr} LIKE :search
            )";
            $params[':search'] = "%$search%";
        }

        if (!empty($modulo)) {
            $sql .= " AND a.tabla_nombre = :modulo";
            $params[':modulo'] = $modulo;
        }

        if (!empty($accionCrud)) {
            $sql .= " AND a.accion = :accion";
            $params[':accion'] = strtoupper($accionCrud);
        }

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    // Get the user name expression
    private function getUserNameExpr() {
        // Verificar qué columnas existen en 'usuarios'
        $stmt = $this->conn->prepare("SHOW COLUMNS FROM usuarios");
        $stmt->execute();
        $cols = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');

        $expr = "''";

        // Priority: representative_legal for non-company roles,
        // nombre_empresa for companies (id_rol = 2)
        if (in_array('representante_legal', $cols) && in_array('nombre_empresa', $cols) && in_array('id_rol', $cols)) {
            $expr = "CASE WHEN u.id_rol = 2 THEN u.nombre_empresa ELSE u.representante_legal END";
        } elseif (in_array('representante_legal', $cols)) {
            $expr = "u.representante_legal";
        } elseif (in_array('nombre_empresa', $cols)) {
            $expr = "u.nombre_empresa";
        } elseif (in_array('correo', $cols)) {
            $expr = "u.correo";
        }

        return $expr;
    }

    // Get the effective user ID
    private function getEffectiveUserIdExpr() {
        return "COALESCE(
            a.usuario_id,
            NULLIF(CAST(JSON_UNQUOTE(JSON_EXTRACT(a.new_values, '$.id_usuario')) AS UNSIGNED), 0),
            NULLIF(CAST(JSON_UNQUOTE(JSON_EXTRACT(a.old_values, '$.id_usuario')) AS UNSIGNED), 0)
        )";
    }

    // Get the role of the user
    private function getUserRoleExpr($effectiveUserIdExpr) {
        $stmtUsers = $this->conn->prepare("SHOW COLUMNS FROM usuarios");
        $stmtUsers->execute();
        $userCols = array_column($stmtUsers->fetchAll(PDO::FETCH_ASSOC), 'Field');

        $hasUserRoleFk = in_array('id_rol', $userCols);
        $hasRolesTable = false;
        $hasRoleName = false;

        try {
            $stmtRoles = $this->conn->prepare("SHOW COLUMNS FROM roles");
            $stmtRoles->execute();
            $roleCols = array_column($stmtRoles->fetchAll(PDO::FETCH_ASSOC), 'Field');
            $hasRolesTable = true;
            $hasRoleName = in_array('nombre', $roleCols);
        } catch (Throwable $e) {
            $hasRolesTable = false;
            $hasRoleName = false;
        }

        if ($hasUserRoleFk && $hasRolesTable && $hasRoleName) {
            return "COALESCE(
                NULLIF(CONCAT(UCASE(LEFT(LOWER(r.nombre), 1)), SUBSTRING(LOWER(r.nombre), 2)), ''),
                (
                    SELECT CONCAT(UCASE(LEFT(LOWER(rr.nombre), 1)), SUBSTRING(LOWER(rr.nombre), 2))
                    FROM roles rr
                    WHERE rr.id_rol = COALESCE(
                        NULLIF(CAST(JSON_UNQUOTE(JSON_EXTRACT(a.new_values, '$.id_rol')) AS UNSIGNED), 0),
                        NULLIF(CAST(JSON_UNQUOTE(JSON_EXTRACT(a.old_values, '$.id_rol')) AS UNSIGNED), 0)
                    )
                    LIMIT 1
                ),
                CASE
                    WHEN {$effectiveUserIdExpr} IS NULL THEN 'Sistema'
                    ELSE 'No disponible'
                END
            )";
        }

        return "CASE
            WHEN {$effectiveUserIdExpr} IS NULL THEN 'Sistema'
            ELSE 'No disponible'
        END";
    }
}