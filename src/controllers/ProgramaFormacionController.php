<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/ProgramaFormacion.php";

class ProgramaFormacionController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new ProgramaFormacionModel($conn);
    }

    // List programs active
    public function listar() {
        $programas = $this->model->listar();
        echo json_encode([
            'status' => 'success',
            'data' => $programas
        ]);
    }

    // List all programs (For admin)
    public function listarTodas() {
        $programas = $this->model->listarTodas();
        echo json_encode([
            'status' => 'success',
            'data' => $programas
        ]);
    }

    // Get program for ID
    public function obtener($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de programa requerido'
            ]);
            return;
        }
        
        $programa = $this->model->obtener($id);
        
        if ($programa) {
            echo json_encode([
                'success' => true,
                'data' => $programa
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Programa no encontrado'
            ]);
        }
    }

    // Create new program
    public function crear() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        // Basic validations
        if (empty($input['id_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El área es requerida'
            ]);
            return;
        }
        
        if (empty($input['codigo_programa'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El código del programa es requerido'
            ]);
            return;
        }
        
        if (empty($input['nombre_programa'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El nombre del programa es requerido'
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
        
        if (empty($input['fecha_creacion'])) {
            echo json_encode([
                'success' => false,
                'error' => 'La fecha de inicio es requerida'
            ]);
            return;
        }
        
        if (empty($input['fecha_fin'])) {
            echo json_encode([
                'success' => false,
                'error' => 'La fecha de fin es requerida'
            ]);
            return;
        }
        
        // Verify unique code
        if ($this->model->codigoExiste($input['codigo_programa'])) {
            echo json_encode([
                'success' => false,
                'error' => 'El código del programa ya está registrado'
            ]);
            return;
        }
        
        // Verify unique name in area
        if ($this->model->nombreExisteEnArea($input['nombre_programa'], $input['id_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Ya existe un programa con ese nombre en el área seleccionada'
            ]);
            return;
        }
        
        // Validate dates
        $errores_fechas = $this->model->validarFechas($input['fecha_creacion'], $input['fecha_fin']);
        if (!empty($errores_fechas)) {
            echo json_encode([
                'success' => false,
                'error' => $errores_fechas[0]
            ]);
            return;
        }
        
        $id = $this->model->crear($input);
        
        if ($id) {
            echo json_encode([
                'success' => true,
                'id_programa' => $id,
                'message' => 'Programa creado correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al crear el programa'
            ]);
        }
    }

    // Update program exist
    public function actualizar() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['id_programa'])) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de programa requerido'
            ]);
            return;
        }
        
        // Conditional validations
        if (isset($input['codigo_programa'])) {
            if ($this->model->codigoExiste($input['codigo_programa'], $input['id_programa'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El código del programa ya está registrado'
                ]);
                return;
            }
        }
        
        if (isset($input['nombre_programa']) && isset($input['id_area'])) {
            if (empty(trim($input['nombre_programa']))) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El nombre del programa no puede estar vacío'
                ]);
                return;
            }
            
            if ($this->model->nombreExisteEnArea($input['nombre_programa'], $input['id_area'], $input['id_programa'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Ya existe un programa con ese nombre en el área seleccionada'
                ]);
                return;
            }
        }
        
        // Validate dates if they are being updated
        if (isset($input['fecha_creacion']) && isset($input['fecha_fin'])) {
            $errores_fechas = $this->model->validarFechas($input['fecha_creacion'], $input['fecha_fin']);
            if (!empty($errores_fechas)) {
                echo json_encode([
                    'success' => false,
                    'error' => $errores_fechas[0]
                ]);
                return;
            }
        }
        
        $resultado = $this->model->actualizar($input);
        
        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Programa actualizado correctamente' : 'Error al actualizar el programa'
        ]);
    }

    // Change state in programs
    public function cambiarEstado($id, $accion) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de programa requerido'
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
            'message' => $resultado ? "Programa {$accion}do correctamente" : "Error al {$accion} el programa"
        ]);
    }

    // Delete programs
    public function eliminar($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de programa requerido'
            ]);
            return;
        }
        
        $resultado = $this->model->eliminar($id);
        
        if ($resultado['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Programa eliminado correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'Error al eliminar el programa'
            ]);
        }
    }

    // Get programs for area
    public function obtenerPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $programas = $this->model->obtenerPorArea($id_area);
        
        echo json_encode([
            'success' => true,
            'data' => $programas
        ]);
    }

    // Get programs for select by area
    public function obtenerParaSelectPorArea($id_area) {
        if (!$id_area) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de área requerido'
            ]);
            return;
        }
        
        $programas = $this->model->obtenerParaSelectPorArea($id_area);
        
        $options = [];
        foreach ($programas as $id => $nombre) {
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

    // Get programs for level
    public function obtenerPorNivel($id_nivel) {
        if (!$id_nivel) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de nivel requerido'
            ]);
            return;
        }
        
        $programas = $this->model->obtenerPorNivel($id_nivel);
        
        echo json_encode([
            'success' => true,
            'data' => $programas
        ]);
    }

    // Get programs for modality
    public function obtenerPorModalidad($modalidad) {
        if (!$modalidad) {
            echo json_encode([
                'success' => false,
                'error' => 'Modalidad requerida'
            ]);
            return;
        }
        
        $programas = $this->model->obtenerPorModalidad($modalidad);
        
        echo json_encode([
            'success' => true,
            'data' => $programas
        ]);
    }

    // Get modalities available
    public function obtenerModalidades() {
        $modalidades = $this->model->obtenerModalidades();
        
        $options = [];
        foreach ($modalidades as $value => $label) {
            $options[] = [
                'value' => $value,
                'text' => $label
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $options
        ]);
    }

    // Get programs active in current date
    public function obtenerActivosEnFecha() {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $programas = $this->model->obtenerActivosEnFecha($fecha);
        
        echo json_encode([
            'success' => true,
            'data' => $programas
        ]);
    }

    // Get programs soon to end
    public function obtenerProximosAFinalizar() {
        $dias = $_GET['dias'] ?? 30;
        $programas = $this->model->obtenerProximosAFinalizar((int)$dias);
        
        echo json_encode([
            'success' => true,
            'data' => $programas
        ]);
    }

    // Verify if the code exists
    public function verificarCodigo() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['codigo_programa'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Código de programa requerido'
            ]);
            return;
        }
        
        $existe = $this->model->codigoExiste(
            $input['codigo_programa'],
            $input['excluir_id'] ?? null
        );
        
        echo json_encode([
            'existe' => $existe
        ]);
    }

    // Verify if the name exists in area
    public function verificarNombreEnArea() {
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['nombre_programa']) || !isset($input['id_area'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Nombre y área son requeridos'
            ]);
            return;
        }
        
        $existe = $this->model->nombreExisteEnArea(
            $input['nombre_programa'],
            $input['id_area'],
            $input['excluir_id'] ?? null
        );
        
        echo json_encode([
            'existe' => $existe
        ]);
    }

    // Verify if program has dependences
    public function verificarDependencias($id) {
        if (!$id) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de programa requerido'
            ]);
            return;
        }
        
        $tieneDependencias = $this->model->tieneDependencias($id);
        
        echo json_encode([
            'tiene_dependencias' => $tieneDependencias
        ]);
    }

    // Get statistics for programs
    public function obtenerEstadisticas() {
        $estadisticas = $this->model->obtenerEstadisticas();
        echo json_encode([
            'status' => 'success',
            'data' => $estadisticas
        ]);
    }

    // Search programs for term
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

    // Get programs for select (all)
    public function obtenerParaSelect() {
        $programas = $this->model->obtenerParaSelect();
        
        $options = [];
        foreach ($programas as $id => $nombre) {
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

    // Get programs with technological lines
    public function obtenerConLineasTecnologicas() {
        $programas = $this->model->obtenerConLineasTecnologicas();
        
        echo json_encode([
            'success' => true,
            'data' => $programas
        ]);
    }

    // Get statistics of programs vs profiles
    public function estadisticasVsPerfiles() {
        try {
            $sql = "SELECT 
                        p.id_programa,
                        p.codigo_programa,
                        p.nombre_programa,
                        a.nombre_area,
                        n.nombre_nivel,
                        COUNT(po.id_perfil) AS total_perfiles_asociados,
                        IFNULL(SUM(po.cupos), 0) AS total_cupos_ofertados
                    FROM programas_formacion p
                    INNER JOIN areas a ON p.id_area = a.id_area
                    INNER JOIN niveles_formacion n ON p.id_nivel = n.id_nivel
                    LEFT JOIN perfiles_ocupacionales po ON p.id_programa = po.id_programa AND po.estado = 1
                    WHERE p.estado = 1
                    GROUP BY p.id_programa
                    ORDER BY total_perfiles_asociados DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $resultados
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Error al obtener estadísticas'
            ]);
        }
    }
}


$accion = $_GET['accion'] ?? null;
$id = $_GET['id_programa'] ?? null;
$id_area = $_GET['id_area'] ?? null;
$id_nivel = $_GET['id_nivel'] ?? null;
$modalidad = $_GET['modalidad'] ?? null;

// Verify the conexion exist
if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new ProgramaFormacionController($conn);

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
    
    // Methods by area
    case "porArea":
        $controller->obtenerPorArea($id_area);
        break;
        
    case "paraSelectPorArea":
        $controller->obtenerParaSelectPorArea($id_area);
        break;
    
    // Methods by level
    case "porNivel":
        $controller->obtenerPorNivel($id_nivel);
        break;
    
    // Methods by modality
    case "porModalidad":
        $controller->obtenerPorModalidad($modalidad);
        break;
        
    case "modalidades":
        $controller->obtenerModalidades();
        break;
    
    // Methods by dates
    case "activosEnFecha":
        $controller->obtenerActivosEnFecha();
        break;
        
    case "proximosAFinalizar":
        $controller->obtenerProximosAFinalizar();
        break;
    
    // Validations
    case "verificarCodigo":
        $controller->verificarCodigo();
        break;
        
    case "verificarNombreEnArea":
        $controller->verificarNombreEnArea();
        break;
        
    case "verificarDependencias":
        $controller->verificarDependencias($id);
        break;
        
    // Statistics
    case "estadisticas":
        $controller->obtenerEstadisticas();
        break;
        
    // Search and utils
    case "buscar":
        $controller->buscar();
        break;
        
    case "buscarAvanzado":
        $controller->buscarAvanzado();
        break;
        
    case "paraSelect":
        $controller->obtenerParaSelect();
        break;
        
    case "conLineasTecnologicas":
        $controller->obtenerConLineasTecnologicas();
        break;

    case "estadisticasVsPerfiles":
        $controller->estadisticasVsPerfiles();
        break;

    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}