<?php

class ProyeccionFuturoModel {

    private $conn;
    private $table = "proyeccion_futuro";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /* ================= PROYECCIONES A FUTURO (CRUD BÁSICO) ================= */

    /**
     * Listar todas las proyecciones activas
     */
    public function listar() {
        try {
            $sql = "SELECT p.*, a.nombre_area,
                    CASE 
                        WHEN p.anio = '1' THEN '1 año'
                        WHEN p.anio = '2' THEN '2 años'
                        WHEN p.anio = '3' THEN '3 años'
                        WHEN p.anio = '4' THEN '4 años'
                        WHEN p.anio = '5' THEN '5 años'
                        WHEN p.anio = '6' THEN '6 años'
                        WHEN p.anio = '7' THEN '7 años'
                        WHEN p.anio = '8' THEN '8 años'
                        WHEN p.anio = '9' THEN '9 años'
                        WHEN p.anio = '10' THEN '10 años'
                    END as anio_texto
                    FROM proyeccion_futuro p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    WHERE p.estado = 1
                    ORDER BY a.nombre_area, p.anio ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Listar todas las proyecciones (incluyendo inactivas para administración)
     */
    public function listarTodas() {
        try {
            $sql = "SELECT p.*, a.nombre_area,
                    CASE 
                        WHEN p.anio = '1' THEN '1 año'
                        WHEN p.anio = '2' THEN '2 años'
                        WHEN p.anio = '3' THEN '3 años'
                        WHEN p.anio = '4' THEN '4 años'
                        WHEN p.anio = '5' THEN '5 años'
                        WHEN p.anio = '6' THEN '6 años'
                        WHEN p.anio = '7' THEN '7 años'
                        WHEN p.anio = '8' THEN '8 años'
                        WHEN p.anio = '9' THEN '9 años'
                        WHEN p.anio = '10' THEN '10 años'
                    END as anio_texto
                    FROM proyeccion_futuro p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    ORDER BY a.nombre_area, p.anio ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener una proyección por ID
     */
    public function obtener($id) {
        try {
            $sql = "SELECT p.*, a.nombre_area 
                    FROM proyeccion_futuro p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    WHERE p.id_proyeccion = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Crear una nueva proyección
     */
    public function crear($data) {
        try {
            $sql = "INSERT INTO proyeccion_futuro (id_area, anio, descripcion, estado) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            
            $ok = $stmt->execute([
                $data['id_area'],
                $data['anio'],
                $data['descripcion'] ?? null,
                $data['estado'] ?? 1
            ]);

            return $ok ? (int)$this->conn->lastInsertId() : false;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Actualizar proyección existente
     */
    public function actualizar($data) {
        try {
            $campos = [];
            $valores = [];

            $camposPermitidos = ['id_area', 'anio', 'descripcion', 'estado'];

            foreach ($camposPermitidos as $campo) {
                if (array_key_exists($campo, $data)) {
                    $campos[] = "$campo = ?";
                    $valores[] = $data[$campo];
                }
            }

            if (empty($campos)) {
                return false;
            }

            $valores[] = $data['id_proyeccion'];

            $sql = "UPDATE proyeccion_futuro SET " . implode(", ", $campos) . " WHERE id_proyeccion = ?";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute($valores);

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Cambiar estado de la proyección (activar/desactivar)
     */
    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE proyeccion_futuro SET estado = ? WHERE id_proyeccion = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Eliminar proyección (borrado físico)
     */
    public function eliminar($id) {
        try {
            // Verificar si la proyección tiene dependencias
            if ($this->tieneDependencias($id)) {
                return ['success' => false, 'error' => 'La proyección tiene líneas tecnológicas asociadas'];
            }

            $sql = "DELETE FROM proyeccion_futuro WHERE id_proyeccion = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$id]);
            
            return ['success' => $result];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al eliminar la proyección'];
        }
    }

    /* ================= MÉTODOS POR ÁREA ================= */

    /**
     * Obtener proyecciones por área
     */
    public function obtenerPorArea($id_area) {
        try {
            $sql = "SELECT id_proyeccion, anio, descripcion,
                    CASE 
                        WHEN anio = '1' THEN '1 año'
                        WHEN anio = '2' THEN '2 años'
                        WHEN anio = '3' THEN '3 años'
                        WHEN anio = '4' THEN '4 años'
                        WHEN anio = '5' THEN '5 años'
                        WHEN anio = '6' THEN '6 años'
                        WHEN anio = '7' THEN '7 años'
                        WHEN anio = '8' THEN '8 años'
                        WHEN anio = '9' THEN '9 años'
                        WHEN anio = '10' THEN '10 años'
                    END as anio_texto
                    FROM proyeccion_futuro 
                    WHERE id_area = ? AND estado = 1
                    ORDER BY anio ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener proyecciones para select por área
     */
    public function obtenerParaSelectPorArea($id_area) {
        try {
            $sql = "SELECT id_proyeccion, 
                    CONCAT(
                        CASE 
                            WHEN anio = '1' THEN '1 año'
                            WHEN anio = '2' THEN '2 años'
                            WHEN anio = '3' THEN '3 años'
                            WHEN anio = '4' THEN '4 años'
                            WHEN anio = '5' THEN '5 años'
                            WHEN anio = '6' THEN '6 años'
                            WHEN anio = '7' THEN '7 años'
                            WHEN anio = '8' THEN '8 años'
                            WHEN anio = '9' THEN '9 años'
                            WHEN anio = '10' THEN '10 años'
                        END,
                        ' - ',
                        LEFT(descripcion, 50),
                        IF(LENGTH(descripcion) > 50, '...', '')
                    ) as nombre_completo
                    FROM proyeccion_futuro 
                    WHERE id_area = ? AND estado = 1
                    ORDER BY anio ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_area]);
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_proyeccion']] = $row['nombre_completo'];
            }
            
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    /* ================= MÉTODOS POR AÑO ================= */

    /**
     * Obtener proyecciones por año
     */
    public function obtenerPorAnio($anio) {
        try {
            $sql = "SELECT p.*, a.nombre_area 
                    FROM proyeccion_futuro p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    WHERE p.anio = ? AND p.estado = 1
                    ORDER BY a.nombre_area";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$anio]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener años disponibles
     */
    public function obtenerAniosDisponibles() {
        try {
            $sql = "SELECT DISTINCT anio,
                    CASE 
                        WHEN anio = '1' THEN '1 año'
                        WHEN anio = '2' THEN '2 años'
                        WHEN anio = '3' THEN '3 años'
                        WHEN anio = '4' THEN '4 años'
                        WHEN anio = '5' THEN '5 años'
                        WHEN anio = '6' THEN '6 años'
                        WHEN anio = '7' THEN '7 años'
                        WHEN anio = '8' THEN '8 años'
                        WHEN anio = '9' THEN '9 años'
                        WHEN anio = '10' THEN '10 años'
                    END as anio_texto
                    FROM proyeccion_futuro 
                    WHERE estado = 1
                    ORDER BY anio ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /* ================= MÉTODOS DE VALIDACIÓN ================= */

    /**
     * Verificar si ya existe una proyección para el área y año
     */
    public function existePorAreaYAnio($id_area, $anio, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM proyeccion_futuro 
                    WHERE id_area = ? AND anio = ?";
            $params = [$id_area, $anio];

            if ($excluir_id) {
                $sql .= " AND id_proyeccion != ?";
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

    /**
     * Verificar si la proyección tiene dependencias (líneas tecnológicas)
     */
    public function tieneDependencias($id_proyeccion) {
        try {
            $sql = "SELECT COUNT(*) as total FROM lineas_tecnologicas 
                    WHERE id_proyeccion = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_proyeccion]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'] > 0;

        } catch (Exception $e) {
            return true;
        }
    }

    /* ================= ESTADÍSTICAS ================= */

    /**
     * Obtener estadísticas de proyecciones
     */
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_proyecciones,
                        SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as proyecciones_activas,
                        SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as proyecciones_inactivas,
                        COUNT(DISTINCT id_area) as areas_con_proyecciones,
                        COUNT(DISTINCT anio) as anios_con_proyecciones
                    FROM proyeccion_futuro";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Proyecciones por año
            $sql_por_anio = "SELECT 
                                anio,
                                CASE 
                                    WHEN anio = '1' THEN '1 año'
                                    WHEN anio = '2' THEN '2 años'
                                    WHEN anio = '3' THEN '3 años'
                                    WHEN anio = '4' THEN '4 años'
                                    WHEN anio = '5' THEN '5 años'
                                    WHEN anio = '6' THEN '6 años'
                                    WHEN anio = '7' THEN '7 años'
                                    WHEN anio = '8' THEN '8 años'
                                    WHEN anio = '9' THEN '9 años'
                                    WHEN anio = '10' THEN '10 años'
                                END as anio_texto,
                                COUNT(*) as total,
                                SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as activas
                            FROM proyeccion_futuro
                            GROUP BY anio
                            ORDER BY anio ASC";
            $stmt = $this->conn->prepare($sql_por_anio);
            $stmt->execute();
            $estadisticas['proyecciones_por_anio'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Proyecciones por área
            $sql_por_area = "SELECT a.nombre_area, COUNT(p.id_proyeccion) as total_proyecciones
                            FROM areas a
                            LEFT JOIN proyeccion_futuro p ON a.id_area = p.id_area AND p.estado = 1
                            WHERE a.estado = 1
                            GROUP BY a.id_area, a.nombre_area
                            ORDER BY total_proyecciones DESC
                            LIMIT 5";
            $stmt = $this->conn->prepare($sql_por_area);
            $stmt->execute();
            $estadisticas['proyecciones_por_area'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Proyecciones recientes
            $sql_recientes = "SELECT p.id_proyeccion, p.anio, a.nombre_area, p.descripcion, p.fecha_creacion,
                             CASE 
                                WHEN p.anio = '1' THEN '1 año'
                                WHEN p.anio = '2' THEN '2 años'
                                WHEN p.anio = '3' THEN '3 años'
                                WHEN p.anio = '4' THEN '4 años'
                                WHEN p.anio = '5' THEN '5 años'
                                WHEN p.anio = '6' THEN '6 años'
                                WHEN p.anio = '7' THEN '7 años'
                                WHEN p.anio = '8' THEN '8 años'
                                WHEN p.anio = '9' THEN '9 años'
                                WHEN p.anio = '10' THEN '10 años'
                             END as anio_texto
                             FROM proyeccion_futuro p
                             INNER JOIN areas a ON p.id_area = a.id_area
                             WHERE p.estado = 1
                             ORDER BY p.fecha_creacion DESC
                             LIMIT 5";
            $stmt = $this->conn->prepare($sql_recientes);
            $stmt->execute();
            $estadisticas['proyecciones_recientes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $estadisticas;

        } catch (Exception $e) {
            return [];
        }
    }

    /* ================= BÚSQUEDA ================= */

    /**
     * Buscar proyecciones por término en descripción
     */
    public function buscar($termino) {
        try {
            $sql = "SELECT p.*, a.nombre_area,
                    CASE 
                        WHEN p.anio = '1' THEN '1 año'
                        WHEN p.anio = '2' THEN '2 años'
                        WHEN p.anio = '3' THEN '3 años'
                        WHEN p.anio = '4' THEN '4 años'
                        WHEN p.anio = '5' THEN '5 años'
                        WHEN p.anio = '6' THEN '6 años'
                        WHEN p.anio = '7' THEN '7 años'
                        WHEN p.anio = '8' THEN '8 años'
                        WHEN p.anio = '9' THEN '9 años'
                        WHEN p.anio = '10' THEN '10 años'
                    END as anio_texto
                    FROM proyeccion_futuro p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    WHERE p.descripcion LIKE ? OR a.nombre_area LIKE ?
                    ORDER BY a.nombre_area, p.anio ASC";
            $stmt = $this->conn->prepare($sql);
            $termino_busqueda = "%$termino%";
            $stmt->execute([$termino_busqueda, $termino_busqueda]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener proyecciones para select (todas las activas)
     */
    public function obtenerParaSelect() {
        try {
            $sql = "SELECT p.id_proyeccion, 
                    CONCAT(a.nombre_area, ' - ',
                        CASE 
                            WHEN p.anio = '1' THEN '1 año'
                            WHEN p.anio = '2' THEN '2 años'
                            WHEN p.anio = '3' THEN '3 años'
                            WHEN p.anio = '4' THEN '4 años'
                            WHEN p.anio = '5' THEN '5 años'
                            WHEN p.anio = '6' THEN '6 años'
                            WHEN p.anio = '7' THEN '7 años'
                            WHEN p.anio = '8' THEN '8 años'
                            WHEN p.anio = '9' THEN '9 años'
                            WHEN p.anio = '10' THEN '10 años'
                        END
                    ) as nombre_completo
                    FROM proyeccion_futuro p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    WHERE p.estado = 1 AND a.estado = 1
                    ORDER BY a.nombre_area, p.anio ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_proyeccion']] = $row['nombre_completo'];
            }
            
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener lista de años posibles
     */
    public function getListaAnios() {
        return [
            '1' => '1 año',
            '2' => '2 años',
            '3' => '3 años',
            '4' => '4 años',
            '5' => '5 años',
            '6' => '6 años',
            '7' => '7 años',
            '8' => '8 años',
            '9' => '9 años',
            '10' => '10 años'
        ];
    }
}