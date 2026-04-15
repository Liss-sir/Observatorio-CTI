<?php

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ ."/../models/MiPerfil.php";
require_once __DIR__ . "/../../config/database.php";

class MiPerfilController {
    private $model;

    public function __construct($db) {
        $this->model = new MiPerfil($db);
    }

    /**
     * Verifica que el usuario esté autenticado y devuelve su ID.
     * Si no está autenticado, envía respuesta 401 y retorna false.
     */
    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            return false;
        }
        return $_SESSION['id_usuario'];
    }

    /**
     * Obtiene los datos del perfil del usuario autenticado junto con las estadísticas y últimos perfiles
     */
    public function index() {
        $userId = $this->checkAuth();
        if (!$userId) return;

        // Datos del usuario (frescos desde la BD)
        $user = $this->model->getUser($userId);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado']);
            return;
        }

        // Estadísticas
        // Determinar tipo de cuenta
        $esAdmin = ($user['id_rol'] == 1);

        if ($esAdmin) {
            $stats = $this->model->getStats();
            $latestProfiles = $this->model->getLatestProfiles(5);
        } else {
            try {
                $stats = $this->model->getStatsByEmpresa($userId);
                $latestProfiles = $this->model->getLatestProfilesByEmpresa($userId, 5);
            } catch (Exception $e) {
                echo json_encode([
                    'error' => $e->getMessage()
                ]);
                exit;
            }
        }

        // Formatear fecha de registro (ej. "febrero 2026")
        $fecha = strtotime($user['fecha_registro']);

        $meses = [
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre'
        ];

        $mesIngles = date('F', $fecha);
        $mesEspanol = $meses[$mesIngles];

        $registro = date('d', $fecha) . ' de ' . $mesEspanol . ' de ' . date('Y', $fecha);

        // Determinar tipo de cuenta (según rol)
        $tipoCuenta = ($user['id_rol'] == 1) ? 'Administrador' : 'Empresa';
        $descripcionCuenta = ($user['id_rol'] == 1)
            ? 'Acceso completo a la plataforma'
            : 'Acceso parcial a la plataforma';

        $response = [
            'usuario' => [
                'nombre_completo' => $user['representante_legal'],
                'correo'          => $user['correo'],
                'miembro_desde'   => $registro,
                'tipo_cuenta'     => $tipoCuenta,
                'descripcion'     => $descripcionCuenta,
                'tipo_documento'   => $user['tipo_documento'] ?? '',
                'numero_documento' => $user['numero_documento'] ?? '',
                'nombre_empresa'   => $user['nombre_empresa'] ?? '',
                'razon_social'     => $user['razon_social'] ?? '',
            ],
            'estadisticas' => $stats,
            'ultimos_perfiles' => $latestProfiles,
        ];

        echo json_encode($response);
    }

    /**
     * Actualiza el nombre y correo del usuario autenticado
     */
    public function update() {
        $userId = $this->checkAuth();
        if (!$userId) return;

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['nombre_completo']) || !isset($input['correo'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }

        $name = trim($input['nombre_completo']);
        $email = trim($input['correo']);

        // Validar que el correo no esté en uso por otro usuario
        if ($this->model->emailExists($email, $userId)) {
            http_response_code(409);
            echo json_encode(['error' => 'El correo ya está registrado']);
            return;
        }

        // Actualizar en la base de datos
        $updated = $this->model->updateUser($userId, $name, $email);
        if ($updated) {
            // Actualizar los datos en la sesión para mantener coherencia
            $_SESSION['usuario']['representante_legal'] = $name;
            $_SESSION['correo'] = $email;
            $_SESSION['usuario']['correo'] = $email;

            echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el perfil']);
        }
    }
}


$action = $_GET['action'] ?? 'index';

// Verificar que la conexión exista
if (!isset($conn)) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$controller = new MiPerfilController($conn);

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'update':
        $controller->update();
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Acción no válida']);
        break;
}