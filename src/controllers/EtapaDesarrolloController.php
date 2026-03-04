<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/EtapaDesarrollo.php";

class EtapaDesarrolloController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new EtapaDesarrolloModel($conn);
    }

    // List stage activate
    public function listar() {
        $etapas = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $etapas
        ]);
    }

    // list all stage (for admin)
    public function listarTodas() {
        $etapas = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $etapas
        ]);
    }

    // Get stage for ID
    public function obtener($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de etapa requerido'
            ]);
            return;
        }
        
        $etapa = $this->model->obtener($id);
        
        if ($etapa) {
            echo json_encode([
                'success' => true,
                'data' => $etapa
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Etapa no encontrada'
            ]);
        }
    }

    // Create new stage
    public function crear() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (empty($input['id_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El área es requerida'
            ]);
            return;
        }
        
        if (empty($input['nombre'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El nombre de la etapa es requerido'
            ]);
            return;
        }
        
        if ($this->model->nombreExisteEnArea($input['nombre'], $input['id_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Ya existe una etapa con ese nombre en el área seleccionada'
            ]);
            return;
        }
        
        $id = $this->model->crear($input);
        
        if ($id) {
            echo json_encode([
                'success' => true,
                'id_etapa' => $id,
                'message' => 'Etapa creada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear la etapa'
            ]);
        }
    }

    // Update stage exist
    public function actualizar() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_etapa'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de etapa requerido'
            ]);
            return;
        }
        
        if (isset($input['nombre']) && isset($input['id_area'])) {
            if (empty(trim($input['nombre']))) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El nombre de la etapa no puede estar vacío'
                ]);
                return;
            }
            
            if ($this->model->nombreExisteEnArea($input['nombre'], $input['id_area'], $input['id_etapa'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Ya existe una etapa con ese nombre en el área seleccionada'
                ]);
                return;
            }
        }
        
        $resultado = $this->model->actualizar($input);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Etapa actualizada correctamente' : 'Error al actualizar la etapa'
        ]);
    }

    // Change state this stage
    public function cambiarEstado($id, $accion) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de etapa requerido'
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
            'message' => $resultado ? "Etapa {$accion}da correctamente" : "Error al {$accion} la etapa"
        ]);
    }

    // Delete stage
    public function eliminar($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de etapa requerido'
            ]);
            return;
        }
        
        $resultado = $this->model->eliminar($id);
        
        if ($resultado['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Etapa eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'Error al eliminar la etapa'
            ]);
        }
    }


    // Get satage for areas
    public function obtenerPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $etapas = $this->model->obtenerPorArea($id_area);
        
        echo json_encode([
            'success' => true,
            'data' => $etapas
        ]);
    }

    // Get stage from select for area
    public function obtenerParaSelectPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $etapas = $this->model->obtenerParaSelectPorArea($id_area);
        
        $options = [];
        foreach ($etapas as $id => $nombre) {
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

    // Verify if this name in stage exist in area
    public function verificarNombreExistente() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['nombre']) || !isset($input['id_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Nombre y área son requeridos'
            ]);
            return;
        }
        
        $existe = $this->model->nombreExisteEnArea(
            $input['nombre'],
            $input['id_area'],
            $input['excluir_id'] ?? null
        );
        
        echo json_encode([
            'existe' => $existe
        ]);
    }

    // Verify if stage have dependences
    public function verificarDependencias($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de etapa requerido'
            ]);
            return;
        }
        
        $tieneDependencias = $this->model->tieneDependencias($id);
        
        echo json_encode([
            'tiene_dependencias' => $tieneDependencias
        ]);
    }

    // Get statistics from stage
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Get stage for term
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

    // Get stage for select
    public function obtenerParaSelect() {
        $etapas = $this->model->obtenerParaSelect();
        
        $options = [];
        foreach ($etapas as $id => $nombre) {
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
$id = $_GET['id_etapa'] ?? null;
$id_area = $_GET['id_area'] ?? null;

if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new EtapaDesarrolloController($conn);

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
    
    // Methods area
    case "porArea":
        $controller->obtenerPorArea($id_area);
        break;
        
    case "paraSelectPorArea":
        $controller->obtenerParaSelectPorArea($id_area);
        break;
        
    // Validations
    case "verificarNombre":
        $controller->verificarNombreExistente();
        break;
        
    case "verificarDependencias":
        $controller->verificarDependencias($id);
        break;
        
    // Statistics
    case "estadisticas":
        $controller->obtenerEstadisticas();
        break;
        
    // BSearch and utils
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