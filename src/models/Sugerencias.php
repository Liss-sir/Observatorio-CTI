<?php

class SugerenciasModel {

    private $conn;
    private $table = "sugerencias_blog";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // List all active suggestions
    public function listar() {
        try {
            $sql = "SELECT s.*, u.nombre_empresa, u.representante_legal, u.correo
                    FROM sugerencias_blog s
                    INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
                    WHERE s.estado = 1
                    ORDER BY s.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // List all suggestions (For admin)
    public function listarTodas() {
        try {
            $sql = "SELECT s.*, u.nombre_empresa, u.representante_legal, u.correo
                    FROM sugerencias_blog s
                    INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
                    ORDER BY s.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get suggestion by ID
    public function obtener($id) {
        try {
            $sql = "SELECT s.*, u.nombre_empresa, u.representante_legal, u.correo, u.tipo_documento, u.numero_documento
                    FROM sugerencias_blog s
                    INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
                    WHERE s.id_sugerencia = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Get suggestions by user
    public function obtenerPorUsuario($id_usuario) {
        try {
            $sql = "SELECT s.*, u.nombre_empresa, u.representante_legal
                    FROM sugerencias_blog s
                    INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
                    WHERE s.id_usuario = ? AND s.estado = 1
                    ORDER BY s.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_usuario]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Create new suggestion
    public function crear($data) {
        try {
            $sql = "INSERT INTO sugerencias_blog (
                id_usuario, titulo, contenido, estado
            ) VALUES (?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            
            $ok = $stmt->execute([
                $data['id_usuario'],
                trim($data['titulo']),
                trim($data['contenido']),
                $data['estado'] ?? 1
            ]);

            return $ok ? (int)$this->conn->lastInsertId() : false;

        } catch (Exception $e) {
            return false;
        }
    }

    // Update existing suggestion
    public function actualizar($data) {
        try {
            $campos = [];
            $valores = [];

            $camposPermitidos = [
                'titulo', 'contenido', 'estado'
            ];

            foreach ($camposPermitidos as $campo) {
                if (array_key_exists($campo, $data)) {
                    $campos[] = "$campo = ?";
                    $valores[] = $campo === 'titulo' || $campo === 'contenido' ? trim($data[$campo]) : $data[$campo];
                }
            }

            // If there are no fields to update
            if (empty($campos)) {
                return false;
            }

            // Add ID at the end
            $valores[] = $data['id_sugerencia'];

            $sql = "UPDATE sugerencias_blog SET " . implode(", ", $campos) . " WHERE id_sugerencia = ?";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute($valores);

        } catch (Exception $e) {
            return false;
        }
    }

    // Change state in suggestions
    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE sugerencias_blog SET estado = ? WHERE id_sugerencia = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete suggestion
    public function eliminar($id) {
        try {
            // Physical delete (no dependencies verification needed as per structure)
            $sql = "DELETE FROM sugerencias_blog WHERE id_sugerencia = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$id]);
            
            return ['success' => $result];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al eliminar la sugerencia'];
        }
    }

    // Get recent suggestions
    public function obtenerRecientes($limite = 10) {
        try {
            $sql = "SELECT s.*, u.nombre_empresa, u.representante_legal
                    FROM sugerencias_blog s
                    INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
                    WHERE s.estado = 1
                    ORDER BY s.fecha_creacion DESC
                    LIMIT ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$limite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Search suggestions by term
    public function buscar($termino) {
        try {
            $sql = "SELECT s.*, u.nombre_empresa, u.representante_legal, u.correo
                    FROM sugerencias_blog s
                    INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
                    WHERE (s.titulo LIKE ? OR s.contenido LIKE ? OR u.nombre_empresa LIKE ? OR u.representante_legal LIKE ?)
                    AND s.estado = 1
                    ORDER BY s.fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $termino_busqueda = "%$termino%";
            $stmt->execute([$termino_busqueda, $termino_busqueda, $termino_busqueda, $termino_busqueda]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Get statistics for suggestions
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_sugerencias,
                        SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as sugerencias_activas,
                        SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as sugerencias_inactivas,
                        COUNT(DISTINCT id_usuario) as usuarios_que_sugieren
                    FROM sugerencias_blog";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Suggestions per user
            $sql_por_usuario = "SELECT u.nombre_empresa, u.representante_legal, COUNT(s.id_sugerencia) as total_sugerencias
                               FROM usuarios u
                               LEFT JOIN sugerencias_blog s ON u.id_usuario = s.id_usuario AND s.estado = 1
                               WHERE u.estado = 1
                               GROUP BY u.id_usuario, u.nombre_empresa, u.representante_legal
                               HAVING total_sugerencias > 0
                               ORDER BY total_sugerencias DESC
                               LIMIT 5";
            $stmt = $this->conn->prepare($sql_por_usuario);
            $stmt->execute();
            $estadisticas['sugerencias_por_usuario'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Suggestions per month (last 6 months)
            $sql_por_mes = "SELECT DATE_FORMAT(fecha_creacion, '%Y-%m') as mes,
                                   COUNT(*) as total,
                                   SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as activas
                            FROM sugerencias_blog
                            WHERE fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                            GROUP BY DATE_FORMAT(fecha_creacion, '%Y-%m')
                            ORDER BY mes DESC";
            $stmt = $this->conn->prepare($sql_por_mes);
            $stmt->execute();
            $estadisticas['sugerencias_por_mes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Last suggestions
            $sql_ultimas = "SELECT s.titulo, u.nombre_empresa, s.fecha_creacion
                           FROM sugerencias_blog s
                           INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
                           WHERE s.estado = 1
                           ORDER BY s.fecha_creacion DESC
                           LIMIT 5";
            $stmt = $this->conn->prepare($sql_ultimas);
            $stmt->execute();
            $estadisticas['ultimas_sugerencias'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $estadisticas;

        } catch (Exception $e) {
            return [];
        }
    }

    // Get suggestions for select (admin purposes)
    public function obtenerParaSelect() {
        try {
            $sql = "SELECT s.id_sugerencia, 
                           CONCAT(u.nombre_empresa, ' - ', LEFT(s.titulo, 50)) as nombre_completo
                    FROM sugerencias_blog s
                    INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
                    WHERE s.estado = 1
                    ORDER BY s.fecha_creacion DESC
                    LIMIT 100";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[$row['id_sugerencia']] = $row['nombre_completo'];
            }
            
            return $resultados;
        } catch (Exception $e) {
            return [];
        }
    }

    // Verify if user exists and is active
    public function usuarioExisteYActivo($id_usuario) {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE id_usuario = ? AND estado = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_usuario]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    // Validate suggestion data
    public function validarDatos($titulo, $contenido) {
        $errores = [];
        
        if (strlen(trim($titulo)) < 5) {
            $errores[] = 'El título debe tener al menos 5 caracteres';
        }
        
        if (strlen(trim($titulo)) > 150) {
            $errores[] = 'El título no puede tener más de 150 caracteres';
        }
        
        if (strlen(trim($contenido)) < 10) {
            $errores[] = 'El contenido debe tener al menos 10 caracteres';
        }
        
        return $errores;
    }
}