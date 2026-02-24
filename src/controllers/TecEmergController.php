<?php

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/TecEmergentes.php";

class TecnoEmergController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new TecnoEmergentes($conn);
    }

    /**
     * POST /crear
     * Espera JSON con nombre y descripcion
     */
    public function crear() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['nombre']) || empty(trim($input['nombre']))) {
            echo json_encode([
                'success' => false,
                'error' => 'El nombre es requerido'
            ]);
            return;
        }

        $nombre = trim($input['nombre']);
        $descripcion = isset($input['descripcion']) ? trim($input['descripcion']) : '';

        $id = $this->model->crear($nombre, $descripcion);
        if ($id) {
            echo json_encode([
                'success' => true,
                'message' => 'Tendencia creada correctamente',
                'id' => $id
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear la tendencia'
            ]);
        }
    }

    /**
     * GET /listar
     * Devuelve todas las tendencias activas
     */
    public function listar() {
        $lista = $this->model->listar();
        echo json_encode([
            'success' => true,
            'data' => $lista
        ]);
    }

    public function listarInactivas() {
        $lista = $this->model->listarInactivas();
        echo json_encode([
            'success' => true,
            'data' => $lista
        ]);
    }

    /**
     * GET /visualizar?id=XX
     * Devuelve una tendencia por ID
     */
    public function visualizar() {
        $id = 0;
        if (isset($_GET['id_tendencia'])) {
            $id = intval($_GET['id_tendencia']);
        } elseif (isset($_GET['id'])) {
            $id = intval($_GET['id']);
        }
        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'ID inválido'
            ]);
            return;
        }

        $tendencia = $this->model->obtenerPorId($id);
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

    /**
     * POST /editar
     * Espera JSON con id, nombre, descripcion
     */
    public function editar() {
        $input = json_decode(file_get_contents("php://input"), true);

        $id = 0;
        if (isset($input['id_tendencia'])) {
            $id = intval($input['id_tendencia']);
        } elseif (isset($input['id'])) {
            $id = intval($input['id']);
        }
        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'ID válido requerido'
            ]);
            return;
        }

        if (!isset($input['nombre']) || empty(trim($input['nombre']))) {
            echo json_encode([
                'success' => false,
                'error' => 'El nombre es requerido'
            ]);
            return;
        }

        $nombre = trim($input['nombre']);
        $descripcion = isset($input['descripcion']) ? trim($input['descripcion']) : '';

        // Verificar que la tendencia existe
        $existe = $this->model->obtenerPorId($id);
        if (!$existe) {
            echo json_encode([
                'success' => false,
                'error' => 'La tendencia no existe'
            ]);
            return;
        }

        $ok = $this->model->actualizar($id, $nombre, $descripcion);
        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Tendencia actualizada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al actualizar la tendencia'
            ]);
        }
    }

    /**
     * POST /inactivar
     * Espera JSON con id
     */
    public function activar() {
        $input = json_decode(file_get_contents("php://input"), true);

        $id = 0;
        if (isset($input['id_tendencia'])) {
            $id = intval($input['id_tendencia']);
        } elseif (isset($input['id'])) {
            $id = intval($input['id']);
        }
        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'ID válido requerido'
            ]);
            return;
        }

        // Verificar existencia y estado actual
        $existe = $this->model->obtenerPorId($id);
        if (!$existe) {
            echo json_encode([
                'success' => false,
                'error' => 'La tendencia no existe'
            ]);
            return;
        }

        if ($existe['estado'] == 1) {
            echo json_encode([
                'success' => false,
                'error' => 'La tendencia ya está activa'
            ]);
            return;
        }

        $ok = $this->model->activar($id);
        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Tendencia activada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al activar la tendencia'
            ]);
        }
    }

    public function inactivar() {
        $input = json_decode(file_get_contents("php://input"), true);

        $id = 0;
        if (isset($input['id_tendencia'])) {
            $id = intval($input['id_tendencia']);
        } elseif (isset($input['id'])) {
            $id = intval($input['id']);
        }
        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'ID válido requerido'
            ]);
            return;
        }

        // Verificar existencia y estado actual
        $existe = $this->model->obtenerPorId($id);
        if (!$existe) {
            echo json_encode([
                'success' => false,
                'error' => 'La tendencia no existe'
            ]);
            return;
        }

        if ($existe['estado'] == 0) {
            echo json_encode([
                'success' => false,
                'error' => 'La tendencia ya está inactiva'
            ]);
            return;
        }

        $ok = $this->model->inactivar($id);
        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Tendencia inactivada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al activar la tendencia'
            ]);
        }
    }
}

// ================= ROUTER =================

$accion = $_GET['accion'] ?? null;

if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new TecnoEmergController($conn);

switch ($accion) {
    case 'crear':
        $controller->crear();
        break;

    case 'listar':
        $controller->listar();
        break;
    
    case 'listarInactivas':
        $controller->listarInactivas();
        break;

    case 'visualizar':
        $controller->visualizar();
        break;

    case 'editar':
        $controller->editar();
        break;

    case 'inactivar':
        $controller->inactivar();
        break;
    
    case 'activar':
        $controller->activar();
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}