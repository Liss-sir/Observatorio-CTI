<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/PerfilOcupacional.php";
require_once __DIR__ . "/../helpers/permisos.php";

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
        verificarPermiso('crear_perfil');
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

    // Search profiles for term (simple)
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

    // Acepta parámetro 'q' en la URL para combinar búsqueda textual con filtros JSON
    public function buscarAvanzado() {
        // Leer filtros del cuerpo JSON
        $input = json_decode(file_get_contents("php://input"), true);
        $filtros = $input ?? [];
        
        // Leer término de búsqueda de la query string (si existe)
        $termino = $_GET['q'] ?? null;
        
        $resultados = $this->model->buscarAvanzado($filtros, $termino);
        
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

    // List all active technological lines
    public function listarLineasTecnologicas() {
        $lineas = $this->model->listarLineasTecnologicas();
        
        echo json_encode([
            'success' => true,
            'data' => $lineas
        ]);
    }

    // List programs filtered by technological line
    public function listarProgramasPorLinea() {
        $id_linea = $_GET['id_linea'] ?? null;
        
        if (!$id_linea) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de línea tecnológica requerido'
            ]);
            return;
        }
        
        $programas = $this->model->listarProgramasPorLinea($id_linea);
        
        echo json_encode([
            'success' => true,
            'data' => $programas
        ]);
    }

    // List all active training levels
    public function listarNivelesFormacion() {
        $niveles = $this->model->listarNivelesFormacion();
        
        echo json_encode([
            'success' => true,
            'data' => $niveles
        ]);
    }

    // Get a specific technological line by ID
    public function obtenerLineaTecnologica($id_linea) {
        if (!$id_linea) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de línea tecnológica requerido'
            ]);
            return;
        }
        
        $linea = $this->model->obtenerLineaTecnologica($id_linea);
        
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

    // Get a specific training program by ID
    public function obtenerProgramaFormacion($id_programa) {
        if (!$id_programa) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de programa requerido'
            ]);
            return;
        }
        
        $programa = $this->model->obtenerProgramaFormacion($id_programa);
        
        if ($programa) {
            echo json_encode([
                'success' => true,
                'data' => $programa
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Programa de formación no encontrado'
            ]);
        }
    }

    // Get a specific training level by ID
    public function obtenerNivelFormacion($id_nivel) {
        if (!$id_nivel) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de nivel requerido'
            ]);
            return;
        }
        
        $nivel = $this->model->obtenerNivelFormacion($id_nivel);
        
        if ($nivel) {
            echo json_encode([
                'success' => true,
                'data' => $nivel
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Nivel de formación no encontrado'
            ]);
        }
    }

    // List all emerging technologies
    public function listarTecnologiasEmergentes() {
        $tecnologias = $this->model->listarTecnologiasEmergentes();
        
        echo json_encode([
            'success' => true,
            'data' => $tecnologias
        ]);
    }

    // List all current trends
    public function listarTendenciasActuales() {
        $tendencias = $this->model->listarTendenciasActuales();
        
        echo json_encode([
            'success' => true,
            'data' => $tendencias
        ]);
    }

    // List all future projections
    public function listarProyeccionesFuturo() {
        $proyecciones = $this->model->listarProyeccionesFuturo();
        
        echo json_encode([
            'success' => true,
            'data' => $proyecciones
        ]);
    }

    // Get a specific emerging technology by ID
    public function obtenerTecnologiaEmergente($id_tendencia) {
        if (!$id_tendencia) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de tecnología emergente requerido'
            ]);
            return;
        }
        
        $tecnologia = $this->model->obtenerTecnologiaEmergente($id_tendencia);
        
        if ($tecnologia) {
            echo json_encode([
                'success' => true,
                'data' => $tecnologia
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Tecnología emergente no encontrada'
            ]);
        }
    }

    // Get a specific future projection by ID
    public function obtenerProyeccionFuturo($id_proyeccion) {
        if (!$id_proyeccion) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de proyección requerido'
            ]);
            return;
        }
        
        $proyeccion = $this->model->obtenerProyeccionFuturo($id_proyeccion);
        
        if ($proyeccion) {
            echo json_encode([
                'success' => true,
                'data' => $proyeccion
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Proyección a futuro no encontrada'
            ]);
        }
    }

    // Disable profiles that exceed 12 months of validity
    public function deshabilitarPerfilesVencidos() {
        $cantidad = $this->model->deshabilitarPerfilesVencidos();
        
        echo json_encode([
            'success' => true,
            'cantidad_deshabilitados' => $cantidad,
            'message' => "Se deshabilitaron $cantidad perfiles que superaron los 12 meses de vigencia"
        ]);
    }

    // Get profiles that are about to expire
    public function obtenerPerfilesProximosVencer() {
        $meses = $_GET['meses'] ?? 1;
        
        $perfiles = $this->model->obtenerPerfilesProximosVencer($meses);
        
        echo json_encode([
            'success' => true,
            'data' => $perfiles,
            'total' => count($perfiles)
        ]);
    }

    // Get statistics of validity of profiles
    public function obtenerEstadisticasVigencia() {
        $estadisticas = $this->model->obtenerEstadisticasVigencia();
        
        echo json_encode([
            'success' => true,
            'data' => $estadisticas
        ]);
    }

    // Get statistics for profiles (general)
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        
        echo json_encode([
            'success' => true,
            'data' => $estadisticas
        ]);
    }

}

// ==================== ROUTER ====================
$accion = $_GET['accion'] ?? null;
$id = $_GET['id_perfil'] ?? null;
$id_usuario = $_GET['id_usuario'] ?? null;
$id_linea = $_GET['id_linea'] ?? null;
$id_programa = $_GET['id_programa'] ?? null;
$id_nivel = $_GET['id_nivel'] ?? null;
$id_tendencia = $_GET['id_tendencia'] ?? null;
$id_proyeccion = $_GET['id_proyeccion'] ?? null;

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

    case "listarLineasTecnologicas":
        $controller->listarLineasTecnologicas();
        break;
        
    case "listarProgramasPorLinea":
        $controller->listarProgramasPorLinea();
        break;
        
    case "listarNivelesFormacion":
        $controller->listarNivelesFormacion();
        break;
        
    case "obtenerLineaTecnologica":
        $controller->obtenerLineaTecnologica($id_linea);
        break;
        
    case "obtenerProgramaFormacion":
        $controller->obtenerProgramaFormacion($id_programa);
        break;
        
    case "obtenerNivelFormacion":
        $controller->obtenerNivelFormacion($id_nivel);
        break;
    
    case "listarTecnologiasEmergentes":
        $controller->listarTecnologiasEmergentes();
        break;
        
    case "listarTendenciasActuales":
        $controller->listarTendenciasActuales();
        break;
        
    case "listarProyeccionesFuturo":
        $controller->listarProyeccionesFuturo();
        break;
        
    case "obtenerTecnologiaEmergente":
        $controller->obtenerTecnologiaEmergente($id_tendencia);
        break;
        
    case "obtenerProyeccionFuturo":
        $controller->obtenerProyeccionFuturo($id_proyeccion);
        break;
        
    case "deshabilitarPerfilesVencidos":
        $controller->deshabilitarPerfilesVencidos();
        break;
        
    case "obtenerPerfilesProximosVencer":
        $controller->obtenerPerfilesProximosVencer();
        break;
        
    case "obtenerEstadisticasVigencia":
        $controller->obtenerEstadisticasVigencia();
        break;
        

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}