<?php

class EtapaDesarrolloModel {

    private $conn;
    private $table = "etapa_desarrollo";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // List all stage active
    public function listar() {
        try {
            $sql = "SELECT e.*, a.nombre_area 
                    FROM etapa_desarrollo e
                    INNER JOIN areas a ON e.id_area = a.id_area
                    WHERE e.estado = 1
                    ORDER BY a.nombre_area, e.nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // List all stage (include inactive for admin)
    public function listarTodas() {
        try {
            $sql = "SELECT e.*, a.nombre_area 
                    FROM etapa_desarrollo e
                    INNER JOIN areas a ON e.id_area = a.id_area
                    ORDER BY a.nombre_area, e.nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get stage for id
    public function obtener($id) {
        try {
            $sql = "SELECT e.*, a.nombre_area 
                    FROM etapa_desarrollo e
                    INNER JOIN areas a ON e.id_area = a.id_area
                    WHERE e.id_etapa = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Create now stage
    public function crear($data) {
        try {
            $sql = "INSERT INTO etapa_desarrollo (id_area, nombre, descripcion, estado) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            
            $ok = $stmt->execute([
                $data['id_area'],
                trim($data['nombre']),
                $data['descripcion'] ?? null,
                $data['estado'] ?? 1
            ]);

            return $ok ? (int)$this->conn->lastInsertId() : false;

        } catch (Exception $e) {
            return false;
        }
    }

    // Update stage exist
    public function actualizar($data) {
        try {
            $campos = [];
            $valores = [];

            $camposPermitidos = ['id_area', 'nombre', 'descripcion', 'estado'];

            foreach ($camposPermitidos as $campo) {
                if (array_key_exists($campo, $data)) {
                    $campos[] = "$campo = ?";
                    $valores[] = $campo === 'nombre' ? trim($data[$campo]) : $data[$campo];
                }
            }

            if (empty($campos)) {
                return false;
            }

            $valores[] = $data['id_etapa'];

            $sql = "UPDATE etapa_desarrollo SET " . implode(", ", $campos) . " WHERE id_etapa = ?";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute($valores);

        } catch (Exception $e) {
            return false;
        }
    }

    // Change state this stage
    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE etapa_desarrollo SET estado = ? WHERE id_etapa = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete stage
    public function eliminar($id) {
        try {
            if ($this->tieneDependencias($id)) {
                return ['success' => false, 'error' => 'La etapa tiene líneas tecnológicas asociadas'];
            }

            $sql = "DELETE FROM etapa_desarrollo WHERE id_etapa = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$id]);
            
            return ['success' => $result];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al eliminar la etapa'];
        }
    }

    // Get stage for areas
    public function obtenerPorArea($id_area) {
        try {
            $sql = "SELECT id_etapa, nombre, descripcion 
                    FROM etapa_desarrollo 
                    WHERE id_area = ? AND estado = 1
                    ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get stage for areas in select
    public function obtenerParaSelectPorArea($id_area) {
        try {
            $sql = "SELECT id_etapa, nombre 
                    FROM etapa_desarrollo 
                    WHERE id_area = ? AND estado = 1
                    ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (Exception $e) {
            return [];
        }
    }

    // Verify if this name in stage exist in area
    public function nombreExisteEnArea($nombre, $id_area, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM etapa_desarrollo 
                    WHERE nombre = ? AND id_area = ?";
            $params = [trim($nombre), $id_area];

            if ($excluir_id) {
                $sql .= " AND id_etapa != ?";
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

    // Verify if this stage have dependences 
    public function tieneDependencias($id_etapa) {
        try {
            $sql = "SELECT COUNT(*) as total FROM lineas_tecnologicas 
                    WHERE id_etapa = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_etapa]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'] > 0;

        } catch (Exception $e) {
            return true;
        }
    }

    // Get statistics of stage
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_etapas,
                        SUM(CASE WHEN e.estado = 1 THEN 1 ELSE 0 END) as etapas_activas,
                        SUM(CASE WHEN e.estado = 0 THEN 1 ELSE 0 END) as etapas_inactivas,
                        COUNT(DISTINCT e.id_area) as areas_con_etapas,
                        (SELECT COUNT(*) FROM areas WHERE estado = 1) as total_areas_activas
                    FROM etapa_desarrollo e";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $sql_por_area = "SELECT a.nombre_area, COUNT(e.id_etapa) as total_etapas
                            FROM areas a
                            LEFT JOIN etapa_desarrollo e ON a.id_area = e.id_area AND e.estado = 1
                            WHERE a.estado = 1
                            GROUP BY a.id_area, a.nombre_area
                            ORDER BY total_etapas DESC
                            LIMIT 5";
            $stmt = $this->conn->prepare($sql_por_area);
            $stmt->execute();
            $estadisticas['etapas_por_area'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $sql_recientes = "SELECT e.id_etapa, e.nombre, a.nombre_area, e.fecha_creacion
                             FROM etapa_desarrollo e
                             INNER JOIN areas a ON e.id_area = a.id_area
                             WHERE e.estado = 1
                             ORDER BY e.fecha_creacion DESC
                             LIMIT 5";
            $stmt = $this->conn->prepare($sql_recientes);
            $stmt->execute();
            $estadisticas['etapas_recientes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $estadisticas;

        } catch (Exception $e) {
            return [];
        }
    }


    // Search stage for term
    public function buscar($termino) {
        try {
            $sql = "SELECT e.*, a.nombre_area 
                    FROM etapa_desarrollo e
                    INNER JOIN areas a ON e.id_area = a.id_area
                    WHERE e.nombre LIKE ? OR e.descripcion LIKE ?
                    ORDER BY a.nombre_area, e.nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $termino_busqueda = "%$termino%";
            $stmt->execute([$termino_busqueda, $termino_busqueda]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get stage for select (all activate)
    public function obtenerParaSelect() {
        try {
            $sql = "SELECT e.id_etapa, CONCAT(a.nombre_area, ' - ', e.nombre) as nombre_completo
                    FROM etapa_desarrollo e
                    INNER JOIN areas a ON e.id_area = a.id_area
                    WHERE e.estado = 1 AND a.estado = 1
                    ORDER BY a.nombre_area, e.nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_etapa']] = $row['nombre_completo'];
            }
            
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }
}