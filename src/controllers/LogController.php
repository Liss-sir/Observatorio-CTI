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

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Destruir todas las variables de sesión
        $_SESSION = array();
        
        // Destruir la sesión
        session_destroy();
        
        echo json_encode([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    /**
     * POST /enviar-verificacion
     * Espera JSON con correo
     * Envía un correo de verificación al usuario si existe y no está verificado
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
     * Procesa la verificación mediante token (normalmente se accede desde el enlace del correo)
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
     * Procesa la verificación mediante la session
     */
    public function verificarSesion() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true) {
            echo json_encode([
                'success' => true,
                'autenticado' => true,
                'usuario' => [
                    'id_usuario' => $_SESSION['id_usuario'],
                    'correo' => $_SESSION['correo'],
                    'rol_nombre' => $_SESSION['rol_nombre'],
                    'nombre' => $_SESSION['usuario']['representante_legal'] ?? $_SESSION['usuario']['nombre_empresa'] ?? 'Usuario'
                ]
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'autenticado' => false
            ]);
        }
    }

    /**
     * POST /recuperar
     * Solicita recuperación de contraseña (envía correo con token)
     * Espera JSON con correo
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
     * Espera JSON con token y nueva_password
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
     * Verifica si un correo existe y su estado (opcional, útil para frontend)
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

    /**
     * POST /register
     * Espera JSON con campos: nombre_empresa, razon_social (opcional), representante_legal, tipo_documento, numero_documento, correo, password
     */
    public function register() {
        $input = json_decode(file_get_contents("php://input"), true);

        // Validar campos requeridos
        $required = ['representante_legal', 'tipo_documento', 'numero_documento', 'correo', 'password'];
        foreach ($required as $field) {
            if (!isset($input[$field]) || empty(trim($input[$field]))) {
                echo json_encode([
                    'success' => false,
                    'error' => "El campo $field es requerido"
                ]);
                return;
            }
        }

        // Validar formato de correo
        if (!filter_var($input['correo'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'error' => 'Correo electrónico no válido'
            ]);
            return;
        }

        // Validar longitud de contraseña
        if (strlen($input['password']) < 6) {
            echo json_encode([
                'success' => false,
                'error' => 'La contraseña debe tener al menos 6 caracteres'
            ]);
            return;
        }

        // Validar tipo_documento contra valores permitidos
        $tipos_permitidos = ['CC', 'CE', 'NIT', 'TI', 'PASAPORTE', 'RUT'];
        if (!in_array($input['tipo_documento'], $tipos_permitidos)) {
            echo json_encode([
                'success' => false,
                'error' => 'Tipo de documento no válido'
            ]);
            return;
        }

        // Validar razon_social si se envía (opcional, pero debe ser uno de los valores del enum)
        if (isset($input['razon_social']) && !empty($input['razon_social'])) {
            $razones_permitidas = [
                'SOCIEDAD POR ACCIONES SIMPLIFICADA',
                'SOCIEDAD ANONIMA',
                'SOCIEDAD DE RESPONSABILIDAD LIMITADA',
                'SOCIEDAD EN COMANDITA SIMPLE',
                'SOCIEDAD EN COMANDITA POR ACCIONES',
                'EMPRESA UNIPERSONAL',
                'COOPERATIVA',
                'FUNDACION',
                'ASOCIACION'
            ];
            if (!in_array($input['razon_social'], $razones_permitidas)) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Razón social no válida'
                ]);
                return;
            }
        }

        // Llamar al modelo
        $resultado = $this->model->registrar($input);

        if ($resultado['success']) {
            // Enviar correo de verificación automáticamente
        //$this->model->enviarVerificacion($resultado['id_usuario'], $input['correo']);
            echo json_encode([
                'success' => true,
                'message' => 'Usuario registrado correctamente. Se ha enviado un correo de verificación.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'Error al registrar'
            ]);
        }
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

    case 'logout':
        $controller->logout();
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

    case 'register':
        $controller->register();
        break;

    case 'sesion':
        $controller->verificarSesion();
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}