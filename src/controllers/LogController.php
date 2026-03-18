<?php

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Login.php";

class LogController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new LoginModel($conn);
    }

    /**
     * POST /login
     * Espera JSON con correo y password
     */
    public function login() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['correo']) || !isset($input['password'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Correo y contraseña requeridos'
            ]);
            return;
        }

        // Llamar al modelo
        $resultado = $this->model->login($input['correo'], $input['password']);
        
        // Si el login fue exitoso, iniciar sesión PHP
        if ($resultado['success'] && isset($resultado['usuario'])) {
            $_SESSION['usuario'] = $resultado['usuario'];
            $_SESSION['id_usuario'] = $resultado['usuario']['id_usuario'];
            $_SESSION['correo'] = $resultado['usuario']['correo'];
            $_SESSION['rol_nombre'] = $resultado['usuario']['rol_nombre'];
            $_SESSION['autenticado'] = true;
            
            // Agregar URL de redirección
            $resultado['redirect'] = '../../view/dashboard/dashboard.php';
        }
        
        echo json_encode($resultado);
    }

    


    /**
     * POST /enviar-verificacion
     * Espera JSON con correo
     */
    public function enviarVerificacion() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['correo'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Correo requerido'
            ]);
            return;
        }

        $usuario = $this->model->obtenerUsuarioPorCorreo($input['correo']);
        if (!$usuario) {
            echo json_encode([
                'success' => false,
                'error' => 'El correo no está registrado'
            ]);
            return;
        }

        if ($usuario['correo_verificado'] == 1) {
            echo json_encode([
                'success' => false,
                'error' => 'La cuenta ya está verificada'
            ]);
            return;
        }

        $enviado = $this->model->enviarVerificacion($usuario['id_usuario'], $usuario['correo']);
        echo json_encode([
            'success' => $enviado,
            'message' => $enviado ? 'Correo de verificación enviado' : 'Error al enviar el correo'
        ]);
    }

    /**
     * Procesa la verificación mediante token
     */
    public function verificarCuenta() {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            echo json_encode([
                'success' => false,
                'error' => 'Token requerido'
            ]);
            return;
        }

        $resultado = $this->model->procesarVerificacion($token);
        echo json_encode($resultado);
    }

    /**
     * POST /recuperar
     * Solicita recuperación de contraseña
     */
    public function solicitarRecuperacion() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['correo'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Correo requerido'
            ]);
            return;
        }

        $usuario = $this->model->obtenerUsuarioPorCorreo($input['correo']);
        if (!$usuario) {
            // Por seguridad, no revelar si el correo existe
            echo json_encode([
                'success' => true,
                'message' => 'Si el correo está registrado, recibirás un enlace de recuperación'
            ]);
            return;
        }

        $enviado = $this->model->enviarRecuperacion($input['correo']);
        echo json_encode([
            'success' => $enviado,
            'message' => $enviado ? 'Correo de recuperación enviado' : 'Error al enviar el correo'
        ]);
    }

    /**
     * POST /restablecer
     * Restablece la contraseña usando un token
     */
    public function restablecerPassword() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['token']) || !isset($input['nueva_password'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Token y nueva contraseña requeridos'
            ]);
            return;
        }

        if (strlen($input['nueva_password']) < 6) {
            echo json_encode([
                'success' => false,
                'error' => 'La contraseña debe tener al menos 6 caracteres'
            ]);
            return;
        }

        $resultado = $this->model->procesarRestablecimiento($input['token'], $input['nueva_password']);
        echo json_encode($resultado);
    }

    /**
     * GET /estado-correo?correo=...
     */
    public function estadoCorreo() {
        $correo = $_GET['correo'] ?? '';
        if (empty($correo)) {
            echo json_encode(['error' => 'Correo requerido']);
            return;
        }

        $usuario = $this->model->obtenerUsuarioPorCorreo($correo);
        if (!$usuario) {
            echo json_encode(['existe' => false, 'mensaje' => "El correo $correo no se encuentra registrado"]);
            return;
        }

        echo json_encode([
            'existe' => true,
            'correo_verificado' => $usuario['correo_verificado'],
            'estado' => $usuario['estado']
        ]);
    }
}

// ================= ROUTER =================

$accion = $_GET['accion'] ?? null;

if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new LogController($conn);

switch ($accion) {
    case 'login':
        $controller->login();
        break;

    case 'enviar-verificacion':
        $controller->enviarVerificacion();
        break;

    case 'verificar-cuenta':
        $controller->verificarCuenta();
        break;

    case 'recuperar':
        $controller->solicitarRecuperacion();
        break;

    case 'restablecer':
        $controller->restablecerPassword();
        break;

    case 'estado-correo':
        $controller->estadoCorreo();
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}