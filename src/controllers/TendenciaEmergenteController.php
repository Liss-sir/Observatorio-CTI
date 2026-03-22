<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/TendenciaEmergente.php";

class TendenciaEmergenteController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new TendenciaEmergenteModel($conn);
    }

    // Listar tendencias activas
    public function listar() {
        $tendencias = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $tendencias
        ]);
    }

    // Listar todas las tendencias (para admin)
    public function listarTodas() {
        $tendencias = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $tendencias
        ]);
    }

    // Obtener tendencia por ID
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

    // Crear nueva tendencia
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

    // Actualizar tendencia existente
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

    // Cambiar estado de la tendencia
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

    // Eliminar tendencia
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

    // Obtener tendencias por área
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

    // Obtener tendencias para select por área
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
        foreach ($tendencias as $id => $texto) {
            $options[] = [
                'id' => $id,
                'text' => $texto
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $options
        ]);
    }

    // Buscar tendencias por término en nombre
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

    // Búsqueda avanzada con filtros
    public function buscarAvanzado() {
        $filtros = json_decode(file_get_contents("php://input"), true);
        
        $resultados = $this->model->buscarAvanzado($filtros ?? []);
        
        echo json_encode([
            'success' => true,
            'data' => $resultados
        ]);
    }

    // Verificar si el nombre ya existe en el área
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

    // Verificar si la tendencia tiene dependencias
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

    // Obtener estadísticas de tendencias
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Obtener todas las tendencias para select (global)
    public function obtenerParaSelect() {
        $tendencias = $this->model->obtenerParaSelect();
        
        $options = [];
        foreach ($tendencias as $id => $texto) {
            $options[] = [
                'id' => $id,
                'text' => $texto
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $options
        ]);
    }

    // Obtener tendencias populares
    public function obtenerTendenciasPopulares() {
        $limite = $_GET['limite'] ?? 5;
        $tendencias = $this->model->obtenerTendenciasPopulares((int)$limite);
        
        echo json_encode([
            'success' => true,
            'data' => $tendencias
        ]);
    }

    // Obtener tendencias con conteo de líneas asociadas
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
    
    // Métodos por área
    case "porArea":
        $controller->obtenerPorArea($id_area);
        break;
        
    case "paraSelectPorArea":
        $controller->obtenerParaSelectPorArea($id_area);
        break;
    
    // Búsquedas
    case "buscar":
        $controller->buscar();
        break;
        
    case "buscarAvanzado":
        $controller->buscarAvanzado();
        break;
        
    // Validaciones
    case "verificarNombre":
        $controller->verificarNombreExistente();
        break;
        
    case "verificarDependencias":
        $controller->verificarDependencias($id);
        break;
        
    // Estadísticas
    case "estadisticas":
        $controller->obtenerEstadisticas();
        break;
        
    // Métodos adicionales
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