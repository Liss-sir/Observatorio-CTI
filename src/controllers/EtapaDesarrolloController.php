<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/EtapaDesarrollo.php";

class EtapaDesarrolloController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new EtapaDesarrolloModel($conn);
    }

    // Listar etapas activas
    public function listar() {
        $etapas = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $etapas
        ]);
    }

    // Listar todas las etapas (para admin)
    public function listarTodas() {
        $etapas = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $etapas
        ]);
    }

    // Obtener etapa por ID
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

    // Crear nueva etapa
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

    // Actualizar etapa existente
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

    // Cambiar estado de la etapa
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

    // Eliminar etapa
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

    // Obtener etapas por área
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

    // Obtener etapas para select por área
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
        foreach ($etapas as $id => $texto) {
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

    // Verificar si la etapa tiene dependencias
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

    // Obtener estadísticas de etapas
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Buscar etapas por término en nombre
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

    // Obtener todas las etapas para select (global)
    public function obtenerParaSelect() {
        $etapas = $this->model->obtenerParaSelect();
        
        $options = [];
        foreach ($etapas as $id => $texto) {
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
    
    // Métodos por área
    case "porArea":
        $controller->obtenerPorArea($id_area);
        break;
        
    case "paraSelectPorArea":
        $controller->obtenerParaSelectPorArea($id_area);
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
        
    // Búsqueda y utilidades
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