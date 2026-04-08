<?php

class PerfilOcupacionalModel {

    private $conn;
    private $table = "perfiles_ocupacionales";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // List profiles active
    public function listar() {
        try {
            $sql = "SELECT p.*, 
                           u.nombre_empresa, u.representante_legal,
                           l.nombre_linea,
                           pr.nombre_programa, pr.codigo_programa,
                           n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    INNER JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    INNER JOIN programas_formacion pr ON p.id_programa = pr.id_programa
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.estado = 1
                    ORDER BY p.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // List all profiles (For admin)
    public function listarTodas() {
        try {
            $sql = "SELECT p.*, 
                           u.nombre_empresa, u.representante_legal,
                           l.nombre_linea,
                           pr.nombre_programa, pr.codigo_programa,
                           n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    INNER JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    INNER JOIN programas_formacion pr ON p.id_programa = pr.id_programa
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    ORDER BY p.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get profile for ID
    public function obtener($id) {
        try {
            $sql = "SELECT p.*, 
                           u.nombre_empresa, u.representante_legal, u.correo,
                           l.nombre_linea,
                           pr.nombre_programa, pr.codigo_programa,
                           n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    INNER JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    INNER JOIN programas_formacion pr ON p.id_programa = pr.id_programa
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.id_perfil = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Get profiles for user ID
    public function obtenerPorUsuario($id_usuario) {
        try {
            $sql = "SELECT p.*, 
                           l.nombre_linea,
                           pr.nombre_programa, pr.codigo_programa,
                           n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    INNER JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    INNER JOIN programas_formacion pr ON p.id_programa = pr.id_programa
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.id_usuario = ? AND p.estado = 1
                    ORDER BY p.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_usuario]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Create new profile
    public function crear($data) {
        try {
            $sql = "INSERT INTO perfiles_ocupacionales (
                id_usuario, id_linea, id_programa, id_nivel, 
                cupos, nombre, descripcion, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            
            $ok = $stmt->execute([
                $data['id_usuario'],
                $data['id_linea'],
                $data['id_programa'],
                $data['id_nivel'],
                $data['cupos'],
                trim($data['nombre']),
                $data['descripcion'] ?? '',
                $data['estado'] ?? 1
            ]);

            return $ok ? (int)$this->conn->lastInsertId() : false;

        } catch (Exception $e) {
            return false;
        }
    }

    // Update profile exist
    public function actualizar($data) {
        try {
            $campos = [];
            $valores = [];

            $camposPermitidos = [
                'id_linea', 'id_programa', 'id_nivel', 
                'cupos', 'nombre', 'descripcion', 'estado'
            ];

            foreach ($camposPermitidos as $campo) {
                if (array_key_exists($campo, $data)) {
                    $campos[] = "$campo = ?";
                    $valores[] = $campo === 'nombre' ? trim($data[$campo]) : $data[$campo];
                }
            }

            // If there profile no field no update
            if (empty($campos)) {
                return false;
            }

            // Add ID at the end
            $valores[] = $data['id_perfil'];

            $sql = "UPDATE perfiles_ocupacionales SET " . implode(", ", $campos) . " WHERE id_perfil = ?";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute($valores);

        } catch (Exception $e) {
            return false;
        }
    }

    // Change state in profiles
    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE perfiles_ocupacionales SET estado = ? WHERE id_perfil = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete profiles
    public function eliminar($id) {
        try {
            $sql = "DELETE FROM perfiles_ocupacionales WHERE id_perfil = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$id]);
            
            return ['success' => $result];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al eliminar el perfil'];
        }
    }

    // Get profiles for technological line
    public function obtenerPorLinea($id_linea) {
        try {
            $sql = "SELECT p.*, 
                           u.nombre_empresa, u.representante_legal,
                           pr.nombre_programa,
                           n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    INNER JOIN programas_formacion pr ON p.id_programa = pr.id_programa
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.id_linea = ? AND p.estado = 1
                    ORDER BY p.cupos DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_linea]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get profiles for formation program
    public function obtenerPorPrograma($id_programa) {
        try {
            $sql = "SELECT p.*, 
                           u.nombre_empresa,
                           l.nombre_linea,
                           n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    INNER JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.id_programa = ? AND p.estado = 1
                    ORDER BY p.cupos DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_programa]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get profiles for formation level
    public function obtenerPorNivel($id_nivel) {
        try {
            $sql = "SELECT p.*, 
                           u.nombre_empresa,
                           l.nombre_linea,
                           pr.nombre_programa
                    FROM perfiles_ocupacionales p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    INNER JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    INNER JOIN programas_formacion pr ON p.id_programa = pr.id_programa
                    WHERE p.id_nivel = ? AND p.estado = 1
                    ORDER BY p.cupos DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_nivel]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Search profiles for term
    public function buscar($termino) {
        try {
            $sql = "SELECT p.*, 
                           u.nombre_empresa, u.representante_legal,
                           l.nombre_linea,
                           pr.nombre_programa,
                           n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    INNER JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    INNER JOIN programas_formacion pr ON p.id_programa = pr.id_programa
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE (p.nombre LIKE ? OR p.descripcion LIKE ? OR u.nombre_empresa LIKE ?)
                    AND p.estado = 1
                    ORDER BY p.fecha_creacion DESC";
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
            $sql = "SELECT p.*, 
                           u.nombre_empresa, u.representante_legal,
                           l.nombre_linea,
                           pr.nombre_programa,
                           n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    INNER JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    INNER JOIN programas_formacion pr ON p.id_programa = pr.id_programa
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE 1=1";
            $params = [];

            if (!empty($filtros['id_usuario'])) {
                $sql .= " AND p.id_usuario = ?";
                $params[] = $filtros['id_usuario'];
            }

            if (!empty($filtros['id_linea'])) {
                $sql .= " AND p.id_linea = ?";
                $params[] = $filtros['id_linea'];
            }

            if (!empty($filtros['id_programa'])) {
                $sql .= " AND p.id_programa = ?";
                $params[] = $filtros['id_programa'];
            }

            if (!empty($filtros['id_nivel'])) {
                $sql .= " AND p.id_nivel = ?";
                $params[] = $filtros['id_nivel'];
            }

            if (!empty($filtros['estado'])) {
                $sql .= " AND p.estado = ?";
                $params[] = $filtros['estado'];
            }

            if (!empty($filtros['cupos_min'])) {
                $sql .= " AND p.cupos >= ?";
                $params[] = $filtros['cupos_min'];
            }

            if (!empty($filtros['fecha_desde'])) {
                $sql .= " AND DATE(p.fecha_creacion) >= ?";
                $params[] = $filtros['fecha_desde'];
            }

            if (!empty($filtros['fecha_hasta'])) {
                $sql .= " AND DATE(p.fecha_creacion) <= ?";
                $params[] = $filtros['fecha_hasta'];
            }

            $sql .= " ORDER BY p.fecha_creacion DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Verify if profile name exists for user
    public function nombreExisteParaUsuario($nombre, $id_usuario, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM perfiles_ocupacionales 
                    WHERE nombre = ? AND id_usuario = ?";
            $params = [trim($nombre), $id_usuario];

            if ($excluir_id) {
                $sql .= " AND id_perfil != ?";
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

    // Verify if line and program are compatible
    public function verificarLineaPrograma($id_linea, $id_programa) {
        try {
            $sql = "SELECT COUNT(*) as total FROM lineas_tecnologicas 
                    WHERE id_linea = ? AND id_programa = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_linea, $id_programa]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'] > 0;

        } catch (Exception $e) {
            return false;
        }
    }

    // List all active technological lines
    public function listarLineasTecnologicas() {
        try {
            $sql = "SELECT l.id_linea, l.nombre_linea, l.id_area, l.id_programa, 
                           l.id_etapa, l.id_tendencia, l.id_proyeccion, l.estado,
                           a.nombre_area
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    WHERE l.estado = 1
                    ORDER BY l.nombre_linea ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // List programs filtered by technological line
    public function listarProgramasPorLinea($id_linea) {
        try {
            $sql = "SELECT p.id_programa, p.nombre_programa, p.codigo_programa, 
                           p.id_nivel, p.modalidad, p.cupos_formacion,
                           n.nombre_nivel
                    FROM programas_formacion p
                    INNER JOIN lineas_tecnologicas l ON p.id_programa = l.id_programa
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE l.id_linea = ? AND l.estado = 1 AND p.estado = 1
                    ORDER BY p.nombre_programa ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_linea]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // List all active training levels
    public function listarNivelesFormacion() {
        try {
            $sql = "SELECT id_nivel, nombre_nivel, estado
                    FROM niveles_formacion
                    WHERE estado = 1
                    ORDER BY id_nivel ASC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get the details of a specific technological line
    public function obtenerLineaTecnologica($id_linea) {
        try {
            $sql = "SELECT l.*, a.nombre_area
                    FROM lineas_tecnologicas l
                    INNER JOIN areas a ON l.id_area = a.id_area
                    WHERE l.id_linea = ? AND l.estado = 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_linea]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Get the details of a specific training program
    public function obtenerProgramaFormacion($id_programa) {
        try {
            $sql = "SELECT p.*, n.nombre_nivel
                    FROM programas_formacion p
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.id_programa = ? AND p.estado = 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_programa]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Get the details of a specific training level
    public function obtenerNivelFormacion($id_nivel) {
        try {
            $sql = "SELECT id_nivel, nombre_nivel, estado
                    FROM niveles_formacion
                    WHERE id_nivel = ? AND estado = 1";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_nivel]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Get statistics for profiles
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_perfiles,
                        SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as perfiles_activos,
                        SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as perfiles_inactivos,
                        SUM(cupos) as total_cupos,
                        AVG(cupos) as promedio_cupos,
                        COUNT(DISTINCT id_usuario) as empresas_con_perfiles,
                        COUNT(DISTINCT id_linea) as lineas_utilizadas,
                        COUNT(DISTINCT id_programa) as programas_utilizados,
                        COUNT(DISTINCT id_nivel) as niveles_utilizados
                    FROM perfiles_ocupacionales";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Profiles by company
            $sql_por_empresa = "SELECT u.nombre_empresa, COUNT(p.id_perfil) as total_perfiles,
                                       SUM(p.cupos) as total_cupos
                                FROM usuarios u
                                INNER JOIN perfiles_ocupacionales p ON u.id_usuario = p.id_usuario
                                WHERE p.estado = 1
                                GROUP BY u.id_usuario, u.nombre_empresa
                                ORDER BY total_perfiles DESC
                                LIMIT 5";
            $stmt = $this->conn->prepare($sql_por_empresa);
            $stmt->execute();
            $estadisticas['perfiles_por_empresa'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Profiles by level
            $sql_por_nivel = "SELECT n.nombre_nivel, COUNT(p.id_perfil) as total_perfiles,
                                     SUM(p.cupos) as total_cupos
                              FROM niveles_formacion n
                              LEFT JOIN perfiles_ocupacionales p ON n.id_nivel = p.id_nivel AND p.estado = 1
                              WHERE n.estado = 1
                              GROUP BY n.id_nivel, n.nombre_nivel
                              ORDER BY total_perfiles DESC";
            $stmt = $this->conn->prepare($sql_por_nivel);
            $stmt->execute();
            $estadisticas['perfiles_por_nivel'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Profiles by line
            $sql_por_linea = "SELECT l.nombre_linea, COUNT(p.id_perfil) as total_perfiles
                             FROM lineas_tecnologicas l
                             LEFT JOIN perfiles_ocupacionales p ON l.id_linea = p.id_linea AND p.estado = 1
                             WHERE l.estado = 1
                             GROUP BY l.id_linea, l.nombre_linea
                             ORDER BY total_perfiles DESC
                             LIMIT 5";
            $stmt = $this->conn->prepare($sql_por_linea);
            $stmt->execute();
            $estadisticas['perfiles_por_linea'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Recent profiles
            $sql_recientes = "SELECT p.nombre, u.nombre_empresa, p.cupos, p.fecha_creacion
                             FROM perfiles_ocupacionales p
                             INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                             WHERE p.estado = 1
                             ORDER BY p.fecha_creacion DESC
                             LIMIT 5";
            $stmt = $this->conn->prepare($sql_recientes);
            $stmt->execute();
            $estadisticas['perfiles_recientes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Profiles expiring soon (by event)
            $sql_proximos_deshabilitar = "SELECT COUNT(*) as total
                                         FROM perfiles_ocupacionales
                                         WHERE estado = 1
                                         AND fecha_creacion <= DATE_SUB(NOW(), INTERVAL 11 MONTH)";
            $stmt = $this->conn->prepare($sql_proximos_deshabilitar);
            $stmt->execute();
            $estadisticas['proximos_deshabilitar'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $estadisticas;

        } catch (Exception $e) {
            return [];
        }
    }

    // Get profiles for select (by user)
    public function obtenerParaSelectPorUsuario($id_usuario) {
        try {
            $sql = "SELECT id_perfil, nombre 
                    FROM perfiles_ocupacionales 
                    WHERE id_usuario = ? AND estado = 1
                    ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_usuario]);
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_perfil']] = $row['nombre'];
            }
            
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    // Get profiles with available spots
    public function obtenerConCuposDisponibles() {
        try {
            $sql = "SELECT p.*, 
                           u.nombre_empresa, u.representante_legal,
                           l.nombre_linea,
                           pr.nombre_programa,
                           n.nombre_nivel
                    FROM perfiles_ocupacionales p
                    INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
                    INNER JOIN lineas_tecnologicas l ON p.id_linea = l.id_linea
                    INNER JOIN programas_formacion pr ON p.id_programa = pr.id_programa
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    WHERE p.estado = 1 AND p.cupos > 0
                    ORDER BY p.cupos DESC, p.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Update spots
    public function actualizarCupos($id_perfil, $nuevos_cupos) {
        try {
            $sql = "UPDATE perfiles_ocupacionales SET cupos = ? WHERE id_perfil = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$nuevos_cupos, $id_perfil]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Decrease spots by one
    public function disminuirCupo($id_perfil) {
        try {
            $sql = "UPDATE perfiles_ocupacionales SET cupos = cupos - 1 
                    WHERE id_perfil = ? AND cupos > 0";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id_perfil]);
        } catch (Exception $e) {
            return false;
        }
    }
}