<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

// Configuración de Supabase
$supabaseUrl = 'https://chrskbosiqcphyqbdeqy.supabase.co';
$supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImNocnNrYm9zaXFjcGh5cWJkZXF5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0OTU3MTM1MiwiZXhwIjoyMDY1MTQ3MzUyfQ.l6KJrNHKVm8eGgIC024ibiG1NIXkj_1yBarKhYRy3og';

// Determinar si es una pausa nueva o existente
$isNewPause = !isset($data['end_time']);

// Validar campos requeridos
if ($isNewPause) {
    $required = ['employee_id', 'reason', 'start_time'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Campo requerido: $field"]);
            exit;
        }
    }

// 🚨 VALIDACIÓN GLOBAL: solo una persona en cada tipo de baño al mismo tiempo en la fecha actual
if ($data['reason'] === 'bathroom_office' || $data['reason'] === 'bathroom_outside') {
    date_default_timezone_set('America/Tegucigalpa'); // Honduras UTC-6
    $today = date('Y-m-d'); // hoy
    $tomorrow = date('Y-m-d', strtotime('+1 day')); // mañana

    // Solo revisar el tipo de baño que se intenta crear
    $reason = $data['reason'];
    $checkUrl = "$supabaseUrl/rest/v1/pauses?"
        . "reason=eq.$reason"
        . "&end_time=is.null"
        . "&start_time=gte.$today&start_time=lt.$tomorrow";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $checkUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "apikey: $supabaseKey",
            "Authorization: Bearer $supabaseKey",
            "Content-Type: application/json"
        ]
    ]);
    $checkResponse = curl_exec($ch);
    curl_close($ch);

    $activeBathroom = json_decode($checkResponse, true);

    if (!empty($activeBathroom)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => "Ya hay alguien usando este tipo de baño ($reason), por ahora está ocupado."
        ]);
        exit;
    }
}


} else {
    $required = ['employee_id', 'end_time'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Campo requerido: $field"]);
            exit;
        }
    }
    if (isset($data['reason'])) {
        unset($data['reason']);
    }
}

// Preparar datos para Supabase
$pauseData = [];
if ($isNewPause) {
    // Crear nueva pausa
    $pauseData = [
        'employee_id' => $data['employee_id'],
        'reason' => $data['reason'],
        'start_time' => $data['start_time'],
        'created_at' => date('c')
    ];
    $url = "$supabaseUrl/rest/v1/pauses";
    $method = 'POST';
} else {
    $employee_id = $data['employee_id'];
    $pause_id = isset($data['pause_id']) ? intval($data['pause_id']) : null;

    if ($pause_id === null) {
        try {
            $employee_id = trim($employee_id);
            $query = http_build_query([
                'select' => 'pause_id,start_time,end_time',
                'employee_id' => 'eq.' . $employee_id,
                'end_time' => 'is.null'
            ]);
            $url = $supabaseUrl . '/rest/v1/pauses?' . $query;

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'apikey: ' . $supabaseKey,
                    'Authorization: Bearer ' . $supabaseKey,
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Prefer: return=representation'
                ]
            ]);
            $response = curl_exec($ch);
            $activePauses = json_decode($response, true);
            curl_close($ch);

            if (empty($activePauses)) {
                throw new Exception('No se encontraron pausas activas para el empleado');
            }

            $pause_id = $activePauses[0]['pause_id'];

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Error al buscar pausas activas: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    // Actualizar pausa existente
    $pauseData = [
        'end_time' => $data['end_time'],
        'updated_at' => date('c')
    ];
    $url = "$supabaseUrl/rest/v1/pauses?and=(pause_id.eq.$pause_id,employee_id.eq.$employee_id)";
    $method = 'PATCH';
}

// Configurar cURL
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_POSTFIELDS => json_encode($pauseData),
    CURLOPT_HTTPHEADER => [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey,
        'Content-Type: application/json',
        'Prefer: return=representation',
        'Prefer: resolution=merge-duplicates'
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Manejo de errores
if ($error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al conectar con Supabase',
        'error' => $error
    ]);
    exit;
}

if ($httpCode >= 400) {
    http_response_code($httpCode);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos',
        'details' => json_decode($response, true)
    ]);
    exit;
}

// Éxito
$responseData = json_decode($response, true);
if ($isNewPause && !empty($responseData[0]['pause_id'])) {
    echo json_encode([
        'success' => true,
        'pause_id' => $responseData[0]['pause_id'],
        'message' => 'Pausa guardada correctamente'
    ]);
} else {
    echo json_encode([
        'success' => true,
        'message' => 'Pausa actualizada correctamente'
    ]);
}
?>
