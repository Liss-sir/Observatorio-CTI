<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/TendenciaEmergente.php";

class TendenciaEmergenteController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new TendenciaEmergenteModel($conn);
    }

    // List treands active
    public function listar() {
        $tendencias = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $tendencias
        ]);
    }

    // List all treands (from admin)
    public function listarTodas() {
        $tendencias = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $tendencias
        ]);
    }

    // Get treands for ID 
    public function obtener($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de tendencia requerido'
            ]);
            return;
        }
        
        $tendencia = $this->model->obtener($id);
        
        if ($tendencia) {
            echo json_encode([
                'success' => true,
                'data' => $tendencia
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Tendencia no encontrada'
            ]);
        }
    }

    // Create new treands
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
                'error' => 'El nombre de la tendencia es requerido'
            ]);
            return;
        }
        
        if ($this->model->nombreExisteEnArea($input['nombre'], $input['id_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Ya existe una tendencia con ese nombre en el área seleccionada'
            ]);
            return;
        }
        
        $id = $this->model->crear($input);
        
        if ($id) {
            echo json_encode([
                'success' => true,
                'id_tendencia' => $id,
                'message' => 'Tendencia creada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear la tendencia'
            ]);
        }
    }

    // Update treands exist
    public function actualizar() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_tendencia'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de tendencia requerido'
            ]);
            return;
        }
        
        if (isset($input['nombre']) && isset($input['id_area'])) {
            if (empty(trim($input['nombre']))) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El nombre de la tendencia no puede estar vacío'
                ]);
                return;
            }
            
            if ($this->model->nombreExisteEnArea($input['nombre'], $input['id_area'], $input['id_tendencia'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Ya existe una tendencia con ese nombre en el área seleccionada'
                ]);
                return;
            }
        }
        
        $resultado = $this->model->actualizar($input);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Tendencia actualizada correctamente' : 'Error al actualizar la tendencia'
        ]);
    }

    // Change state of the treands
    public function cambiarEstado($id, $accion) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de tendencia requerido'
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
            'message' => $resultado ? "Tendencia {$accion}da correctamente" : "Error al {$accion} la tendencia"
        ]);
    }

    // Delete treands 
    public function eliminar($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de tendencia requerido'
            ]);
            return;
        }
        
        $resultado = $this->model->eliminar($id);
        
        if ($resultado['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Tendencia eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'Error al eliminar la tendencia'
            ]);
        }
    }

    // Get trands for area
    public function obtenerPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $tendencias = $this->model->obtenerPorArea($id_area);
        
        echo json_encode([
            'success' => true,
            'data' => $tendencias
        ]);
    }

    // Get treands from select for area
    public function obtenerParaSelectPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $tendencias = $this->model->obtenerParaSelectPorArea($id_area);
        
        $options = [];
        foreach ($tendencias as $id => $nombre) {
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

    // Search treands for term
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

    // Search avanced whith filters
    /**
     * Búsqueda avanzada con filtros
     */
    public function buscarAvanzado() {
        $filtros = json_decode(file_get_contents("php://input"), true);
        
        $resultados = $this->model->buscarAvanzado($filtros ?? []);
        
        echo json_encode([
            'success' => true,
            'data' => $resultados
        ]);
    }

    // Verify if the name this treands exist in area
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

    // Verify if the trands have dependences
    public function verificarDependencias($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de tendencia requerido'
            ]);
            return;
        }
        
        $tieneDependencias = $this->model->tieneDependencias($id);
        
        echo json_encode([
            'tiene_dependencias' => $tieneDependencias
        ]);
    }

    // Get statistics in treands
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Get treands for select
    public function obtenerParaSelect() {
        $tendencias = $this->model->obtenerParaSelect();
        
        $options = [];
        foreach ($tendencias as $id => $nombre) {
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

    // Get popular trends
    public function obtenerTendenciasPopulares() {
        $limite = $_GET['limite'] ?? 5;
        $tendencias = $this->model->obtenerTendenciasPopulares((int)$limite);
        
        echo json_encode([
            'success' => true,
            'data' => $tendencias
        ]);
    }

    // Get trends in cont this line
    public function obtenerConConteoLineas() {
        $id_area = $_GET['id_area'] ?? null;
        $tendencias = $this->model->obtenerConConteoLineas($id_area);
        
        echo json_encode([
            'success' => true,
            'data' => $tendencias
        ]);
    }
}


$accion = $_GET['accion'] ?? null;
$id = $_GET['id_tendencia'] ?? null;
$id_area = $_GET['id_area'] ?? null;

if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new TendenciaEmergenteController($conn);

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
    
    // MMethods from area
    case "porArea":
        $controller->obtenerPorArea($id_area);
        break;
        
    case "paraSelectPorArea":
        $controller->obtenerParaSelectPorArea($id_area);
        break;
    
    // Searchin
    case "buscar":
        $controller->buscar();
        break;
        
    case "buscarAvanzado":
        $controller->buscarAvanzado();
        break;
        
    // Validation
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
        
    // Methods additional
    case "paraSelect":
        $controller->obtenerParaSelect();
        break;
        
    case "populares":
        $controller->obtenerTendenciasPopulares();
        break;
        
    case "conConteoLineas":
        $controller->obtenerConConteoLineas();
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}