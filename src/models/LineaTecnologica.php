<?php

class LineaTecnologicaModel {

    private $conn;
    private $table = "lineas_tecnologicas";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // List active technological lines with related data
    public function listar() {
        try {
            $sql = "SELECT 
                        l.*,
                        a.nombre_area,
                        p.nombre_programa,
                        p.codigo_programa,
                        e.nombre as nombre_etapa,
                        t.nombre as nombre_tendencia,
                        pf.anio as anio_proyeccion
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    INNER JOIN etapa_desarrollo e ON l.id_etapa = e.id_etapa
                    INNER JOIN tendencias_emergentes t ON l.id_tendencia = t.id_tendencia
                    INNER JOIN proyeccion_futuro pf ON l.id_proyeccion = pf.id_proyeccion
                    WHERE l.estado = 1
                    ORDER BY l.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // List all technological lines (for admin)
    public function listarTodas() {
        try {
            $sql = "SELECT 
                        l.*,
                        a.nombre_area,
                        p.nombre_programa,
                        p.codigo_programa,
                        e.nombre as nombre_etapa,
                        t.nombre as nombre_tendencia,
                        pf.anio as anio_proyeccion
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    INNER JOIN etapa_desarrollo e ON l.id_etapa = e.id_etapa
                    INNER JOIN tendencias_emergentes t ON l.id_tendencia = t.id_tendencia
                    INNER JOIN proyeccion_futuro pf ON l.id_proyeccion = pf.id_proyeccion
                    ORDER BY l.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get a single technological line by ID
    public function obtener($id) {
        try {
            $sql = "SELECT 
                        l.*,
                        a.nombre_area,
                        p.nombre_programa,
                        p.codigo_programa,
                        e.nombre as nombre_etapa,
                        t.nombre as nombre_tendencia,
                        pf.anio as anio_proyeccion
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    INNER JOIN etapa_desarrollo e ON l.id_etapa = e.id_etapa
                    INNER JOIN tendencias_emergentes t ON l.id_tendencia = t.id_tendencia
                    INNER JOIN proyeccion_futuro pf ON l.id_proyeccion = pf.id_proyeccion
                    WHERE l.id_linea = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Create a new technological line
    public function crear($data) {
        try {
            $sql = "INSERT INTO lineas_tecnologicas 
                    (nombre_linea, id_area, id_programa, id_etapa, id_tendencia, id_proyeccion, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $estado = isset($data['estado']) ? $data['estado'] : 1;
            $ok = $stmt->execute([
                trim($data['nombre_linea']),
                $data['id_area'],
                $data['id_programa'],
                $data['id_etapa'],
                $data['id_tendencia'],
                $data['id_proyeccion'],
                $estado
            ]);
            return $ok ? (int)$this->conn->lastInsertId() : false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Update an existing technological line
    public function actualizar($data) {
        try {
            $campos = [];
            $valores = [];

            $camposPermitidos = ['nombre_linea', 'id_area', 'id_programa', 'id_etapa', 'id_tendencia', 'id_proyeccion', 'estado'];

            foreach ($camposPermitidos as $campo) {
                if (array_key_exists($campo, $data)) {
                    $campos[] = "$campo = ?";
                    $valores[] = $campo === 'nombre_linea' ? trim($data[$campo]) : $data[$campo];
                }
            }

            if (empty($campos)) {
                return false;
            }

            $valores[] = $data['id_linea'];

            $sql = "UPDATE lineas_tecnologicas SET " . implode(", ", $campos) . " WHERE id_linea = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($valores);
        } catch (Exception $e) {
            return false;
        }
    }

    // Change state (activate/deactivate)
    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE lineas_tecnologicas SET estado = ? WHERE id_linea = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete a technological line (physical) after checking dependencies
    public function eliminar($id) {
        try {
            if ($this->tieneDependencias($id)) {
                return ['success' => false, 'error' => 'La línea tecnológica tiene perfiles ocupacionales asociados'];
            }

            $sql = "DELETE FROM lineas_tecnologicas WHERE id_linea = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$id]);
            return ['success' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al eliminar la línea tecnológica'];
        }
    }

    // Get lines by area
    public function obtenerPorArea($id_area) {
        try {
            $sql = "SELECT 
                        l.*,
                        p.nombre_programa,
                        e.nombre as nombre_etapa,
                        t.nombre as nombre_tendencia,
                        pf.anio as anio_proyeccion
                    FROM lineas_tecnologicas l
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    INNER JOIN etapa_desarrollo e ON l.id_etapa = e.id_etapa
                    INNER JOIN tendencias_emergentes t ON l.id_tendencia = t.id_tendencia
                    INNER JOIN proyeccion_futuro pf ON l.id_proyeccion = pf.id_proyeccion
                    WHERE l.id_area = ? AND l.estado = 1
                    ORDER BY l.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get lines by program
    public function obtenerPorPrograma($id_programa) {
        try {
            $sql = "SELECT 
                        l.*,
                        a.nombre_area,
                        e.nombre as nombre_etapa,
                        t.nombre as nombre_tendencia,
                        pf.anio as anio_proyeccion
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN etapa_desarrollo e ON l.id_etapa = e.id_etapa
                    INNER JOIN tendencias_emergentes t ON l.id_tendencia = t.id_tendencia
                    INNER JOIN proyeccion_futuro pf ON l.id_proyeccion = pf.id_proyeccion
                    WHERE l.id_programa = ? AND l.estado = 1
                    ORDER BY l.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_programa]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get lines by stage
    public function obtenerPorEtapa($id_etapa) {
        try {
            $sql = "SELECT 
                        l.*,
                        a.nombre_area,
                        p.nombre_programa,
                        t.nombre as nombre_tendencia,
                        pf.anio as anio_proyeccion
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    INNER JOIN tendencias_emergentes t ON l.id_tendencia = t.id_tendencia
                    INNER JOIN proyeccion_futuro pf ON l.id_proyeccion = pf.id_proyeccion
                    WHERE l.id_etapa = ? AND l.estado = 1
                    ORDER BY l.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_etapa]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get lines by trend
    public function obtenerPorTendencia($id_tendencia) {
        try {
            $sql = "SELECT 
                        l.*,
                        a.nombre_area,
                        p.nombre_programa,
                        e.nombre as nombre_etapa,
                        pf.anio as anio_proyeccion
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    INNER JOIN etapa_desarrollo e ON l.id_etapa = e.id_etapa
                    INNER JOIN proyeccion_futuro pf ON l.id_proyeccion = pf.id_proyeccion
                    WHERE l.id_tendencia = ? AND l.estado = 1
                    ORDER BY l.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_tendencia]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get lines by projection
    public function obtenerPorProyeccion($id_proyeccion) {
        try {
            $sql = "SELECT 
                        l.*,
                        a.nombre_area,
                        p.nombre_programa,
                        e.nombre as nombre_etapa,
                        t.nombre as nombre_tendencia
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    INNER JOIN etapa_desarrollo e ON l.id_etapa = e.id_etapa
                    INNER JOIN tendencias_emergentes t ON l.id_tendencia = t.id_tendencia
                    WHERE l.id_proyeccion = ? AND l.estado = 1
                    ORDER BY l.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_proyeccion]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Check if the technological line has associated occupational profiles
    public function tieneDependencias($id_linea) {
        try {
            $sql = "SELECT COUNT(*) as total FROM perfiles_ocupacionales WHERE id_linea = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_linea]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] > 0;
        } catch (Exception $e) {
            return true;
        }
    }

    // Get lines formatted for dropdown (id => descriptive text)
    public function obtenerParaSelect() {
        try {
            $sql = "SELECT 
                        l.id_linea,
                        CONCAT(l.nombre_linea, ' - ', a.nombre_area, ' - ', p.codigo_programa) as texto
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    WHERE l.estado = 1
                    ORDER BY l.nombre_linea ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_linea']] = $row['texto'];
            }
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    // Get lines for dropdown filtered by area
    public function obtenerParaSelectPorArea($id_area) {
        try {
            $sql = "SELECT 
                        l.id_linea,
                        CONCAT(l.nombre_linea, ' - ', p.codigo_programa, ' - ', p.nombre_programa) as texto
                    FROM lineas_tecnologicas l
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    WHERE l.id_area = ? AND l.estado = 1
                    ORDER BY l.nombre_linea ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_linea']] = $row['texto'];
            }
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    // Get statistics about technological lines
    public function obtenerEstadisticas() {
        try {
            $stats = [];

            // Total lines
            $sql = "SELECT COUNT(*) as total FROM lineas_tecnologicas";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $stats['total_lineas'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Active lines
            $sql = "SELECT COUNT(*) as activas FROM lineas_tecnologicas WHERE estado = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $stats['activas'] = $stmt->fetch(PDO::FETCH_ASSOC)['activas'];

            // Inactive lines
            $stats['inactivas'] = $stats['total_lineas'] - $stats['activas'];

            // Lines per area
            $sql = "SELECT a.nombre_area, COUNT(l.id_linea) as total
                    FROM areas a
                    LEFT JOIN lineas_tecnologicas l ON a.id_area = l.id_area
                    WHERE a.estado = 1
                    GROUP BY a.id_area
                    ORDER BY total DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $stats['por_area'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Lines per program (top 5)
            $sql = "SELECT p.nombre_programa, COUNT(l.id_linea) as total
                    FROM programas_formacion p
                    LEFT JOIN lineas_tecnologicas l ON p.id_programa = l.id_programa
                    WHERE p.estado = 1
                    GROUP BY p.id_programa
                    ORDER BY total DESC
                    LIMIT 5";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $stats['por_programa'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Lines per stage
            $sql = "SELECT e.nombre as etapa, COUNT(l.id_linea) as total
                    FROM etapa_desarrollo e
                    LEFT JOIN lineas_tecnologicas l ON e.id_etapa = l.id_etapa
                    WHERE e.estado = 1
                    GROUP BY e.id_etapa
                    ORDER BY total DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $stats['por_etapa'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Lines per trend
            $sql = "SELECT t.nombre as tendencia, COUNT(l.id_linea) as total
                    FROM tendencias_emergentes t
                    LEFT JOIN lineas_tecnologicas l ON t.id_tendencia = l.id_tendencia
                    WHERE t.estado = 1
                    GROUP BY t.id_tendencia
                    ORDER BY total DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $stats['por_tendencia'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $stats;
        } catch (Exception $e) {
            return [];
        }
    }

    // Simple search by related names
    public function buscar($termino) {
        try {
            $sql = "SELECT 
                        l.*,
                        a.nombre_area,
                        p.nombre_programa,
                        e.nombre as nombre_etapa,
                        t.nombre as nombre_tendencia,
                        pf.anio as anio_proyeccion
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    INNER JOIN etapa_desarrollo e ON l.id_etapa = e.id_etapa
                    INNER JOIN tendencias_emergentes t ON l.id_tendencia = t.id_tendencia
                    INNER JOIN proyeccion_futuro pf ON l.id_proyeccion = pf.id_proyeccion
                    WHERE l.estado = 1 
                      AND (l.nombre_linea LIKE ? OR a.nombre_area LIKE ? OR p.nombre_programa LIKE ? OR e.nombre LIKE ? 
                           OR t.nombre LIKE ?)
                    ORDER BY l.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $termino_busqueda = "%$termino%";
            $stmt->execute([$termino_busqueda, $termino_busqueda, $termino_busqueda, $termino_busqueda, $termino_busqueda]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Advanced search with filters
    public function buscarAvanzado($filtros) {
        try {
            $sql = "SELECT 
                        l.*,
                        a.nombre_area,
                        p.nombre_programa,
                        e.nombre as nombre_etapa,
                        t.nombre as nombre_tendencia,
                        pf.anio as anio_proyeccion
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    INNER JOIN programas_formacion p ON l.id_programa = p.id_programa
                    INNER JOIN etapa_desarrollo e ON l.id_etapa = e.id_etapa
                    INNER JOIN tendencias_emergentes t ON l.id_tendencia = t.id_tendencia
                    INNER JOIN proyeccion_futuro pf ON l.id_proyeccion = pf.id_proyeccion
                    WHERE 1=1";
            $params = [];

            if (!empty($filtros['nombre_linea'])) {
                $sql .= " AND l.nombre_linea LIKE ?";
                $params[] = "%" . $filtros['nombre_linea'] . "%";
            }
            if (!empty($filtros['id_area'])) {
                $sql .= " AND l.id_area = ?";
                $params[] = $filtros['id_area'];
            }
            if (!empty($filtros['id_programa'])) {
                $sql .= " AND l.id_programa = ?";
                $params[] = $filtros['id_programa'];
            }
            if (!empty($filtros['id_etapa'])) {
                $sql .= " AND l.id_etapa = ?";
                $params[] = $filtros['id_etapa'];
            }
            if (!empty($filtros['id_tendencia'])) {
                $sql .= " AND l.id_tendencia = ?";
                $params[] = $filtros['id_tendencia'];
            }
            if (!empty($filtros['id_proyeccion'])) {
                $sql .= " AND l.id_proyeccion = ?";
                $params[] = $filtros['id_proyeccion'];
            }
            if (isset($filtros['estado'])) {
                $sql .= " AND l.estado = ?";
                $params[] = $filtros['estado'];
            }
            if (!empty($filtros['fecha_desde'])) {
                $sql .= " AND DATE(l.fecha_creacion) >= ?";
                $params[] = $filtros['fecha_desde'];
            }
            if (!empty($filtros['fecha_hasta'])) {
                $sql .= " AND DATE(l.fecha_creacion) <= ?";
                $params[] = $filtros['fecha_hasta'];
            }

            $sql .= " ORDER BY l.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}