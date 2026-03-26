<?php

header("Content-Type: application/json; charset=utf-8");

// ========== CORREGIDO: RUTAS CON MINÚSCULAS ==========
require_once __DIR__ . "/../../config/database.php";  // config con minúscula
require_once __DIR__ . "/../models/Usuario.php";      // Usuario con mayúscula (como está en tu estructura)
require_once __DIR__ . "/../helpers/permisos.php";

class UsuarioController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new UsuarioModel($conn);
    }

    /* ================= USUARIOS (CRUD) ================= */

    public function listar() {
        verificarPermiso('gestionar_usuarios');
        $usuarios = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $usuarios
        ]);
    }

    public function obtener($id) {
        if (!$id) {
            echo json_encode(['error' => 'id_usuario requerido']);
            return;
        }
        $usuario = $this->model->obtener($id);
        echo json_encode([
            'status' => 'success',
            'data' => $usuario
        ]);
    }

    public function crear() {
        verificarPermiso('gestionar_usuarios');
        $input = json_decode(file_get_contents("php://input"), true);
        
        // Validaciones básicas
        $camposRequeridos = ['representante_legal', 'tipo_documento', 'numero_documento', 'correo', 'password'];
        foreach ($camposRequeridos as $campo) {
            if (empty($input[$campo])) {
                echo json_encode([
                    'success' => false,
                    'error' => "El campo $campo es requerido"
                ]);
                return;
            }
        }
        
        // Validar que el correo no exista
        if ($this->model->correoExiste($input['correo'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El correo electrónico ya está registrado'
            ]);
            return;
        }
        
        $id = $this->model->crear($input);
        
        if ($id) {
            echo json_encode([
                'success' => true,
                'id_usuario' => $id,
                'message' => 'Usuario creado correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear el usuario'
            ]);
        }
    }

    public function actualizar() {
        verificarPermiso('gestionar_usuarios');
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'error' => 'id_usuario requerido'
            ]);
            return;
        }
        
        echo json_encode([
            'success' => $this->model->actualizar($input)
        ]);
    }

    public function cambiarEstado($id, $accion) {
        verificarPermiso('gestionar_usuarios');
        if (!$id) {
            echo json_encode(['error' => 'id_usuario requerido']);
            return;
        }
        
        $map = [
            "activar" => 1,
            "desactivar" => 0
        ];
        
        $estado = $map[$accion] ?? null;
        
        if ($estado === null) {
            echo json_encode(['error' => 'Acción no válida']);
            return;
        }
        
        echo json_encode([
            'success' => $this->model->cambiarEstado($id, $estado)
        ]);
    }

    /* ================= ROLES ================= */

    public function obtenerRoles() {
        echo json_encode($this->model->obtenerRoles());
    }

    /* ================= AUTENTICACIÓN ================= */

    public function login() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['correo']) || !isset($input['password'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Correo y contraseña requeridos'
            ]);
            return;
        }
        
        $resultado = $this->model->login($input['correo'], $input['password']);
        echo json_encode($resultado);
    }

    /* ================= ESTADÍSTICAS ================= */

    public function obtenerEstadisticas() {
        echo json_encode($this->model->obtenerEstadisticas());
    }

    /* ================= VALIDACIONES ================= */

    public function verificarCorreoExistente() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['correo'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Correo requerido'
            ]);
            return;
        }
        
        $existe = $this->model->correoExiste(
            $input['correo'], 
            $input['excluir_id'] ?? null
        );
        
        echo json_encode([
            'existe' => $existe
        ]);
    }
}

/* ================= ROUTER ================= */

$accion = $_GET['accion'] ?? null;
$id = $_GET['id_usuario'] ?? null;

// Verificar que la conexión existe
if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new UsuarioController($conn);

switch ($accion) {
    
    case "listar":
        $controller->listar();
        break;

    case "obtener":
        $controller->obtener($id);
        break;

    case "crear":
        $controller->crear();
        break;

    case "actualizar":
        $controller->actualizar();
        break;

    case "activar":
        $controller->cambiarEstado($id, "activar");
        break;

    case "desactivar":
        $controller->cambiarEstado($id, "desactivar");
        break;

    case "roles":
        $controller->obtenerRoles();
        break;

    case "login":
        $controller->login();
        break;

    case "estadisticas":
        $controller->obtenerEstadisticas();
        break;

    case "verificarCorreoExistente":
        $controller->verificarCorreoExistente();
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}