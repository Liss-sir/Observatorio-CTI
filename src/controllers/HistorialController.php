<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Historial.php';

header('Content-Type: application/json; charset=utf-8');

// Función para respuestas JSON estandarizadas
function json_response($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Verificar conexión
if (!isset($conn) || !($conn instanceof PDO)) {
    json_response(['ok' => false, 'message' => 'Error de conexión a la base de datos'], 500);
}

$historial = new Historial($conn);

// Obtener acción (acepta 'action' o 'accion')
$action = $_GET['action'] ?? $_GET['accion'] ?? '';

try {
    if ($action === 'listar') {
        // Parámetros de entrada
        $search = trim($_GET['q'] ?? '');
        $modulo = trim($_GET['modulo'] ?? '');        // Nombre exacto de la tabla
        $accionCrud = trim($_GET['accion'] ?? '');    // INSERT, UPDATE, DELETE
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = min(100, max(5, (int)($_GET['limit'] ?? 20)));
        $offset = ($page - 1) * $limit;

        // Obtener datos
        $items = $historial->listar($search, $modulo, $accionCrud, $limit, $offset);
        $total = $historial->contar($search, $modulo, $accionCrud);

        // Enriquecer cada registro con una descripción legible
        enrich_items($items);

        json_response([
            'ok' => true,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'items' => $items
        ]);
    }
    elseif ($action === 'contar') {
        $search = trim($_GET['q'] ?? '');
        $modulo = trim($_GET['modulo'] ?? '');
        $accionCrud = trim($_GET['accion'] ?? '');

        $total = $historial->contar($search, $modulo, $accionCrud);
        json_response(['ok' => true, 'total' => $total]);
    }
    else {
        json_response(['ok' => false, 'message' => 'Acción no válida'], 400);
    }
} catch (Throwable $e) {
    json_response(['ok' => false, 'message' => $e->getMessage()], 500);
}

// ----------------------------------------------------------------------
// Funciones auxiliares para generar descripciones a partir del JSON
// ----------------------------------------------------------------------

/**
 * Decodifica un campo JSON de forma segura.
 */
function safe_json_decode($json) {
    if ($json === null || trim($json) === '') return null;
    $data = json_decode($json, true);
    return (json_last_error() === JSON_ERROR_NONE && is_array($data)) ? $data : null;
}

/**
 * Normaliza un valor para mostrarlo en texto plano.
 */
function normalize_value($val) {
    if ($val === null) return '';
    if (is_bool($val)) return $val ? 'true' : 'false';
    if (is_array($val) || is_object($val)) return json_encode($val, JSON_UNESCAPED_UNICODE);
    $s = trim((string)$val);
    return mb_strlen($s) > 80 ? mb_substr($s, 0, 77) . '…' : $s;
}

/**
 * Encuentra la primera clave existente en un array.
 */
function find_first_key($arr, $candidates) {
    if (!is_array($arr)) return null;
    foreach ($candidates as $k) {
        if (array_key_exists($k, $arr) && !empty(trim((string)$arr[$k]))) {
            return $k;
        }
    }
    return null;
}

/**
 * Genera texto con los cambios más relevantes entre dos arrays (old vs new).
 */
function build_changes_text($old, $new, $max = 3) {
    if (!is_array($old) || !is_array($new)) return '';

    $changes = [];
    $keys = array_unique(array_merge(array_keys($old), array_keys($new)));

    foreach ($keys as $k) {
        $ov = array_key_exists($k, $old) ? normalize_value($old[$k]) : '';
        $nv = array_key_exists($k, $new) ? normalize_value($new[$k]) : '';

        if ($ov === $nv) continue;

        // Ignorar campos de fecha/hora comunes
        if (preg_match('/^(fecha|created_at|updated_at|fecha_creacion|fecha_actualizacion)$/i', $k)) continue;

        $label = str_replace('_', ' ', $k);
        $changes[] = "$label: \"$ov\" → \"$nv\"";
    }

    if (empty($changes)) return '';

    $shown = array_slice($changes, 0, $max);
    $rest = count($changes) - count($shown);
    $txt = implode(' • ', $shown);
    if ($rest > 0) $txt .= " • y $rest más";

    return $txt;
}

/**
 * Genera un resumen de los valores más importantes para un INSERT.
 */
function build_insert_highlights($data, $max = 3) {
    if (!is_array($data)) return '';

    // Campos prioritarios según tablas típicas del sistema
    $priorityKeys = [
        'nombre_programa', 'nombre_area', 'nombre', 'titulo', 'descripcion',
        'representante_legal', 'nombre_empresa', 'correo', 'codigo_programa',
        'cupos', 'modalidad', 'tipo_documento', 'numero_documento'
    ];

    $pairs = [];
    foreach ($priorityKeys as $k) {
        if (array_key_exists($k, $data) && !empty(trim((string)$data[$k]))) {
            $label = str_replace('_', ' ', $k);
            $pairs[] = "$label: " . normalize_value($data[$k]);
        }
        if (count($pairs) >= $max) break;
    }

    if (empty($pairs)) {
        // Fallback: primeras claves no vacías
        $i = 0;
        foreach ($data as $k => $v) {
            if ($i >= $max) break;
            if (preg_match('/^(id_|password|token|hash)/i', $k)) continue;
            $val = normalize_value($v);
            if ($val === '') continue;
            $label = str_replace('_', ' ', $k);
            $pairs[] = "$label: $val";
            $i++;
        }
    }

    return empty($pairs) ? '' : implode(' • ', $pairs);
}

/**
 * Enriquece cada registro con una descripción textual generada a partir de los JSON.
 */
function enrich_items(&$items) {
    foreach ($items as &$item) {
        $tabla = $item['tabla_nombre'] ?? '';
        $accion = $item['accion'] ?? '';
        $pk = $item['pk_valor'] ?? '';

        $old = safe_json_decode($item['old_values'] ?? null);
        $new = safe_json_decode($item['new_values'] ?? null);

        $modulo = $tabla ? ucfirst(str_replace('_', ' ', $tabla)) : 'Módulo';

        if ($accion === 'INSERT' && $new) {
            $high = build_insert_highlights($new, 3);
            $desc = "Creó registro en $modulo" . ($pk ? " (#$pk)" : '');
            if ($high) $desc .= " — $high";
        }
        elseif ($accion === 'UPDATE' && ($old || $new)) {
            $changes = build_changes_text($old, $new, 3);
            $desc = "Actualizó $modulo" . ($pk ? " (#$pk)" : '');
            if ($changes) $desc .= " — $changes";
            else $desc .= " (sin cambios visibles)";
        }
        elseif ($accion === 'DELETE' && $old) {
            $high = build_insert_highlights($old, 2);
            $desc = "Eliminó registro en $modulo" . ($pk ? " (#$pk)" : '');
            if ($high) $desc .= " — $high";
        }
        else {
            $desc = "Acción en $modulo" . ($pk ? " (#$pk)" : '');
        }

        $item['descripcion'] = $desc;
    }
}