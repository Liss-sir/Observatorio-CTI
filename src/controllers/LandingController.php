<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Landing.php";

class LandingController {

    private $model;

    public function __construct(PDO $conn) {
        $this->model = new LandingModel($conn);
    }

    // Obtener los últimos 4 perfiles
    public function ultimosPerfiles() {
        $perfiles = $this->model->ultimosPerfiles(4);
        echo json_encode([
            'status' => 'success',
            'data' => $perfiles
        ]);
    }

    // Obtener las 4 líneas con más perfiles
    public function lineasConMasPerfiles() {
        $lineas = $this->model->lineasConMasPerfiles(4);
        echo json_encode([
            'status' => 'success',
            'data' => $lineas
        ]);
    }

    // Obtener los 5 programas con más cupos
    public function programasConMasCupos() {
        $programas = $this->model->programasConMasCupos(5);
        echo json_encode([
            'status' => 'success',
            'data' => $programas
        ]);
    }
}

// Enrutamiento simple basado en el parámetro 'accion'
$accion = $_GET['accion'] ?? null;

if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new LandingController($conn);

switch ($accion) {
    case "ultimosPerfiles":
        $controller->ultimosPerfiles();
        break;
    case "lineasConMasPerfiles":
        $controller->lineasConMasPerfiles();
        break;
    case "programasConMasCupos":
        $controller->programasConMasCupos();
        break;
    default:
        echo json_encode([
            "error" => "Acción no válida",
            "accion_recibida" => $accion
        ]);
}