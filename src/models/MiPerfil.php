<?php
class MiPerfil {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Obtiene los datos básicos de un usuario por su ID
     */
    public function getUser($userId) {
        $stmt = $this->db->prepare("SELECT id_usuario, id_rol, nombre_empresa, representante_legal, correo, fecha_registro, tipo_documento, numero_documento, razon_social FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza nombre (representante_legal) y correo de un usuario
     */
    public function updateUser($userId, $name, $email) {
        $stmt = $this->db->prepare("UPDATE usuarios SET representante_legal = ?, correo = ? WHERE id_usuario = ?");
        return $stmt->execute([$name, $email, $userId]);
    }

    /**
     * Verifica si un correo ya existe en la base de datos, excluyendo un usuario dado
     */
    public function emailExists($email, $excludeUserId = null) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = ?";
        $params = [$email];
        if ($excludeUserId !== null) {
            $sql .= " AND id_usuario != ?";
            $params[] = $excludeUserId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Obtiene todas las estadísticas requeridas para el dashboard del perfil
     */
    public function getStats() {
        // 1. Perfiles registrados (activos)
        $perfiles = $this->countRows("perfiles_ocupacionales");

        // 2. Administradores activos (id_rol=1, estado=1)
        $administradores = $this->countRows("usuarios", "id_rol = 1");

        // 3. Empresas registradas (id_rol=2, estado=1)
        $empresas = $this->countRows("usuarios", "id_rol = 2");

        // 4. Usuarios totales activos (estado=1)
        $usuariosTotales = $this->countRows("usuarios");

        // 5. Programas de formación activos
        $programas = $this->countRows("programas_formacion", "estado = 1");

        // 6. Áreas activas
        $areas = $this->countRows("areas");

        // 7. Líneas tecnológicas activas
        $lineas = $this->countRows("lineas_tecnologicas");

        // 8. Tecnologías emergentes activas
        $tecnologiasEmergentes = $this->countRows("tendencias_emergentes");

        // 9. Tendencias actuales: conteo de tendencias distintas que están siendo utilizadas en líneas tecnológicas activas
        $tendenciasActuales = $this->countRows("etapa_desarrollo");;

        // 10. Proyecciones a futuro activas
        $proyecciones = $this->countRows("proyeccion_futuro");

        // 11. Sugerencias activas
        $sugerencias = $this->countRows("sugerencias_blog");

        return [
            'perfiles_registrados'      => $perfiles,
            'administradores_activos'   => $administradores,
            'empresas_registradas'      => $empresas,
            'usuarios_totales'          => $usuariosTotales,
            'programas_formacion'       => $programas,
            'areas'                     => $areas,
            'lineas_tecnologicas'       => $lineas,
            'tecnologias_emergentes'    => $tecnologiasEmergentes,
            'tendencias_actuales'       => $tendenciasActuales,
            'proyecciones_futuro'       => $proyecciones,
            'sugerencias'               => $sugerencias,
        ];
    }

    /**
     * Obtiene los últimos perfiles ocupacionales creados (activos)
     */
    public function getLatestProfiles($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT nombre, estado
            FROM perfiles_ocupacionales
            ORDER BY fecha_creacion DESC
            LIMIT :limit
        ");
        // 🔒 Bind explícito como entero
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Método auxiliar para contar registros con condición
    */
    private function countRows($table, $condition = '1') {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $condition");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Cuenta tendencias distintas que están referenciadas en líneas tecnológicas activas
     */
    private function countDistinctTendenciasEnLineas() {
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT id_tendencia)
            FROM lineas_tecnologicas
            WHERE estado = 1 AND id_tendencia IS NOT NULL
        ");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    //EMPRESA -- NUEVO
    //ESTADISTICAS POR EMPRESA
    public function getStatsByEmpresa($empresaId) {
     

        // Perfiles creados por la empresa
        $sqlPerfiles = "SELECT COUNT(*) as total 
                        FROM perfiles_ocupacionales
                        WHERE id_usuario = :empresaId";
        $stmt = $this->db->prepare($sqlPerfiles);
        $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
        $stmt->execute();
        $perfiles = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Programas de formación (ajusta si tienes relación con empresa)
        $sqlProgramas = "SELECT COUNT(*) as total FROM programas_formacion";
        $stmt = $this->db->prepare($sqlProgramas);
        $stmt->execute();
        $programas = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Sugerencias (si tienes tabla relacionada)
        $sqlSugerencias = "SELECT COUNT(*) as total 
                        FROM sugerencias_blog
                        WHERE id_usuario = :empresaId";
        $stmt = $this->db->prepare($sqlSugerencias);
        $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
        $stmt->execute();
        $sugerencias = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        return [
            'perfiles_registrados' => $perfiles,
            'programas_formacion' => $programas,
            'sugerencias' => $sugerencias
        ];
    }

    //ULTIMOS PERFILES POR EMPRESA
    public function getLatestProfilesByEmpresa($empresaId, $limit) {
        $sql = "SELECT nombre, estado 
                FROM perfiles_ocupacionales
                WHERE id_usuario = :empresaId 
                ORDER BY fecha_creacion DESC 
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
}