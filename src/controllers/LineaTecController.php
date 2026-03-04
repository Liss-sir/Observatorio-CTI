<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/LineaTecnologica.php";

class LineaTecnologicaController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new LineaTecnologicaModel($conn);
    }

    // List active technological lines
    public function listar() {
        $lineas = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $lineas
        ]);
    }

    // List all technological lines (for admin)
    public function listarTodas() {
        $lineas = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $lineas
        ]);
    }

    // Get a technological line by ID
    public function obtener($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de línea tecnológica requerido'
            ]);
            return;
        }
        $linea = $this->model->obtener($id);
        if ($linea) {
            echo json_encode([
                'success' => true,
                'data' => $linea
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Línea tecnológica no encontrada'
            ]);
        }
    }

    // Create a new technological line
    public function crear() {
        $input = json_decode(file_get_contents("php://input"), true);

        // Required fields validation
        $required = ['id_area', 'id_programa', 'id_etapa', 'id_tendencia', 'id_proyeccion'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                echo json_encode([
                    'success' => false,
                    'error' => "El campo $field es requerido"
                ]);
                return;
            }
        }

        $id = $this->model->crear($input);
        if ($id) {
            echo json_encode([
                'success' => true,
                'id_linea' => $id,
                'message' => 'Línea tecnológica creada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear la línea tecnológica'
            ]);
        }
    }

    // Update an existing technological line
    public function actualizar() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['id_linea'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de línea tecnológica requerido'
            ]);
            return;
        }

        $resultado = $this->model->actualizar($input);
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Línea tecnológica actualizada correctamente' : 'Error al actualizar la línea tecnológica'
        ]);
    }

    // Change state (activate/deactivate)
    public function cambiarEstado($id, $accion) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de línea tecnológica requerido'
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
            'message' => $resultado ? "Línea tecnológica {$accion}da correctamente" : "Error al {$accion} la línea tecnológica"
        ]);
    }

    // Delete a technological line (physical delete)
    public function eliminar($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de línea tecnológica requerido'
            ]);
            return;
        }

        $resultado = $this->model->eliminar($id);
        if ($resultado['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Línea tecnológica eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'Error al eliminar la línea tecnológica'
            ]);
        }
    }

    // Get lines by area
    public function obtenerPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        $lineas = $this->model->obtenerPorArea($id_area);
        echo json_encode([
            'success' => true,
            'data' => $lineas
        ]);
    }

    // Get lines by training program
    public function obtenerPorPrograma($id_programa) {
        if (!$id_programa) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de programa requerido'
            ]);
            return;
        }
        $lineas = $this->model->obtenerPorPrograma($id_programa);
        echo json_encode([
            'success' => true,
            'data' => $lineas
        ]);
    }

    // Get lines by development stage
    public function obtenerPorEtapa($id_etapa) {
        if (!$id_etapa) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de etapa requerido'
            ]);
            return;
        }
        $lineas = $this->model->obtenerPorEtapa($id_etapa);
        echo json_encode([
            'success' => true,
            'data' => $lineas
        ]);
    }

    // Get lines by emerging trend
    public function obtenerPorTendencia($id_tendencia) {
        if (!$id_tendencia) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de tendencia requerido'
            ]);
            return;
        }
        $lineas = $this->model->obtenerPorTendencia($id_tendencia);
        echo json_encode([
            'success' => true,
            'data' => $lineas
        ]);
    }

    // Get lines by future projection
    public function obtenerPorProyeccion($id_proyeccion) {
        if (!$id_proyeccion) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de proyección requerido'
            ]);
            return;
        }
        $lineas = $this->model->obtenerPorProyeccion($id_proyeccion);
        echo json_encode([
            'success' => true,
            'data' => $lineas
        ]);
    }

    // Get lines formatted for dropdown (id => descriptive text)
    public function obtenerParaSelect() {
        $lineas = $this->model->obtenerParaSelect();
        $options = [];
        foreach ($lineas as $id => $texto) {
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

    // Get lines for select filtered by area
    public function obtenerParaSelectPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        $lineas = $this->model->obtenerParaSelectPorArea($id_area);
        $options = [];
        foreach ($lineas as $id => $texto) {
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

    // Check if a technological line has dependencies (occupational profiles)
    public function verificarDependencias($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de línea tecnológica requerido'
            ]);
            return;
        }
        $tiene = $this->model->tieneDependencias($id);
        echo json_encode([
            'tiene_dependencias' => $tiene
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

    // Search lines by term (in related names)
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

    // Advanced search with filters
    public function buscarAvanzado() {
        $filtros = json_decode(file_get_contents("php://input"), true);
        $resultados = $this->model->buscarAvanzado($filtros ?? []);
        echo json_encode([
            'success' => true,
            'data' => $resultados
        ]);
    }
}

// --- Router ---
$accion = $_GET['accion'] ?? null;
$id = $_GET['id_linea'] ?? null;
$id_area = $_GET['id_area'] ?? null;
$id_programa = $_GET['id_programa'] ?? null;
$id_etapa = $_GET['id_etapa'] ?? null;
$id_tendencia = $_GET['id_tendencia'] ?? null;
$id_proyeccion = $_GET['id_proyeccion'] ?? null;

if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new LineaTecnologicaController($conn);

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

    // Filters by foreign keys
    case "porArea":
        $controller->obtenerPorArea($id_area);
        break;
    case "porPrograma":
        $controller->obtenerPorPrograma($id_programa);
        break;
    case "porEtapa":
        $controller->obtenerPorEtapa($id_etapa);
        break;
    case "porTendencia":
        $controller->obtenerPorTendencia($id_tendencia);
        break;
    case "porProyeccion":
        $controller->obtenerPorProyeccion($id_proyeccion);
        break;

    // Utilities
    case "paraSelect":
        $controller->obtenerParaSelect();
        break;
    case "paraSelectPorArea":
        $controller->obtenerParaSelectPorArea($id_area);
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
    case "buscarAvanzado":
        $controller->buscarAvanzado();
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}