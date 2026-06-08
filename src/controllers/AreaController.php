<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Areas.php";
require_once __DIR__ . "/../helpers/permisos.php";

class AreaController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new AreaModel($conn);
    }

    // List areas active
    public function listar() {
        verificarPermiso('ver_areas');
        $areas = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $areas
        ]);
    }

    // List all areas (For admin)
    public function listarTodas() {
        verificarPermiso('ver_areas');
        $areas = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $areas
        ]);
    }

    // Get areas for ID
    public function obtener($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $area = $this->model->obtener($id);
        
        if ($area) {
            echo json_encode([
                'success' => true,
                'data' => $area
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Área no encontrada'
            ]);
        }
    }

    // Create new area
    public function crear() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (empty($input['nombre_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El nombre del área es requerido'
            ]);
            return;
        }
        
        if ($this->model->nombreExiste($input['nombre_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El nombre del área ya está registrado'
            ]);
            return;
        }
        
        $id = $this->model->crear($input);
        
        if ($id) {
            echo json_encode([
                'success' => true,
                'id_area' => $id,
                'message' => 'Área creada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear el área'
            ]);
        }
    }

    // Update areas exist
    public function actualizar() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        if (isset($input['nombre_area'])) {
            if (empty(trim($input['nombre_area']))) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El nombre del área no puede estar vacío'
                ]);
                return;
            }
            
            if ($this->model->nombreExiste($input['nombre_area'], $input['id_area'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El nombre del área ya está registrado'
                ]);
                return;
            }
        }
        
        $resultado = $this->model->actualizar($input);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Área actualizada correctamente' : 'Error al actualizar el área'
        ]);
    }

    // Change state in areas
    public function cambiarEstado($id, $accion) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
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
            'message' => $resultado ? "Área {$accion}da correctamente" : "Error al {$accion} el área"
        ]);
    }

    // Delete areas
    public function eliminar($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $resultado = $this->model->eliminar($id);
        
        if ($resultado['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Área eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'Error al eliminar el área'
            ]);
        }
    }

    // Verify that name of areas this exist
    public function verificarNombreExistente() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['nombre_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Nombre de área requerido'
            ]);
            return;
        }
        
        $existe = $this->model->nombreExiste(
            $input['nombre_area'],
            $input['excluir_id'] ?? null
        );
        
        echo json_encode([
            'existe' => $existe
        ]);
    }

    // Verify that areas have dependences
    public function verificarDependencias($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $tieneDependencias = $this->model->tieneDependencias($id);
        
        echo json_encode([
            'tiene_dependencias' => $tieneDependencias
        ]);
    }

    // Get statistics for areas
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Search areas for term
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

    // Get areas for select
    public function obtenerParaSelect() {
        $areas = $this->model->obtenerParaSelect();
        
        $options = [];
        foreach ($areas as $id => $nombre) {
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
}

$accion = $_GET['accion'] ?? null;
$id = $_GET['id_area'] ?? null;

// Verify the conexion exist
if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new AreaController($conn);

switch ($accion) {
    
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
        
    case "verificarNombre":
        $controller->verificarNombreExistente();
        break;
        
    case "verificarDependencias":
        $controller->verificarDependencias($id);
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