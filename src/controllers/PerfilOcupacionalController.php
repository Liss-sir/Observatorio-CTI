<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/PerfilOcupacional.php";

class PerfilOcupacionalController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new PerfilOcupacionalModel($conn);
    }

    // List profiles active
    public function listar() {
        $perfiles = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $perfiles
        ]);
    }

    // List all profiles (For admin)
    public function listarTodas() {
        $perfiles = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $perfiles
        ]);
    }

    // Get profile for ID
    public function obtener($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de perfil requerido'
            ]);
            return;
        }
        
        $perfil = $this->model->obtener($id);
        
        if ($perfil) {
            echo json_encode([
                'success' => true,
                'data' => $perfil
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Perfil no encontrado'
            ]);
        }
    }

    // Get profiles for user ID
    public function obtenerPorUsuario($id_usuario) {
        if (!$id_usuario) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de usuario requerido'
            ]);
            return;
        }
        
        $perfiles = $this->model->obtenerPorUsuario($id_usuario);
        
        echo json_encode([
            'success' => true,
            'data' => $perfiles
        ]);
    }

    // Create new profile
    public function crear() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        // Basic validations
        if (empty($input['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El usuario es requerido'
            ]);
            return;
        }
        
        if (empty($input['id_linea'])) {
            echo json_encode([
                'success' => false,
                'error' => 'La línea tecnológica es requerida'
            ]);
            return;
        }
        
        if (empty($input['id_programa'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El programa de formación es requerido'
            ]);
            return;
        }
        
        if (empty($input['id_nivel'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El nivel de formación es requerido'
            ]);
            return;
        }
        
        if (!isset($input['cupos']) || $input['cupos'] < 0) {
            echo json_encode([
                'success' => false,
                'error' => 'La cantidad de cupos es requerida y debe ser mayor o igual a 0'
            ]);
            return;
        }
        
        if (empty($input['nombre'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El nombre del perfil es requerido'
            ]);
            return;
        }
        
        // Verify if profile name exists for this user
        if ($this->model->nombreExisteParaUsuario($input['nombre'], $input['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Ya existe un perfil con ese nombre para este usuario'
            ]);
            return;
        }
        
        // Verify if line and program are compatible
        if (!$this->model->verificarLineaPrograma($input['id_linea'], $input['id_programa'])) {
            echo json_encode([
                'success' => false,
                'error' => 'La línea tecnológica y el programa de formación no son compatibles'
            ]);
            return;
        }
        
        $id = $this->model->crear($input);
        
        if ($id) {
            echo json_encode([
                'success' => true,
                'id_perfil' => $id,
                'message' => 'Perfil ocupacional creado correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear el perfil ocupacional'
            ]);
        }
    }

    // Update profile exist
    public function actualizar() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_perfil'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de perfil requerido'
            ]);
            return;
        }
        
        // Get current profile to check user
        $perfil_actual = $this->model->obtener($input['id_perfil']);
        if (!$perfil_actual) {
            echo json_encode([
                'success' => false,
                'error' => 'Perfil no encontrado'
            ]);
            return;
        }
        
        // Conditional validations
        if (isset($input['nombre']) && isset($perfil_actual['id_usuario'])) {
            if (empty(trim($input['nombre']))) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El nombre del perfil no puede estar vacío'
                ]);
                return;
            }
            
            if ($this->model->nombreExisteParaUsuario($input['nombre'], $perfil_actual['id_usuario'], $input['id_perfil'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Ya existe un perfil con ese nombre para este usuario'
                ]);
                return;
            }
        }
        
        // Verify line and program compatibility if both are being updated
        if (isset($input['id_linea']) && isset($input['id_programa'])) {
            if (!$this->model->verificarLineaPrograma($input['id_linea'], $input['id_programa'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'La línea tecnológica y el programa de formación no son compatibles'
                ]);
                return;
            }
        }
        
        $resultado = $this->model->actualizar($input);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Perfil actualizado correctamente' : 'Error al actualizar el perfil'
        ]);
    }

    // Change state in profiles
    public function cambiarEstado($id, $accion) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de perfil requerido'
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
            'message' => $resultado ? "Perfil {$accion}do correctamente" : "Error al {$accion} el perfil"
        ]);
    }

    // Delete profiles
    public function eliminar($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de perfil requerido'
            ]);
            return;
        }
        
        $resultado = $this->model->eliminar($id);
        
        if ($resultado['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Perfil eliminado correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'Error al eliminar el perfil'
            ]);
        }
    }

    // Get profiles for technological line
    public function obtenerPorLinea($id_linea) {
        if (!$id_linea) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de línea tecnológica requerido'
            ]);
            return;
        }
        
        $perfiles = $this->model->obtenerPorLinea($id_linea);
        
        echo json_encode([
            'success' => true,
            'data' => $perfiles
        ]);
    }

    // Get profiles for formation program
    public function obtenerPorPrograma($id_programa) {
        if (!$id_programa) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de programa requerido'
            ]);
            return;
        }
        
        $perfiles = $this->model->obtenerPorPrograma($id_programa);
        
        echo json_encode([
            'success' => true,
            'data' => $perfiles
        ]);
    }

    // Get profiles for formation level
    public function obtenerPorNivel($id_nivel) {
        if (!$id_nivel) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de nivel requerido'
            ]);
            return;
        }
        
        $perfiles = $this->model->obtenerPorNivel($id_nivel);
        
        echo json_encode([
            'success' => true,
            'data' => $perfiles
        ]);
    }

    // Search profiles for term
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

    // Verify if profile name exists for user
    public function verificarNombreExistente() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['nombre']) || !isset($input['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Nombre y usuario son requeridos'
            ]);
            return;
        }
        
        $existe = $this->model->nombreExisteParaUsuario(
            $input['nombre'],
            $input['id_usuario'],
            $input['excluir_id'] ?? null
        );
        
        echo json_encode([
            'existe' => $existe
        ]);
    }

    // Verify if line and program are compatible
    public function verificarLineaPrograma() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_linea']) || !isset($input['id_programa'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Línea y programa son requeridos'
            ]);
            return;
        }
        
        $compatible = $this->model->verificarLineaPrograma($input['id_linea'], $input['id_programa']);
        
        echo json_encode([
            'compatible' => $compatible
        ]);
    }

    // Get statistics for profiles
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Get profiles for select by user
    public function obtenerParaSelectPorUsuario($id_usuario) {
        if (!$id_usuario) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de usuario requerido'
            ]);
            return;
        }
        
        $perfiles = $this->model->obtenerParaSelectPorUsuario($id_usuario);
        
        $options = [];
        foreach ($perfiles as $id => $nombre) {
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

    // Get profiles with available spots
    public function obtenerConCuposDisponibles() {
        $perfiles = $this->model->obtenerConCuposDisponibles();
        
        echo json_encode([
            'success' => true,
            'data' => $perfiles
        ]);
    }

    // Update spots
    public function actualizarCupos() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_perfil']) || !isset($input['cupos'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de perfil y cupos son requeridos'
            ]);
            return;
        }
        
        if ($input['cupos'] < 0) {
            echo json_encode([
                'success' => false,
                'error' => 'Los cupos deben ser mayor o igual a 0'
            ]);
            return;
        }
        
        $resultado = $this->model->actualizarCupos($input['id_perfil'], $input['cupos']);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Cupos actualizados correctamente' : 'Error al actualizar los cupos'
        ]);
    }

    // Decrease spot by one
    public function disminuirCupo($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de perfil requerido'
            ]);
            return;
        }
        
        $resultado = $this->model->disminuirCupo($id);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Cupo disminuido correctamente' : 'No hay cupos disponibles'
        ]);
    }
}


$accion = $_GET['accion'] ?? null;
$id = $_GET['id_perfil'] ?? null;
$id_usuario = $_GET['id_usuario'] ?? null;
$id_linea = $_GET['id_linea'] ?? null;
$id_programa = $_GET['id_programa'] ?? null;
$id_nivel = $_GET['id_nivel'] ?? null;

// Verify the conexion exist
if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new PerfilOcupacionalController($conn);

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
        
    case "obtenerPorUsuario":
        $controller->obtenerPorUsuario($id_usuario);
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
    
    // Methods by filters
    case "porLinea":
        $controller->obtenerPorLinea($id_linea);
        break;
        
    case "porPrograma":
        $controller->obtenerPorPrograma($id_programa);
        break;
        
    case "porNivel":
        $controller->obtenerPorNivel($id_nivel);
        break;
    
    // Search
    case "buscar":
        $controller->buscar();
        break;
        
    case "buscarAvanzado":
        $controller->buscarAvanzado();
        break;
        
    // Validations
    case "verificarNombre":
        $controller->verificarNombreExistente();
        break;
        
    case "verificarLineaPrograma":
        $controller->verificarLineaPrograma();
        break;
        
    // Statistics
    case "estadisticas":
        $controller->obtenerEstadisticas();
        break;
        
    // Additional methods
    case "paraSelectPorUsuario":
        $controller->obtenerParaSelectPorUsuario($id_usuario);
        break;
        
    case "conCuposDisponibles":
        $controller->obtenerConCuposDisponibles();
        break;
        
    case "actualizarCupos":
        $controller->actualizarCupos();
        break;
        
    case "disminuirCupo":
        $controller->disminuirCupo($id);
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}