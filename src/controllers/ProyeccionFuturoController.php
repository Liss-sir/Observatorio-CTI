<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/ProyeccionFuturo.php";

class ProyeccionFuturoController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new ProyeccionFuturoModel($conn);
    }

    // List projection active
    public function listar() {
        $proyecciones = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $proyecciones
        ]);
    }

    // List all projection (for admin)
    public function listarTodas() {
        $proyecciones = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $proyecciones
        ]);
    }

    // Get projection for ID
    public function obtener($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de proyección requerido'
            ]);
            return;
        }
        
        $proyeccion = $this->model->obtener($id);
        
        if ($proyeccion) {
            echo json_encode([
                'success' => true,
                'data' => $proyeccion
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Proyección no encontrada'
            ]);
        }
    }

    // Create new projection
    public function crear() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (empty($input['id_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El área es requerida'
            ]);
            return;
        }
        
        if (empty($input['anio'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El año de proyección es requerido'
            ]);
            return;
        }
        
        if (empty($input['descripcion'])) {
            echo json_encode([
                'success' => false,
                'error' => 'La descripción de la proyección es requerida'
            ]);
            return;
        }
        
        if ($this->model->existePorAreaYAnio($input['id_area'], $input['anio'])) {
            $anio_texto = $this->model->getListaAnios()[$input['anio']];
            echo json_encode([
                'success' => false,
                'error' => "Ya existe una proyección para {$anio_texto} en el área seleccionada"
            ]);
            return;
        }
        
        $id = $this->model->crear($input);
        
        if ($id) {
            echo json_encode([
                'success' => true,
                'id_proyeccion' => $id,
                'message' => 'Proyección creada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear la proyección'
            ]);
        }
    }

    // Update projection existis
    public function actualizar() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_proyeccion'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de proyección requerido'
            ]);
            return;
        }
        
        if (isset($input['id_area']) && isset($input['anio'])) {
            if ($this->model->existePorAreaYAnio($input['id_area'], $input['anio'], $input['id_proyeccion'])) {
                $anio_texto = $this->model->getListaAnios()[$input['anio']];
                echo json_encode([
                    'success' => false,
                    'error' => "Ya existe una proyección para {$anio_texto} en el área seleccionada"
                ]);
                return;
            }
        }
        
        $resultado = $this->model->actualizar($input);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Proyección actualizada correctamente' : 'Error al actualizar la proyección'
        ]);
    }

    // Change state this projection (activate/desactivate)
    public function cambiarEstado($id, $accion) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de proyección requerido'
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
            'message' => $resultado ? "Proyección {$accion}da correctamente" : "Error al {$accion} la proyección"
        ]);
    }

    // Delete projection
    public function eliminar($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de proyección requerido'
            ]);
            return;
        }
        
        $resultado = $this->model->eliminar($id);
        
        if ($resultado['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Proyección eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'Error al eliminar la proyección'
            ]);
        }
    }

    // Get projection for area
    public function obtenerPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $proyecciones = $this->model->obtenerPorArea($id_area);
        
        echo json_encode([
            'success' => true,
            'data' => $proyecciones
        ]);
    }

    // Get projection from select or area
    public function obtenerParaSelectPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $proyecciones = $this->model->obtenerParaSelectPorArea($id_area);
        
        $options = [];
        foreach ($proyecciones as $id => $nombre) {
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

    // Get projection for year
    public function obtenerPorAnio($anio) {
        if (!$anio) {
            echo json_encode([
                'success' => false,
                'error' => 'Año requerido'
            ]);
            return;
        }
        
        $proyecciones = $this->model->obtenerPorAnio($anio);
        
        echo json_encode([
            'success' => true,
            'data' => $proyecciones
        ]);
    }

    // Get years available
    public function obtenerAniosDisponibles() {
        $anios = $this->model->obtenerAniosDisponibles();
        
        echo json_encode([
            'success' => true,
            'data' => $anios
        ]);
    }

    // Get list compplete year
    public function obtenerListaAnios() {
        $anios = $this->model->getListaAnios();
        
        $options = [];
        foreach ($anios as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $options
        ]);
    }

    // Verify if exist projection from area and taer
    public function verificarExistencia() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_area']) || !isset($input['anio'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Área y año son requeridos'
            ]);
            return;
        }
        
        $existe = $this->model->existePorAreaYAnio(
            $input['id_area'],
            $input['anio'],
            $input['excluir_id'] ?? null
        );
        
        echo json_encode([
            'existe' => $existe
        ]);
    }

    // Verify if the projections have dependences
    public function verificarDependencias($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de proyección requerido'
            ]);
            return;
        }
        
        $tieneDependencias = $this->model->tieneDependencias($id);
        
        echo json_encode([
            'tiene_dependencias' => $tieneDependencias
        ]);
    }

    // Get statistics from projection
    /**
     * Obtener estadísticas de proyecciones
     */
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Get projection for term
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

    // Get projection from select (alls)
    public function obtenerParaSelect() {
        $proyecciones = $this->model->obtenerParaSelect();
        
        $options = [];
        foreach ($proyecciones as $id => $nombre) {
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
$id = $_GET['id_proyeccion'] ?? null;
$id_area = $_GET['id_area'] ?? null;
$anio = $_GET['anio'] ?? null;

if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new ProyeccionFuturoController($conn);

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
    
    // Methods for area
    case "porArea":
        $controller->obtenerPorArea($id_area);
        break;
        
    case "paraSelectPorArea":
        $controller->obtenerParaSelectPorArea($id_area);
        break;
    
    // Methods for year
    case "porAnio":
        $controller->obtenerPorAnio($anio);
        break;
        
    case "aniosDisponibles":
        $controller->obtenerAniosDisponibles();
        break;
        
    case "listaAnios":
        $controller->obtenerListaAnios();
        break;
        
    // Validations
    case "verificarExistencia":
        $controller->verificarExistencia();
        break;
        
    case "verificarDependencias":
        $controller->verificarDependencias($id);
        break;
        
    // Statistics
    case "estadisticas":
        $controller->obtenerEstadisticas();
        break;
        
    // Search ans utils
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