<?php

class AreaModel {

    private $conn;
    private $table = "areas";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // List all areas active
    public function listar() {
        try {
            $sql = "SELECT id_area, nombre_area, descripcion_area, estado 
                    FROM areas 
                    WHERE estado = 1
                    ORDER BY nombre_area ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // List all areas
    public function listarTodas() {
        try {
            $sql = "SELECT id_area, nombre_area, descripcion_area, estado 
                    FROM areas 
                    ORDER BY nombre_area ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get areas for id
    public function obtener($id) {
        try {
            $sql = "SELECT id_area, nombre_area, descripcion_area, estado 
                    FROM areas 
                    WHERE id_area = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Create areas
    public function crear($data) {
        try {
            // CORRECCIÓN: 3 columnas -> 3 signos de interrogación
            $sql = "INSERT INTO areas (nombre_area, descripcion_area, estado) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            
            // Aseguramos que los datos existan y tengan valor por defecto si vienen vacíos
            $nombre = trim($data['nombre_area'] ?? '');
            $descripcion = trim($data['descripcion'] ?? '');
            $estado = isset($data['estado']) ? (int)$data['estado'] : 1;

            $ok = $stmt->execute([
                $nombre,
                $descripcion, // <--- ¡Agregado! Ahora sí pasamos la descripción
                $estado
            ]);

            return $ok ? (int)$this->conn->lastInsertId() : false;

        } catch (Exception $e) {
            // Opcional: Guardar el error en un log para depurar
            error_log("Error al crear área: " . $e->getMessage());
            return false;
        }
    }

    // Update areas that exist
    public function actualizar($data) {
        try {
            $campos = [];
            $valores = [];

            $camposPermitidos = ['nombre_area','descripcion','estado'];
            $columnMap = [
                'descripcion' => 'descripcion_area'
            ];

            foreach ($camposPermitidos as $campo) {
                if (array_key_exists($campo, $data)) {
                    $columna = $columnMap[$campo] ?? $campo;
                    $campos[] = "$columna = ?";
                    $valores[] = $campo === 'nombre_area' ? trim($data[$campo]) : $data[$campo];
                }
            }

            // If there areas no field no update
            if (empty($campos)) {
                return false;
            }

            // Add ID at the end
            $valores[] = $data['id_area'];

            $sql = "UPDATE areas SET " . implode(", ", $campos) . " WHERE id_area = ?";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute($valores);

        } catch (Exception $e) {
            return false;
        }
    }

    // Change state
    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE areas SET estado = ? WHERE id_area = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete areas
    public function eliminar($id) {
        try {
            // Verify that area have dependences
            if ($this->tieneDependencias($id)) {
                return ['success' => false, 'error' => 'El área tiene registros asociados'];
            }

            $sql = "DELETE FROM areas WHERE id_area = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$id]);
            
            return ['success' => $result];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al eliminar el área'];
        }
    }

    // Verify that the name of this area exist
    public function nombreExiste($nombre_area, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM areas WHERE nombre_area = ?";
            $params = [trim($nombre_area)];

            if ($excluir_id) {
                $sql .= " AND id_area != ?";
                $params[] = $excluir_id;
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'] > 0;

        } catch (Exception $e) {
            return false;
        }
    }

    // Verify if areas have dependencies
    public function tieneDependencias($id_area) {
        try {
            $sql = "SELECT 
                        (SELECT COUNT(*) FROM etapa_desarrollo WHERE id_area = ?) as total_etapas,
                        (SELECT COUNT(*) FROM programas_formacion WHERE id_area = ?) as total_programas,
                        (SELECT COUNT(*) FROM tendencias_emergentes WHERE id_area = ?) as total_tendencias,
                        (SELECT COUNT(*) FROM proyeccion_futuro WHERE id_area = ?) as total_proyecciones,
                        (SELECT COUNT(*) FROM lineas_tecnologicas WHERE id_area = ?) as total_lineas";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area, $id_area, $id_area, $id_area, $id_area]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            foreach ($result as $total) {
                if ($total > 0) {
                    return true;
                }
            }
            
            return false;

        } catch (Exception $e) {
            return true; 
        }
    }

    // Ges statistic for areas
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_areas,
                        SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as areas_activas,
                        SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as areas_inactivas,
                        (SELECT COUNT(DISTINCT id_area) FROM etapa_desarrollo WHERE estado = 1) as areas_con_etapas,
                        (SELECT COUNT(DISTINCT id_area) FROM programas_formacion WHERE estado = 1) as areas_con_programas
                    FROM areas";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get areas most recent
            $sql_recientes = "SELECT id_area, nombre_area, fecha_creacion 
                             FROM areas 
                             WHERE estado = 1 
                             ORDER BY fecha_creacion DESC 
                             LIMIT 5";
            $stmt = $this->conn->prepare($sql_recientes);
            $stmt->execute();
            $estadisticas['areas_recientes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $estadisticas;

        } catch (Exception $e) {
            return [];
        }
    }

    // Search areas foor name
    public function buscar($termino) {
        try {
            $sql = "SELECT id_area, nombre_area, estado, descripcion_area 
                    FROM areas 
                    WHERE nombre_area LIKE ? 
                    ORDER BY nombre_area ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["%$termino%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get Areas for select
    public function obtenerParaSelect() {
        try {
            $sql = "SELECT id_area, nombre_area 
                    FROM areas 
                    WHERE estado = 1 
                    ORDER BY nombre_area ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (Exception $e) {
            return [];
        }
    }
}