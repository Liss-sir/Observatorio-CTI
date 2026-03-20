<?php

class TendenciaEmergenteModel {

    private $conn;
    private $table = "tendencias_emergentes";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // Listar tendencias activas
    public function listar() {
        try {
            $sql = "SELECT t.*, a.nombre_area 
                    FROM tendencias_emergentes t
                    INNER JOIN areas a ON t.id_area = a.id_area
                    WHERE t.estado = 1
                    ORDER BY a.nombre_area, t.descripcion ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Listar todas las tendencias (incluye inactivas para admin)
    public function listarTodas() {
        try {
            $sql = "SELECT t.*, a.nombre_area 
                    FROM tendencias_emergentes t
                    INNER JOIN areas a ON t.id_area = a.id_area
                    ORDER BY a.nombre_area, t.descripcion ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Obtener tendencia por ID
    public function obtener($id) {
        try {
            $sql = "SELECT t.*, a.nombre_area 
                    FROM tendencias_emergentes t
                    INNER JOIN areas a ON t.id_area = a.id_area
                    WHERE t.id_tendencia = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Crear nueva tendencia
    public function crear($data) {
        try {
            $sql = "INSERT INTO tendencias_emergentes (id_area, descripcion, estado) 
                    VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            
            $ok = $stmt->execute([
                $data['id_area'],
                trim($data['descripcion']),
                $data['estado'] ?? 1
            ]);

            return $ok ? (int)$this->conn->lastInsertId() : false;

        } catch (Exception $e) {
            return false;
        }
    }

    // Actualizar tendencia existente
    public function actualizar($data) {
        try {
            $campos = [];
            $valores = [];

            $camposPermitidos = ['id_area', 'descripcion', 'estado'];

            foreach ($camposPermitidos as $campo) {
                if (array_key_exists($campo, $data)) {
                    $campos[] = "$campo = ?";
                    $valores[] = $campo === 'descripcion' ? trim($data[$campo]) : $data[$campo];
                }
            }

            if (empty($campos)) {
                return false;
            }

            $valores[] = $data['id_tendencia'];

            $sql = "UPDATE tendencias_emergentes SET " . implode(", ", $campos) . " WHERE id_tendencia = ?";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute($valores);

        } catch (Exception $e) {
            return false;
        }
    }

    // Cambiar estado de la tendencia
    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE tendencias_emergentes SET estado = ? WHERE id_tendencia = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Eliminar tendencia
    public function eliminar($id) {
        try {
            if ($this->tieneDependencias($id)) {
                return ['success' => false, 'error' => 'La tendencia tiene líneas tecnológicas asociadas'];
            }

            $sql = "DELETE FROM tendencias_emergentes WHERE id_tendencia = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$id]);
            
            return ['success' => $result];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al eliminar la tendencia'];
        }
    }

    // Obtener tendencias por área
    public function obtenerPorArea($id_area) {
        try {
            $sql = "SELECT id_tendencia, descripcion 
                    FROM tendencias_emergentes 
                    WHERE id_area = ? AND estado = 1
                    ORDER BY descripcion ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Obtener tendencias para select por área (con descripción truncada)
    public function obtenerParaSelectPorArea($id_area) {
        try {
            $sql = "SELECT id_tendencia, 
                           CONCAT(LEFT(descripcion, 50), IF(LENGTH(descripcion) > 50, '...', '')) as texto
                    FROM tendencias_emergentes 
                    WHERE id_area = ? AND estado = 1
                    ORDER BY descripcion ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_tendencia']] = $row['texto'];
            }
            
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    // Buscar tendencias por descripción
    public function buscar($termino) {
        try {
            $sql = "SELECT t.*, a.nombre_area 
                    FROM tendencias_emergentes t
                    INNER JOIN areas a ON t.id_area = a.id_area
                    WHERE t.descripcion LIKE ? AND t.estado = 1
                    ORDER BY a.nombre_area, t.descripcion ASC";
            $stmt = $this->conn->prepare($sql);
            $termino_busqueda = "%$termino%";
            $stmt->execute([$termino_busqueda]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Búsqueda avanzada con filtros
    public function buscarAvanzado($filtros) {
        try {
            $sql = "SELECT t.*, a.nombre_area 
                    FROM tendencias_emergentes t
                    INNER JOIN areas a ON t.id_area = a.id_area
                    WHERE 1=1";
            $params = [];

            if (!empty($filtros['id_area'])) {
                $sql .= " AND t.id_area = ?";
                $params[] = $filtros['id_area'];
            }

            if (!empty($filtros['estado'])) {
                $sql .= " AND t.estado = ?";
                $params[] = $filtros['estado'];
            }

            if (!empty($filtros['descripcion'])) {
                $sql .= " AND t.descripcion LIKE ?";
                $params[] = "%{$filtros['descripcion']}%";
            }

            $sql .= " ORDER BY a.nombre_area, t.descripcion ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Verificar si la descripción ya existe en el área
    public function descripcionExisteEnArea($descripcion, $id_area, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM tendencias_emergentes 
                    WHERE descripcion = ? AND id_area = ?";
            $params = [trim($descripcion), $id_area];

            if ($excluir_id) {
                $sql .= " AND id_tendencia != ?";
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

    // Verificar si la tendencia tiene dependencias
    public function tieneDependencias($id_tendencia) {
        try {
            $sql = "SELECT COUNT(*) as total FROM lineas_tecnologicas 
                    WHERE id_tendencia = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_tendencia]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'] > 0;

        } catch (Exception $e) {
            return true;
        }
    }

    // Obtener estadísticas de tendencias
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_tendencias,
                        SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as tendencias_activas,
                        SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as tendencias_inactivas,
                        COUNT(DISTINCT id_area) as areas_con_tendencias
                    FROM tendencias_emergentes";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $sql_por_area = "SELECT a.nombre_area, COUNT(t.id_tendencia) as total_tendencias
                            FROM areas a
                            LEFT JOIN tendencias_emergentes t ON a.id_area = t.id_area AND t.estado = 1
                            WHERE a.estado = 1
                            GROUP BY a.id_area, a.nombre_area
                            ORDER BY total_tendencias DESC
                            LIMIT 5";
            $stmt = $this->conn->prepare($sql_por_area);
            $stmt->execute();
            $estadisticas['tendencias_por_area'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $sql_recientes = "SELECT t.id_tendencia, t.descripcion, a.nombre_area, t.fecha_creacion
                             FROM tendencias_emergentes t
                             INNER JOIN areas a ON t.id_area = a.id_area
                             WHERE t.estado = 1
                             ORDER BY t.fecha_creacion DESC
                             LIMIT 5";
            $stmt = $this->conn->prepare($sql_recientes);
            $stmt->execute();
            $estadisticas['tendencias_recientes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $sql_sin_tendencias = "SELECT COUNT(*) as areas_sin_tendencias
                                  FROM areas a
                                  LEFT JOIN tendencias_emergentes t ON a.id_area = t.id_area AND t.estado = 1
                                  WHERE a.estado = 1 AND t.id_tendencia IS NULL";
            $stmt = $this->conn->prepare($sql_sin_tendencias);
            $stmt->execute();
            $estadisticas['areas_sin_tendencias'] = $stmt->fetch(PDO::FETCH_ASSOC)['areas_sin_tendencias'];
            
            return $estadisticas;

        } catch (Exception $e) {
            return [];
        }
    }

    // Obtener todas las tendencias para select (global)
    public function obtenerParaSelect() {
        try {
            $sql = "SELECT t.id_tendencia, 
                           CONCAT(a.nombre_area, ' - ', LEFT(t.descripcion, 50), IF(LENGTH(t.descripcion) > 50, '...', '')) as texto
                    FROM tendencias_emergentes t
                    INNER JOIN areas a ON t.id_area = a.id_area
                    WHERE t.estado = 1 AND a.estado = 1
                    ORDER BY a.nombre_area, t.descripcion ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_tendencia']] = $row['texto'];
            }
            
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    // Obtener tendencias populares (más usadas en líneas)
    public function obtenerTendenciasPopulares($limite = 5) {
        try {
            $sql = "SELECT t.id_tendencia, t.descripcion, a.nombre_area, COUNT(lt.id_linea) as total_lineas
                    FROM tendencias_emergentes t
                    INNER JOIN areas a ON t.id_area = a.id_area
                    LEFT JOIN lineas_tecnologicas lt ON t.id_tendencia = lt.id_tendencia
                    WHERE t.estado = 1
                    GROUP BY t.id_tendencia, t.descripcion, a.nombre_area
                    ORDER BY total_lineas DESC, t.descripcion ASC
                    LIMIT ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$limite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Obtener tendencias con conteo de líneas asociadas
    public function obtenerConConteoLineas($id_area = null) {
        try {
            $sql = "SELECT t.id_tendencia, t.descripcion, t.estado,
                           COUNT(lt.id_linea) as total_lineas
                    FROM tendencias_emergentes t
                    LEFT JOIN lineas_tecnologicas lt ON t.id_tendencia = lt.id_tendencia
                    WHERE 1=1";
            $params = [];

            if ($id_area) {
                $sql .= " AND t.id_area = ?";
                $params[] = $id_area;
            }

            $sql .= " GROUP BY t.id_tendencia, t.descripcion, t.estado
                      ORDER BY t.descripcion ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}