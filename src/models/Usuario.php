<?php

class UsuarioModel {

    private $conn;
    private $table = "usuarios";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /* ================= USUARIOS (CRUD BÁSICO) ================= */

    /**
     * Listar todos los usuarios activos
     */
    public function listar() {
        try {
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM usuarios u
                    INNER JOIN roles r ON u.id_rol = r.id_rol
                    ORDER BY u.fecha_registro DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener un usuario por ID
     */
    public function obtener($id) {
        try {
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM usuarios u
                    INNER JOIN roles r ON u.id_rol = r.id_rol
                    WHERE u.id_usuario = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Obtener usuario por correo (útil para login)
     */
    public function obtenerPorCorreo($correo) {
        try {
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM usuarios u
                    INNER JOIN roles r ON u.id_rol = r.id_rol
                    WHERE u.correo = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$correo]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Crear un nuevo usuario
     */
    public function crear($data) {
        try {
            $sql = "INSERT INTO usuarios (
                id_rol, nombre_empresa, razon_social, representante_legal,
                tipo_documento, numero_documento, correo, password_hash,
                correo_verificado, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);

            $ok = $stmt->execute([
                $data['id_rol'] ?? 2, // Por defecto, rol EMPRESA (2)
                $data['nombre_empresa'] ?? null,
                $data['razon_social'] ?? null,
                $data['representante_legal'],
                $data['tipo_documento'],
                $data['numero_documento'],
                $data['correo'],
                password_hash($data['password'], PASSWORD_DEFAULT), // Hash automático
                $data['correo_verificado'] ?? 0,
                $data['estado'] ?? 1
            ]);

            return $ok ? (int)$this->conn->lastInsertId() : false;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Actualizar usuario existente
     */
    public function actualizar($data) {
        try {
            // Construir SQL dinámico (solo actualizar campos presentes)
            $campos = [];
            $valores = [];

            $camposPermitidos = [
                'id_rol', 'nombre_empresa', 'razon_social', 'representante_legal',
                'tipo_documento', 'numero_documento', 'correo', 'estado'
            ];

            foreach ($camposPermitidos as $campo) {
                if (array_key_exists($campo, $data)) {
                    $campos[] = "$campo = ?";
                    $valores[] = $data[$campo];
                }
            }

            // Si se envía nueva contraseña
            if (!empty($data['password'])) {
                $campos[] = "password_hash = ?";
                $valores[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            // Si no hay campos para actualizar
            if (empty($campos)) {
                return false;
            }

            // Agregar ID al final
            $valores[] = $data['id_usuario'];

            $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);
            
            return $stmt->execute($valores);

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Cambiar estado del usuario (activar/desactivar)
     */
    public function cambiarEstado($id, $estado) {
        try {
            $sql = "UPDATE usuarios SET estado = ? WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    /* ================= VERIFICACIÓN DE CORREO ================= */

    /**
     * Verificar correo electrónico
     */
    public function verificarCorreo($id) {
        try {
            $sql = "UPDATE usuarios SET correo_verificado = 1 WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    /* ================= ROLES ================= */

    /**
     * Obtener lista de roles disponibles
     */
    public function obtenerRoles() {
        try {
            $sql = "SELECT id_rol, nombre FROM roles ORDER BY nombre";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtener usuarios por rol
     */
    public function obtenerPorRol($id_rol) {
        try {
            $sql = "SELECT id_usuario, nombre_empresa, representante_legal, correo
                    FROM usuarios
                    WHERE id_rol = ? AND estado = 1
                    ORDER BY nombre_empresa";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_rol]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /* ================= TOKENS DE CORREO ================= */

    /**
     * Crear token para verificación o recuperación
     */
    public function crearToken($id_usuario, $tipo, $dias_validez = 1) {
        try {
            // Generar token aleatorio
            $token = bin2hex(random_bytes(32));
            $fecha_expiracion = date('Y-m-d H:i:s', strtotime("+$dias_validez days"));

            $sql = "INSERT INTO tokens_correo (id_usuario, token, tipo, fecha_expiracion)
                    VALUES (?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $ok = $stmt->execute([$id_usuario, $token, $tipo, $fecha_expiracion]);

            return $ok ? $token : false;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Validar token
     */
    public function validarToken($token, $tipo) {
        try {
            $sql = "SELECT tc.*, u.correo, u.id_usuario
                    FROM tokens_correo tc
                    INNER JOIN usuarios u ON tc.id_usuario = u.id_usuario
                    WHERE tc.token = ? AND tc.tipo = ? 
                    AND tc.usado = 0 AND tc.fecha_expiracion > NOW()";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$token, $tipo]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Marcar token como usado
     */
    public function marcarTokenUsado($id_token) {
        try {
            $sql = "UPDATE tokens_correo SET usado = 1 WHERE id_token = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id_token]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Cambiar contraseña con token válido
     */
    public function cambiarPasswordConToken($id_usuario, $nueva_password) {
        try {
            $sql = "UPDATE usuarios SET password_hash = ? WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([password_hash($nueva_password, PASSWORD_DEFAULT), $id_usuario]);
        } catch (Exception $e) {
            return false;
        }
    }

    /* ================= MÉTODOS DE AUTENTICACIÓN ================= */

    /**
     * Login de usuario
     */
    public function login($correo, $password) {
        try {
            $usuario = $this->obtenerPorCorreo($correo);
            
            if (!$usuario) {
                return ['success' => false, 'error' => 'Usuario no encontrado'];
            }

            if ($usuario['estado'] != 1) {
                return ['success' => false, 'error' => 'Usuario inactivo'];
            }

            if (password_verify($password, $usuario['password_hash'])) {
                unset($usuario['password_hash']); // No enviar hash al frontend
                return ['success' => true, 'usuario' => $usuario];
            }

            return ['success' => false, 'error' => 'Contraseña incorrecta'];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error en el servidor'];
        }
    }

    /**
     * Verificar si el correo ya existe
     */
    public function correoExiste($correo, $excluir_id = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE correo = ?";
            $params = [$correo];

            if ($excluir_id) {
                $sql .= " AND id_usuario != ?";
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
     * Obtener estadísticas de usuarios
     */
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_usuarios,
                        SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as usuarios_activos,
                        SUM(CASE WHEN correo_verificado = 1 THEN 1 ELSE 0 END) as correos_verificados,
                        (SELECT COUNT(*) FROM usuarios WHERE id_rol = 1) as total_administradores,
                        (SELECT COUNT(*) FROM usuarios WHERE id_rol = 2) as total_empresas
                    FROM usuarios";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return [];
        }
    }
}