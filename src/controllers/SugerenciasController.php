<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Sugerencias.php";
require_once __DIR__ . "/../helpers/permisos.php";

class SugerenciasController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new SugerenciasModel($conn);
    }

    // List active suggestions
    public function listar() {
        $sugerencias = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $sugerencias
        ]);
    }

    // List all suggestions (For admin)
    public function listarTodas() {
        $sugerencias = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $sugerencias
        ]);
    }

    // Get suggestion by ID
    public function obtener($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de sugerencia requerido'
            ]);
            return;
        }
        
        $sugerencia = $this->model->obtener($id);
        
        if ($sugerencia) {
            echo json_encode([
                'success' => true,
                'data' => $sugerencia
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Sugerencia no encontrada'
            ]);
        }
    }

    // Get suggestions by user
    public function obtenerPorUsuario($id_usuario) {
        if (!$id_usuario) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de usuario requerido'
            ]);
            return;
        }
        
        $sugerencias = $this->model->obtenerPorUsuario($id_usuario);
        
        echo json_encode([
            'success' => true,
            'data' => $sugerencias
        ]);
    }

    // Create new suggestion
    public function crear() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        // Basic validations
        if (empty($input['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El ID de usuario es requerido'
            ]);
            return;
        }
        
        // Verify user exists and is active
        if (!$this->model->usuarioExisteYActivo($input['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El usuario no existe o no está activo'
            ]);
            return;
        }
        
        if (empty($input['tipo_sugerencia'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El tipo de sugerencia es requerido'
            ]);
            return;
        }
        
        // Validate tipo_sugerencia
        if (!$this->model->validarTipoSugerencia($input['tipo_sugerencia'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Tipo de sugerencia no válido. Debe ser PROGRAMA, LINEA_TECNOLOGICA o OTROS'
            ]);
            return;
        }
        
        if (empty($input['titulo'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El título es requerido'
            ]);
            return;
        }
        
        if (empty($input['contenido'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El contenido es requerido'
            ]);
            return;
        }
        
        // Validate data
        $errores_validacion = $this->model->validarDatos($input['titulo'], $input['contenido']);
        if (!empty($errores_validacion)) {
            echo json_encode([
                'success' => false,
                'error' => $errores_validacion[0]
            ]);
            return;
        }
        
        $id = $this->model->crear($input);
        
        if ($id) {
            echo json_encode([
                'success' => true,
                'id_sugerencia' => $id,
                'message' => 'Sugerencia creada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear la sugerencia'
            ]);
        }
    }

    // Update existing suggestion
    public function actualizar() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_sugerencia'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de sugerencia requerido'
            ]);
            return;
        }
        
        // Validate tipo_sugerencia if provided
        if (isset($input['tipo_sugerencia']) && !$this->model->validarTipoSugerencia($input['tipo_sugerencia'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Tipo de sugerencia no válido. Debe ser PROGRAMA, LINEA_TECNOLOGICA o OTROS'
            ]);
            return;
        }
        
        // Conditional validations for titulo/contenido
        if (isset($input['titulo']) || isset($input['contenido'])) {
            $titulo = $input['titulo'] ?? '';
            $contenido = $input['contenido'] ?? '';
            
            if (!empty($titulo) || !empty($contenido)) {
                $errores_validacion = $this->model->validarDatos(
                    $titulo ?: 'Validación',
                    $contenido ?: 'Validación'
                );
                
                if (!empty($errores_validacion)) {
                    echo json_encode([
                        'success' => false,
                        'error' => $errores_validacion[0]
                    ]);
                    return;
                }
            }
        }
        
        $resultado = $this->model->actualizar($input);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Sugerencia actualizada correctamente' : 'Error al actualizar la sugerencia'
        ]);
    }

    // Change state in suggestions
    public function cambiarEstado($id, $accion) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de sugerencia requerido'
            ]);
            return;
        }
        
        $map = [
            "activar" => 1,
            "desactivar" => 0
        ];
        
        $estado = $map[$accion] ?? null;
        
        if ($estado === null) {
            echo json_encode([
                'success' => false,
                'error' => 'Acción no válida'
            ]);
            return;
        }
        
        $resultado = $this->model->cambiarEstado($id, $estado);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? "Sugerencia {$accion}da correctamente" : "Error al {$accion} la sugerencia"
        ]);
    }

    // Delete suggestion
    public function eliminar($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de sugerencia requerido'
            ]);
            return;
        }
        
        $resultado = $this->model->eliminar($id);
        
        if ($resultado['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Sugerencia eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'Error al eliminar la sugerencia'
            ]);
        }
    }

    // Get recent suggestions
    public function obtenerRecientes() {
        $limite = $_GET['limite'] ?? 10;
        $sugerencias = $this->model->obtenerRecientes((int)$limite);
        
        echo json_encode([
            'success' => true,
            'data' => $sugerencias
        ]);
    }

    // Search suggestions
    public function buscar() {
        $termino = $_GET['q'] ?? '';
        
        if (empty($termino)) {
            echo json_encode([
                'success' => false,
                'error' => 'Término de búsqueda requerido'
            ]);
            return;
        }
        
        $resultados = $this->model->buscar($termino);
        
        echo json_encode([
            'success' => true,
            'data' => $resultados
        ]);
    }

    // Get statistics
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Get suggestions for select
    public function obtenerParaSelect() {
        $sugerencias = $this->model->obtenerParaSelect();
        
        $options = [];
        foreach ($sugerencias as $id => $nombre) {
            $options[] = [
                'id' => $id,
                'text' => $nombre
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $options
        ]);
    }

    // Verify if user exists
    public function verificarUsuario($id_usuario) {
        if (!$id_usuario) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de usuario requerido'
            ]);
            return;
        }
        
        $existe = $this->model->usuarioExisteYActivo($id_usuario);
        
        echo json_encode([
            'existe' => $existe
        ]);
    }
}


$accion = $_GET['accion'] ?? null;
$id = $_GET['id_sugerencia'] ?? null;
$id_usuario = $_GET['id_usuario'] ?? null;

// Verify database connection
if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new SugerenciasController($conn);

switch ($accion) {
    
    // Basic CRUD
    case "listar":
        $controller->listar();
        break;
        
    case "listarTodas":
        $controller->listarTodas();
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
        
    case "eliminar":
        $controller->eliminar($id);
        break;
    
    // Methods by user
    case "porUsuario":
        $controller->obtenerPorUsuario($id_usuario);
        break;
        
    case "verificarUsuario":
        $controller->verificarUsuario($id_usuario);
        break;
    
    // Utility methods
    case "recientes":
        $controller->obtenerRecientes();
        break;
        
    case "estadisticas":
        $controller->obtenerEstadisticas();
        break;
        
    case "buscar":
        $controller->buscar();
        break;
        
    case "paraSelect":
        $controller->obtenerParaSelect();
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}