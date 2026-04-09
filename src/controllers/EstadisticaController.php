<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Estadisticas.php";
require_once __DIR__ . "/../helpers/permisos.php";

class EstadisticasController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new EstadisticasModel($conn);
    }

    public function listar() {
        verificarPermiso('ver_estadisticas');
        
        $data = $this->model->obtenerEstadisticasCompletas();
        
        if (isset($data['error'])) {
            echo json_encode([
                'success' => false,
                'error' => $data['error']
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        }
    }

    public function comparativaPerfilesVsOfertas() {
        verificarPermiso('ver_estadisticas');
        
        // Parámetros opcionales de filtro por mes y año
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : null;
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : null;
        
        // Validaciones básicas
        if ($mes !== null && ($mes < 1 || $mes > 12)) {
            echo json_encode([
                'success' => false,
                'error' => 'El mes debe estar entre 1 y 12'
            ]);
            return;
        }
        
        if ($anio !== null && ($anio < 2000 || $anio > 2100)) {
            echo json_encode([
                'success' => false,
                'error' => 'Año inválido'
            ]);
            return;
        }
        
        $data = $this->model->obtenerComparativaPerfilesVsOfertas($mes, $anio);
        
        if (isset($data['error'])) {
            echo json_encode([
                'success' => false,
                'error' => $data['error']
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function distribucionPorLineaTecnologica() {
        verificarPermiso('ver_estadisticas');
        
        $id_area = isset($_GET['id_area']) ? (int)$_GET['id_area'] : null;
        
        $data = $this->model->obtenerDistribucionPorLineaTecnologica($id_area);
        
        if (isset($data['error'])) {
            echo json_encode([
                'success' => false,
                'error' => $data['error']
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function estadisticasCompletas() {
        verificarPermiso('ver_estadisticas');
        
        $data = $this->model->obtenerEstadisticasCompletas();
        
        if (isset($data['error'])) {
            echo json_encode([
                'success' => false,
                'error' => $data['error']
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function tendenciasTemporales() {
        verificarPermiso('ver_estadisticas');
        
        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'mes';
        
        if (!in_array($periodo, ['mes', 'trimestre', 'año'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Periodo no válido. Use: mes, trimestre, año'
            ]);
            return;
        }
        
        $data = $this->model->obtenerTendenciasTemporales($periodo);
        
        if (isset($data['error'])) {
            echo json_encode([
                'success' => false,
                'error' => $data['error']
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function estadisticasPorNivel() {
        verificarPermiso('ver_estadisticas');
        
        $data = $this->model->obtenerEstadisticasPorNivel();
        
        if (isset($data['error'])) {
            echo json_encode([
                'success' => false,
                'error' => $data['error']
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    public function exportarCSV() {
        verificarPermiso('ver_estadisticas');
        
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'comparativa';
        
        switch ($tipo) {
            case 'comparativa':
                $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : null;
                $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : null;
                $data = $this->model->obtenerComparativaPerfilesVsOfertas($mes, $anio);
                $this->generarCSVComparativa($data);
                break;
            case 'lineas':
                $data = $this->model->obtenerDistribucionPorLineaTecnologica();
                $this->generarCSVLineas($data);
                break;
            case 'niveles':
                $data = $this->model->obtenerEstadisticasPorNivel();
                $this->generarCSVNiveles($data);
                break;
            default:
                echo json_encode([
                    'success' => false,
                    'error' => 'Tipo de exportación no válido'
                ]);
        }
    }

    private function generarCSVComparativa($data) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="comparativa_perfiles_ofertas.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Área', 'Perfiles Demandados', 'Programas Demandados', 
                          'Cupos Demandados', 'Programas Ofertados', 'Cupos Ofertados',
                          'Niveles Formación']);
        
        foreach ($data['por_area'] as $area) {
            fputcsv($output, [
                $area['nombre_area'],
                $area['necesidades_empresariales']['total_perfiles'],
                $area['necesidades_empresariales']['programas_demandados'],
                $area['necesidades_empresariales']['cupos_demandados'],
                $area['ofertas_formacion']['total_programas'],
                $area['ofertas_formacion']['cupos_ofertados'],
                $area['ofertas_formacion']['niveles_formacion']
            ]);
        }
        
        fputcsv($output, ['TOTALES', 
                          $data['totales']['total_perfiles'],
                          '',
                          $data['totales']['total_necesidades'],
                          $data['totales']['total_programas'],
                          $data['totales']['total_ofertas'],
                          '']);
        
        fclose($output);
    }

    private function generarCSVLineas($data) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="distribucion_lineas_tecnologicas.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Línea Tecnológica', 'Área', 'Programa Formación', 
                          'Total Perfiles', 'Cupos Solicitados', 'Empresas que Solicitan',
                          'Niveles Demandados', '% Perfiles', '% Cupos']);
        
        foreach ($data['distribucion'] as $linea) {
            fputcsv($output, [
                $linea['nombre_linea'],
                $linea['area']['nombre_area'],
                $linea['programa']['nombre_programa'],
                $linea['estadisticas']['total_perfiles'],
                $linea['estadisticas']['total_cupos_solicitados'],
                $linea['estadisticas']['empresas_que_solicitan'],
                $linea['estadisticas']['niveles_demandados'],
                $linea['porcentaje_perfiles'] . '%',
                $linea['porcentaje_cupos'] . '%'
            ]);
        }
        
        fclose($output);
    }

    private function generarCSVNiveles($data) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="estadisticas_niveles_formacion.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Nivel Formación', 'Perfiles Demandados', 'Cupos Demandados',
                          'Programas Ofertados', 'Cupos Ofertados']);
        
        foreach ($data['niveles'] as $nivel) {
            fputcsv($output, [
                $nivel['nombre_nivel'],
                $nivel['total_perfiles'],
                $nivel['total_cupos_demandados'],
                $nivel['total_programas_ofertados'],
                $nivel['total_cupos_ofertados']
            ]);
        }
        
        fclose($output);
    }
}

$accion = $_GET['accion'] ?? null;

if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new EstadisticasController($conn);

switch ($accion) {
    case "listar":
        $controller->listar();
        break;
    case "comparativa":
        $controller->comparativaPerfilesVsOfertas();
        break;
    case "distribucion":
        $controller->distribucionPorLineaTecnologica();
        break;
    case "completas":
        $controller->estadisticasCompletas();
        break;
    case "tendencias":
        $controller->tendenciasTemporales();
        break;
    case "niveles":
        $controller->estadisticasPorNivel();
        break;
    case "exportar":
        $controller->exportarCSV();
        break;
    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion,
            "acciones_disponibles" => [
                "listar",
                "comparativa",
                "distribucion",
                "completas",
                "tendencias",
                "niveles",
                "exportar"
            ]
        ]);
}