<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/ProyeccionFuturo.php";

class ProyeccionFuturoController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new ProyeccionFuturoModel($conn);
    }

    // Listar proyecciones activas
    public function listar() {
        $proyecciones = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $proyecciones
        ]);
    }

    // Listar todas las proyecciones (para admin)
    public function listarTodas() {
        $proyecciones = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $proyecciones
        ]);
    }

    // Obtener proyección por ID
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

    // Crear nueva proyección
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
        
        if (empty($input['nombre'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El nombre de la proyección es requerido'
            ]);
            return;
        }

        // Verificar si ya existe otra proyección con el mismo nombre en el área
        if ($this->model->existePorAreaYNombre($input['id_area'], $input['nombre'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Ya existe una proyección con este nombre en el área seleccionada.'
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

    // Actualizar proyección existente
    public function actualizar() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_proyeccion'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de proyección requerido'
            ]);
            return;
        }
        
        if ($this->model->existePorAreaYNombre($input['id_area'], $input['nombre'], $input['id_proyeccion'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Ya existe otra proyección con este nombre en el área seleccionada.'
            ]);
            return;
        }

        $resultado = $this->model->actualizar($input);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Proyección actualizada correctamente' : 'Error al actualizar la proyección'
        ]);
    }

    // Cambiar estado de la proyección
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

    // Eliminar proyección
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

    // Obtener proyecciones por área
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

    // Obtener proyecciones para select por área
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

    // Obtener proyecciones por año
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

    // Obtener años disponibles
    public function obtenerAniosDisponibles() {
        $anios = $this->model->obtenerAniosDisponibles();
        
        echo json_encode([
            'success' => true,
            'data' => $anios
        ]);
    }

    // Obtener lista completa de años
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

    // Verificar existencia por área y año
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

    // Verificar dependencias
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

    // Obtener estadísticas
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Buscar proyecciones
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

    // Obtener proyecciones para select (global)
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
    
    // Métodos por área
    case "porArea":
        $controller->obtenerPorArea($id_area);
        break;
        
    case "paraSelectPorArea":
        $controller->obtenerParaSelectPorArea($id_area);
        break;
    
    // Métodos por año
    case "porAnio":
        $controller->obtenerPorAnio($anio);
        break;
        
    case "aniosDisponibles":
        $controller->obtenerAniosDisponibles();
        break;
        
    case "listaAnios":
        $controller->obtenerListaAnios();
        break;
        
    // Validaciones
    case "verificarExistencia":
        $controller->verificarExistencia();
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