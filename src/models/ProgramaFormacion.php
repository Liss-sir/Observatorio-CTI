<?php

class ProgramaFormacionModel {

    private $conn;
    private $table = "programas_formacion";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // List programs active
    public function listar() {
        try {
            $sql = "SELECT p.*, a.nombre_area, n.nombre_nivel,
                    CASE 
                        WHEN p.modalidad = 'PRESENCIAL' THEN 'Presencial'
                        WHEN p.modalidad = 'VIRTUAL' THEN 'Virtual'
                    END as modalidad_texto
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.estado = 1
                    ORDER BY a.nombre_area, p.nombre_programa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // List all programs (For admin)
    public function listarTodas() {
        try {
            $sql = "SELECT p.*, a.nombre_area, n.nombre_nivel,
                    CASE 
                        WHEN p.modalidad = 'PRESENCIAL' THEN 'Presencial'
                        WHEN p.modalidad = 'VIRTUAL' THEN 'Virtual'
                    END as modalidad_texto
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    ORDER BY a.nombre_area, p.nombre_programa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get program for ID
    public function obtener($id) {
        try {
            $sql = "SELECT p.*, a.nombre_area, n.nombre_nivel
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.id_programa = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Get program for code
    public function obtenerPorCodigo($codigo) {
        try {
            $sql = "SELECT p.*, a.nombre_area, n.nombre_nivel
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.codigo_programa = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$codigo]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Create new program
    public function crear($data) {
        try {
            $sql = "INSERT INTO programas_formacion (
                id_area, codigo_programa, nombre_programa, id_nivel, 
                fecha_creacion, fecha_fin, modalidad, descripcion, estado, cupos
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
            
            $stmt = $this->conn->prepare($sql);
            
            $ok = $stmt->execute([
                $data['id_area'],
                $data['codigo_programa'],
                trim($data['nombre_programa']),
                $data['id_nivel'],
                $data['fecha_creacion'],
                $data['fecha_fin'],
                $data['modalidad'] ?? 'PRESENCIAL',
                $data['descripcion'] ?? null,
                $data['estado'] ?? 1,
                $data['cupos'] ?? 0 
            ]);

            return $ok ? (int)$this->conn->lastInsertId() : false;

        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
            exit;
        }
    }

    // Update program exist
    public function actualizar($data) {
    try {
        $campos = [];
        $valores = [];

        $camposPermitidos = [
            'id_area', 'codigo_programa', 'nombre_programa', 'id_nivel',
            'fecha_creacion', 'fecha_fin', 'modalidad', 'descripcion', 'estado',
            'cupos' 
        ];

        foreach ($camposPermitidos as $campo) {
            if (array_key_exists($campo, $data)) {
                $campos[] = "$campo = ?";
                $valores[] = $campo === 'nombre_programa' ? trim($data[$campo]) : $data[$campo];
            }
        }

        // If there program no field no update
        if (empty($campos)) {
            return false;
        }

        // Add ID at the end
        $valores[] = $data['id_programa'];

        $sql = "UPDATE programas_formacion SET " . implode(", ", $campos) . " WHERE id_programa = ?";
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute($valores);

    } catch (Exception $e) {
        return false;
    }
}

    // Change state in programs
    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE programas_formacion SET estado = ? WHERE id_programa = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete programs
    public function eliminar($id) {
        try {
            // Verify that program have dependences
            if ($this->tieneDependencias($id)) {
                return ['success' => false, 'error' => 'El programa tiene líneas tecnológicas asociadas'];
            }

            $sql = "DELETE FROM programas_formacion WHERE id_programa = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$id]);
            
            return ['success' => $result];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al eliminar el programa'];
        }
    }

    // Get programs for area
    public function obtenerPorArea($id_area) {
        try {
            $sql = "SELECT p.*, n.nombre_nivel,
                    CASE 
                        WHEN p.modalidad = 'PRESENCIAL' THEN 'Presencial'
                        WHEN p.modalidad = 'VIRTUAL' THEN 'Virtual'
                    END as modalidad_texto
                    FROM programas_formacion p
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.id_area = ? AND p.estado = 1
                    ORDER BY p.nombre_programa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get programs for select by area
    public function obtenerParaSelectPorArea($id_area) {
        try {
            $sql = "SELECT id_programa, CONCAT(codigo_programa, ' - ', nombre_programa) as nombre_completo
                    FROM programas_formacion 
                    WHERE id_area = ? AND estado = 1
                    ORDER BY codigo_programa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_programa']] = $row['nombre_completo'];
            }
            
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    // Get programs for level
    public function obtenerPorNivel($id_nivel) {
        try {
            $sql = "SELECT p.*, a.nombre_area 
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    WHERE p.id_nivel = ? AND p.estado = 1
                    ORDER BY a.nombre_area, p.nombre_programa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_nivel]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get programs for modality
    public function obtenerPorModalidad($modalidad) {
        try {
            $sql = "SELECT p.*, a.nombre_area, n.nombre_nivel
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.modalidad = ? AND p.estado = 1
                    ORDER BY a.nombre_area, p.nombre_programa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$modalidad]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get programs active in current date
    public function obtenerActivosEnFecha($fecha = null) {
        try {
            $fecha = $fecha ?? date('Y-m-d');
            
            $sql = "SELECT p.*, a.nombre_area, n.nombre_nivel
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.estado = 1 
                    AND p.fecha_creacion <= ? 
                    AND p.fecha_fin >= ?
                    ORDER BY a.nombre_area, p.nombre_programa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$fecha, $fecha]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get programs soon to end
    public function obtenerProximosAFinalizar($dias = 30) {
        try {
            $fecha_limite = date('Y-m-d', strtotime("+$dias days"));
            
            $sql = "SELECT p.*, a.nombre_area, n.nombre_nivel,
                    DATEDIFF(p.fecha_fin, CURDATE()) as dias_restantes
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.estado = 1 
                    AND p.fecha_fin BETWEEN CURDATE() AND ?
                    ORDER BY p.fecha_fin ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$fecha_limite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Verify that the code of this program exist
    public function codigoExiste($codigo, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM programas_formacion WHERE codigo_programa = ?";
            $params = [$codigo];

            if ($excluir_id) {
                $sql .= " AND id_programa != ?";
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

    // Verify that the name of this program exist in area
    public function nombreExisteEnArea($nombre, $id_area, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM programas_formacion 
                    WHERE nombre_programa = ? AND id_area = ?";
            $params = [trim($nombre), $id_area];

            if ($excluir_id) {
                $sql .= " AND id_programa != ?";
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

    // Verify if programs have dependences
    public function tieneDependencias($id_programa) {
        try {
            $sql = "SELECT COUNT(*) as total FROM lineas_tecnologicas 
                    WHERE id_programa = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_programa]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'] > 0;

        } catch (Exception $e) {
            return true;
        }
    }

    // Validate program dates
    public function validarFechas($fecha_creacion, $fecha_fin) {
        $errores = [];
        
        if (strtotime($fecha_creacion) > strtotime($fecha_fin)) {
            $errores[] = 'La fecha de inicio no puede ser mayor a la fecha de fin';
        }
        
        if (strtotime($fecha_creacion) < strtotime(date('Y-m-d'))) {
            $errores[] = 'La fecha de inicio no puede ser anterior a la fecha actual';
        }
        
        return $errores;
    }

    // Get statistics for programs
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_programas,
                        SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as programas_activos,
                        SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as programas_inactivos,
                        COUNT(DISTINCT id_area) as areas_con_programas,
                        COUNT(DISTINCT id_nivel) as niveles_utilizados
                    FROM programas_formacion";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Programs for area
            $sql_por_area = "SELECT a.nombre_area, COUNT(p.id_programa) as total_programas
                            FROM areas a
                            LEFT JOIN programas_formacion p ON a.id_area = p.id_area AND p.estado = 1
                            WHERE a.estado = 1
                            GROUP BY a.id_area, a.nombre_area
                            ORDER BY total_programas DESC
                            LIMIT 5";
            $stmt = $this->conn->prepare($sql_por_area);
            $stmt->execute();
            $estadisticas['programas_por_area'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Programs for level
            $sql_por_nivel = "SELECT n.nombre_nivel, COUNT(p.id_programa) as total_programas
                             FROM niveles_formacion n
                             LEFT JOIN programas_formacion p ON n.id_nivel = p.id_nivel AND p.estado = 1
                             WHERE n.estado = 1
                             GROUP BY n.id_nivel, n.nombre_nivel
                             ORDER BY total_programas DESC";
            $stmt = $this->conn->prepare($sql_por_nivel);
            $stmt->execute();
            $estadisticas['programas_por_nivel'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Programs for modality
            $sql_por_modalidad = "SELECT 
                                    CASE 
                                        WHEN modalidad = 'PRESENCIAL' THEN 'Presencial'
                                        WHEN modalidad = 'VIRTUAL' THEN 'Virtual'
                                    END as modalidad,
                                    COUNT(*) as total
                                 FROM programas_formacion
                                 WHERE estado = 1
                                 GROUP BY modalidad";
            $stmt = $this->conn->prepare($sql_por_modalidad);
            $stmt->execute();
            $estadisticas['programas_por_modalidad'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Programs soon to end
            $sql_proximos = "SELECT p.nombre_programa, a.nombre_area, p.fecha_fin,
                            DATEDIFF(p.fecha_fin, CURDATE()) as dias_restantes
                            FROM programas_formacion p
                            INNER JOIN areas a ON p.id_area = a.id_area
                            WHERE p.estado = 1 AND p.fecha_fin > CURDATE()
                            ORDER BY p.fecha_fin ASC
                            LIMIT 5";
            $stmt = $this->conn->prepare($sql_proximos);
            $stmt->execute();
            $estadisticas['programas_proximos_finalizar'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $estadisticas;

        } catch (Exception $e) {
            return [];
        }
    }

    // Search programs for term
    public function buscar($termino) {
        try {
            $sql = "SELECT p.*, a.nombre_area, n.nombre_nivel,
                    CASE 
                        WHEN p.modalidad = 'PRESENCIAL' THEN 'Presencial'
                        WHEN p.modalidad = 'VIRTUAL' THEN 'Virtual'
                    END as modalidad_texto
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE (p.nombre_programa LIKE ? OR p.codigo_programa LIKE ? OR p.descripcion LIKE ?)
                    AND p.estado = 1
                    ORDER BY a.nombre_area, p.nombre_programa ASC";
            $stmt = $this->conn->prepare($sql);
            $termino_busqueda = "%$termino%";
            $stmt->execute([$termino_busqueda, $termino_busqueda, $termino_busqueda]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Advanced search with filters
    public function buscarAvanzado($filtros) {
        try {
            $sql = "SELECT p.*, a.nombre_area, n.nombre_nivel,
                    CASE 
                        WHEN p.modalidad = 'PRESENCIAL' THEN 'Presencial'
                        WHEN p.modalidad = 'VIRTUAL' THEN 'Virtual'
                    END as modalidad_texto
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE 1=1";
            $params = [];

            if (!empty($filtros['id_area'])) {
                $sql .= " AND p.id_area = ?";
                $params[] = $filtros['id_area'];
            }

            if (!empty($filtros['id_nivel'])) {
                $sql .= " AND p.id_nivel = ?";
                $params[] = $filtros['id_nivel'];
            }

            if (!empty($filtros['modalidad'])) {
                $sql .= " AND p.modalidad = ?";
                $params[] = $filtros['modalidad'];
            }

            if (!empty($filtros['estado'])) {
                $sql .= " AND p.estado = ?";
                $params[] = $filtros['estado'];
            }

            if (!empty($filtros['fecha_desde'])) {
                $sql .= " AND p.fecha_creacion >= ?";
                $params[] = $filtros['fecha_desde'];
            }

            if (!empty($filtros['fecha_hasta'])) {
                $sql .= " AND p.fecha_creacion <= ?";
                $params[] = $filtros['fecha_hasta'];
            }

            $sql .= " ORDER BY a.nombre_area, p.nombre_programa ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get programs for select (all actives)
    public function obtenerParaSelect() {
        try {
            $sql = "SELECT p.id_programa, 
                    CONCAT(a.nombre_area, ' - ', p.codigo_programa, ' - ', p.nombre_programa) as nombre_completo
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    WHERE p.estado = 1 AND a.estado = 1
                    ORDER BY a.nombre_area, p.codigo_programa ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_programa']] = $row['nombre_completo'];
            }
            
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    // Get programs with technological lines associated
    public function obtenerConLineasTecnologicas() {
        try {
            $sql = "SELECT p.id_programa, p.codigo_programa, p.nombre_programa,
                           a.nombre_area, n.nombre_nivel,
                           COUNT(lt.id_linea) as total_lineas
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    LEFT JOIN lineas_tecnologicas lt ON p.id_programa = lt.id_programa
                    WHERE p.estado = 1
                    GROUP BY p.id_programa, p.codigo_programa, p.nombre_programa, 
                             a.nombre_area, n.nombre_nivel
                    ORDER BY total_lineas DESC, p.nombre_programa ASC
                    LIMIT 10";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get modalities available
    public function obtenerModalidades() {
        return [
            'PRESENCIAL' => 'Presencial',
            'VIRTUAL' => 'Virtual'
        ];
    }
}