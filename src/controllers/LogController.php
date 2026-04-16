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
     * Expect JSON with email and password
     */
    public function login() {
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

        $resultado = $this->model->login($input['correo'], $input['password']);
        
        if ($resultado['success'] && isset($resultado['usuario'])) {
            $_SESSION['usuario'] = $resultado['usuario'];
            $_SESSION['id_usuario'] = $resultado['usuario']['id_usuario'];
            $_SESSION['correo'] = $resultado['usuario']['correo'];
            $_SESSION['rol_nombre'] = $resultado['usuario']['rol_nombre'];
            $_SESSION['autenticado'] = true;
            
            $resultado['redirect'] = '../../view/dashboard/dashboard.php';
        }
        
        echo json_encode($resultado);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = array();
        
        session_destroy();
        
        echo json_encode([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    /**
     * POST /send-verification
     * Waits for JSON with email address
     * Sends a verification email to the user if they exist and are not verified
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
     * Process the verification using a token (usually accessed via the email link)
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

        if ($resultado['success']) {
            header("Location: ../auth/login/verificacion_exitosa.php");
        } else {
            header("Location: ../auth/login/error_verificacion.php");
        }
        exit;
    }

    /**
     * Process the verification through the session
     */
    public function verificarSesion() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
                echo json_encode(['success' => false, 'autenticado' => false]);
                return;
            }

            $id = $_SESSION['id_usuario'];
            
            $usuario = $this->model->obtenerPorId($id);

            if (!$usuario || $usuario['estado'] != 1) {
                session_unset();
                session_destroy();
                echo json_encode([
                    'success' => false,
                    'autenticado' => false,
                    'logout' => true,
                    'message' => 'Usuario deshabilitado'
                ]);
                return;
            }

            echo json_encode([
                'success' => true,
                'autenticado' => true,
                'usuario' => [
                    'id_usuario' => $usuario['id_usuario'],
                    'correo' => $usuario['correo'],
                    'rol_nombre' => $usuario['rol_nombre'],
                    'nombre_empresa' => $usuario['nombre_empresa'] ?? 'Usuario'
                ]
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'autenticado' => false,
                'error' => 'Error interno en verificación',
                'debug' => $e->getMessage() 
            ]);
        }
    }

    /**
     * POST /recover
     * Request password recovery (send email with token)
     * Expect JSON with email
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
     * POST /restore
     * Reset your password using a token
     * Expect JSON with token and new_password
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
     * Check if an email exists and its status (optional, useful for frontend)
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
     * Expect JSON with fields: company_name, legal_name (optional), legal_representative, document_type, document_number, email, password
     */
    public function register() {
        $input = json_decode(file_get_contents("php://input"), true);

        session_start();

        $mapaRoles = [
            'administrador' => 1,
            'empresa' => 2
        ];

        $idRol = 2;

        if (isset($_SESSION['rol_nombre']) && strtolower($_SESSION['rol_nombre']) === 'administrador') {
            if (isset($input['rol']) && isset($mapaRoles[$input['rol']])) {
                $idRol = $mapaRoles[$input['rol']];
            }
        }

        $input['id_rol'] = $idRol;

        // Validate required fields
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

        // Validate email format
        if (!filter_var($input['correo'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'error' => 'Correo electrónico no válido'
            ]);
            return;
        }

        // Validate password length
        if (strlen($input['password']) < 6) {
            echo json_encode([
                'success' => false,
                'error' => 'La contraseña debe tener al menos 6 caracteres'
            ]);
            return;
        }

        // Validate document_type against allowed values
        $tipos_permitidos = ['CC', 'CE', 'NIT', 'TI', 'PASAPORTE', 'RUT'];
        if (!in_array($input['tipo_documento'], $tipos_permitidos)) {
            echo json_encode([
                'success' => false,
                'error' => 'Tipo de documento no válido'
            ]);
            return;
        }

        // Validate company name if it is sent (optional, but it must be one of the values ​​in the enum)
        $idRol = $input['id_rol'] ?? 2;

        if (isset($input['razon_social']) && !empty($input['razon_social']) && $idRol != 1) {
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

        // Call the model
        $resultado = $this->model->registrar($input);

        if ($resultado['success']) {
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