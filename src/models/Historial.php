<?php
class Historial {
    private $conn;
    private $table = "audit_log";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtiene los registros de auditoría con paginación y filtros.
     *
     * @param string $search Término de búsqueda (tabla_nombre, pk_valor, nombre de usuario)
     * @param string $modulo Nombre exacto de la tabla (opcional)
     * @param string $accionCrud INSERT/UPDATE/DELETE (opcional)
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function listar($search = "", $modulo = "", $accionCrud = "", $limit = 50, $offset = 0) {
        $params = [];

        // Expresión para el nombre del usuario según los campos reales de la tabla usuarios
        $userNameExpr = $this->getUserNameExpr();

        $sql = "
            SELECT
                a.id_audit,
                a.tabla_nombre,
                a.accion,
                a.pk_valor,
                a.usuario_id,
                a.old_values,
                a.new_values,
                a.fecha,
                CASE
                    WHEN a.usuario_id IS NULL THEN 'Sistema'
                    WHEN {$userNameExpr} IS NOT NULL AND {$userNameExpr} != '' THEN {$userNameExpr}
                    ELSE CONCAT('Usuario #', a.usuario_id)
                END AS usuario_nombre,
                '' AS usuario_cargo   -- No hay campo cargo en la tabla usuarios, se deja vacío
            FROM {$this->table} a
            LEFT JOIN usuarios u ON u.id_usuario = a.usuario_id
            WHERE 1=1
        ";

        // Búsqueda general
        if (!empty($search)) {
            $sql .= " AND (
                a.tabla_nombre LIKE :search
                OR a.pk_valor LIKE :search
                OR {$userNameExpr} LIKE :search
            )";
            $params[':search'] = "%$search%";
        }

        // Filtro por módulo (tabla)
        if (!empty($modulo)) {
            $sql .= " AND a.tabla_nombre = :modulo";
            $params[':modulo'] = $modulo;
        }

        // Filtro por acción CRUD
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

    /**
     * Cuenta el total de registros con los filtros aplicados.
     */
    public function contar($search = "", $modulo = "", $accionCrud = "") {
        $params = [];
        $userNameExpr = $this->getUserNameExpr();

        $sql = "
            SELECT COUNT(*) AS total
            FROM {$this->table} a
            LEFT JOIN usuarios u ON u.id_usuario = a.usuario_id
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

    /**
     * Construye una expresión SQL segura para obtener el nombre del usuario
     * según los campos disponibles en la tabla 'usuarios'.
     */
    private function getUserNameExpr() {
        // Verificar qué columnas existen en 'usuarios'
        $stmt = $this->conn->prepare("SHOW COLUMNS FROM usuarios");
        $stmt->execute();
        $cols = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');

        $expr = "''";

        // Prioridad: representante_legal para roles que no son empresa,
        // nombre_empresa para empresas (id_rol = 2)
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
}