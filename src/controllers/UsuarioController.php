<?php
header("Content-Type: application/json; charset=utf-8");

error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Usuario.php";
require_once __DIR__ . "/../helpers/permisos.php";

class UsuarioController {
    private $model;
    
    public function __construct(PDO $conn) {
        $this->model = new UsuarioModel($conn);
    }
    
    /* ================= USERS (CRUD) ================= */
    public function listar() {
        try {
            verificarPermiso('gestionar_usuarios');
            $usuarios = $this->model->listar();
            echo json_encode([
                'status' => 'success',
                'data' => $usuarios
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    public function obtener($id) {
        try {
            if (!$id) {
                echo json_encode(['error' => 'id_usuario requerido']);
                return;
            }
            $usuario = $this->model->obtener($id);
            echo json_encode([
                'status' => 'success',
                'data' => $usuario
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    /* ================= GET FULL DETAILS ================= */
    public function obtenerDetalle() {
        try {
            $id = $_GET['id_usuario'] ?? null;

            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'id_usuario requerido']);
                return;
            }

            $usuario = $this->model->obtenerCompleto($id);

            echo json_encode([
                'status' => 'success',
                'data' => $usuario
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    public function crear() {
        try {
            verificarPermiso('gestionar_usuarios');
            $input = json_decode(file_get_contents("php://input"), true);
            
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
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function actualizar() {
        try {
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
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function cambiarEstado($id, $accion) {
        try {
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
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /* =================  ================= */
    public function obtenerRoles() {
        try {
            echo json_encode($this->model->obtenerRoles());
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /* ================= STATISTICS ================= */
    public function obtenerEstadisticas() {
        try {
            echo json_encode($this->model->obtenerEstadisticas());
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /* ================= VALIDATIONS ================= */
    public function verificarCorreoExistente() {
        try {
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
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

/* ================= ROUTER ================= */
$accion = $_GET['accion'] ?? null;
$id = $_GET['id_usuario'] ?? null;

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
    case "obtenerDetalle": 
        $controller->obtenerDetalle();
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