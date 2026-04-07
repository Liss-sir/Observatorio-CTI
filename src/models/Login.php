<?php
require __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LoginModel {

    private $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /**
     * Iniciar sesión con correo y contraseña
     * Soporta contraseñas hasheadas y en texto plano (con rehasheo automático)
     * @param string $correo
     * @param string $password
     * @return array ['success' => bool, 'usuario' => array|null, 'error' => string|null]
     */
    public function login($correo, $password) {
        try {
            // Obtener usuario por correo
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM usuarios u
                    INNER JOIN roles r ON u.id_rol = r.id_rol
                    WHERE u.correo = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return ['success' => false, 'error' => 'Usuario no encontrado'];
            }

            // Verificar estado activo
            if ($usuario['estado'] != 1) {
                return ['success' => false, 'error' => 'Usuario inactivo'];
            }

            // Verificar correo verificado
            if ($usuario['correo_verificado'] != 1) {
                return ['success' => false, 'error' => 'Correo electrónico no verificado'];
            }

            // --- Verificación de contraseña (hash + texto plano legacy) ---
            $passwordValid = false;

            // 1. Intentar con password_verify (para hashes)
            if (password_verify($password, $usuario['password_hash'])) {
                $passwordValid = true;
            }
            // 2. Si falla, comparar en texto plano (soporte para contraseñas legacy)
            elseif ($password === $usuario['password_hash']) {
                $passwordValid = true;
                // Rehashear la contraseña y actualizar en la BD para migrar a hash
                $this->cambiarPassword($usuario['id_usuario'], $password);
            }

            if (!$passwordValid) {
                return ['success' => false, 'error' => 'Contraseña incorrecta'];
            }

            // Contraseña válida: devolver datos sin el hash
            unset($usuario['password_hash']);
            return ['success' => true, 'usuario' => $usuario];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error en el servidor'];
        }
    }

    /**
     * Crear un token para verificación de correo
     * @param int $id_usuario
     * @param string $tipo 'VERIFICACION' o 'RECUPERACION'
     * @param int $dias_validez
     * @return string|false Token generado o false si falla
     */
    public function crearToken($id_usuario, $tipo, $dias_validez = 1) {
        try {
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
     * Validar un token no usado y no expirado
     * @param string $token
     * @param string $tipo
     * @return array|null Datos del token + usuario o null
     */
    public function validarToken($token, $tipo) {
        try {
            $sql = "SELECT tc.*, u.correo, u.id_usuario, u.correo_verificado
                    FROM tokens_correo tc
                    INNER JOIN usuarios u ON tc.id_usuario = u.id_usuario
                    WHERE tc.token = ? AND tc.tipo = ? 
                    AND tc.usado = 0 AND tc.fecha_expiracion > NOW()";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$token, $tipo]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Marcar token como usado
     * @param int $id_token
     * @return bool
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
     * Activar cuenta de usuario (correo_verificado = 1)
     * @param int $id_usuario
     * @return bool
     */
    public function activarCuenta($id_usuario) {
        try {
            $sql = "UPDATE usuarios SET correo_verificado = 1 WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id_usuario]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Cambiar contraseña de un usuario (genera hash automáticamente)
     * @param int $id_usuario
     * @param string $nueva_password
     * @return bool
     */
    public function cambiarPassword($id_usuario, $nueva_password) {
        try {
            $hash = password_hash($nueva_password, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET password_hash = ? WHERE id_usuario = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$hash, $id_usuario]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Obtener un usuario por su correo
     * @param string $correo
     * @return array|null
     */
    public function obtenerUsuarioPorCorreo($correo) {
        try {
            $sql = "SELECT id_usuario, nombre_empresa, representante_legal, correo, correo_verificado, estado 
                    FROM usuarios WHERE correo = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$correo]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Enviar correo electrónico (simulado)
     * @param string $destinatario
     * @param string $asunto
     * @param string $cuerpo
     * @return bool
     */

    private function enviarCorreo($destinatario, $asunto, $cuerpo) {
        $mail = new PHPMailer(true);

        try {
            // CONFIG SMTP (GMAIL EJEMPLO)
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'observatorio.perfiles@gmail.com';
            $mail->Password = 'tlrh rwlt tcff banv'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->setFrom('observatorio.perfiles@gmail.com', 'Observatorio CTI');
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $cuerpo;

            return $mail->send();

        } catch (Exception $e) {
            error_log("Error PHPMailer: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar correo de verificación de cuenta
     * @param int $id_usuario
     * @param string $correo_destino
     * @return bool
     */
    public function enviarVerificacion($id_usuario, $correo_destino) {
        $token = $this->crearToken($id_usuario, 'VERIFICACION', 1);
        if (!$token) {
            return false;
        }

        $enlace = "http://localhost/observatorio/Observatorio-CTI/src/controllers/LogController.php?accion=verificar-cuenta&token=" . urlencode($token);
        $asunto = "Verifica tu cuenta en Observatorio CTI";
        $cuerpo = "<h1>Bienvenido</h1>
                   <p>Haz clic en el siguiente enlace para verificar tu cuenta:</p>
                   <a href='$enlace'>$enlace</a>
                   <p>Este enlace expirará en 24 horas.</p>";

        return $this->enviarCorreo($correo_destino, $asunto, $cuerpo);
    }

    /**
     * Enviar correo de recuperación de contraseña
     * @param string $correo
     * @return bool
     */
    public function enviarRecuperacion($correo) {
        $usuario = $this->obtenerUsuarioPorCorreo($correo);
        if (!$usuario) {
            return false; // Usuario no existe (podrías devolver true por seguridad)
        }

        $token = $this->crearToken($usuario['id_usuario'], 'RECUPERACION', 1);
        if (!$token) {
            return false;
        }

        $enlace = "http://localhost/observatorio/Observatorio-CTI/src/controllers/LogController.php?accion=recuperar&token=" . urlencode($token);
        $asunto = "Recuperación de contraseña - Observatorio CTI";
        $cuerpo = "<h1>Recupera tu contraseña</h1>
                   <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
                   <a href='$enlace'>$enlace</a>
                   <p>Si no solicitaste este cambio, ignora este mensaje. El enlace expirará en 24 horas.</p>";

        return $this->enviarCorreo($correo, $asunto, $cuerpo);
    }

    /**
     * Procesar verificación de cuenta mediante token
     * @param string $token
     * @return array ['success' => bool, 'message' => string]
     */
    public function procesarVerificacion($token) {
        $data = $this->validarToken($token, 'VERIFICACION');
        if (!$data) {
            return ['success' => false, 'message' => 'Token inválido o expirado'];
        }

        // Activar cuenta
        if ($this->activarCuenta($data['id_usuario'])) {
            $this->marcarTokenUsado($data['id_token']);
            return ['success' => true, 'message' => 'Cuenta verificada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al activar la cuenta'];
        }
    }

    /**
     * Procesar restablecimiento de contraseña mediante token
     * @param string $token
     * @param string $nueva_password
     * @return array ['success' => bool, 'message' => string]
     */
    public function procesarRestablecimiento($token, $nueva_password) {
        $data = $this->validarToken($token, 'RECUPERACION');
        if (!$data) {
            return ['success' => false, 'message' => 'Token inválido o expirado'];
        }

        if ($this->cambiarPassword($data['id_usuario'], $nueva_password)) {
            $this->marcarTokenUsado($data['id_token']);
            return ['success' => true, 'message' => 'Contraseña actualizada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar la contraseña'];
        }
    }

    /**
     * Registrar un nuevo usuario (empresa)
     * @param array $data Campos: nombre_empresa, razon_social, representante_legal, tipo_documento, numero_documento, correo, password
     * @return array ['success' => bool, 'error' => string|null, 'id_usuario' => int|null]
     */
    public function registrar($data) {
        try {
            // Verificar si el correo ya existe
            $existe = $this->obtenerUsuarioPorCorreo($data['correo']);
            if ($existe) {
                return ['success' => false, 'error' => 'El correo ya está registrado'];
            }

            // Hash de la contraseña
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);

            // Insertar usuario
            $sql = "INSERT INTO usuarios 
                    (id_rol, nombre_empresa, razon_social, representante_legal, tipo_documento, numero_documento, correo, password_hash, correo_verificado, estado, es_sistema, fecha_registro)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 1, 0, NOW())";
            $stmt = $this->conn->prepare($sql);
            $params = [
                2, // id_rol empresa
                $data['nombre_empresa'] ?? null,
                $data['razon_social'] ?? null,
                $data['representante_legal'],
                $data['tipo_documento'],
                $data['numero_documento'],
                $data['correo'],
                $hash
            ];
            $ok = $stmt->execute($params);

            if (!$ok) {
                return ['success' => false, 'error' => 'Error al registrar el usuario'];
            }

            $id_usuario = $this->conn->lastInsertId();
            $envio = $this->enviarVerificacion($id_usuario, $data['correo']);

            if (!$envio) {
                error_log("No se pudo enviar el correo de verificación a: " . $data['correo']);
            }
            return ['success' => true, 'id_usuario' => $id_usuario];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error en el servidor'];
        }
    }
}