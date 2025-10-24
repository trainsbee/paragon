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
    // Para nueva pausa, requerimos estos campos
    $required = ['employee_id', 'reason', 'start_time'];
    
    foreach ($required as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Campo requerido: $field"]);
            exit;
        }
    }
} else {
    // Para actualizar, requerimos employee_id y end_time
    $required = ['employee_id', 'end_time'];
    
    foreach ($required as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Campo requerido: $field"]);
            exit;
        }
    }
    
    // Asegurarse de que no se esté intentando actualizar el reason
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
    
    // Si no se proporciona un pause_id, buscar la pausa activa del empleado
    if ($pause_id === null) {
        try {
            // Asegurarse de que el employee_id esté limpio y sin espacios
            $employee_id = trim($employee_id);
            
            // Construir la URL correctamente codificada para Supabase
            $url = $supabaseUrl . '/rest/v1/pauses?select=pause_id,start_time,end_time';
            
            // Usar cURL en lugar de file_get_contents para mejor manejo de errores
            $ch = curl_init();
            
            // Construir la consulta con parámetros codificados
            $query = http_build_query([
                'select' => 'pause_id,start_time,end_time',
                'employee_id' => 'eq.' . $employee_id,
                'end_time' => 'is.null'
            ]);
            
            $url = $supabaseUrl . '/rest/v1/pauses?' . $query;
            
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
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($response === false) {
                throw new Exception('Error de conexión: ' . $error);
            }
            
            $activePauses = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error al decodificar la respuesta de Supabase: ' . json_last_error_msg());
            }
            
            if (empty($activePauses)) {
                // Verificar si el empleado existe usando cURL
                $ch = curl_init();
                $checkEmployeeUrl = $supabaseUrl . '/rest/v1/employees?select=id&id=eq.' . urlencode($employee_id);
                
                curl_setopt_array($ch, [
                    CURLOPT_URL => $checkEmployeeUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => [
                        'apikey: ' . $supabaseKey,
                        'Authorization: Bearer ' . $supabaseKey,
                        'Content-Type: application/json',
                        'Accept: application/json'
                    ]
                ]);
                
                $employeeResponse = curl_exec($ch);
                $employeeExists = $employeeResponse && !empty(json_decode($employeeResponse, true));
                curl_close($ch);
                
                if (!$employeeExists) {
                    throw new Exception('El empleado con ID ' . $employee_id . ' no existe');
                }
                
                // Verificar si hay pausas para este empleado (aunque estén completadas)
                $ch = curl_init();
                $allPausesUrl = $supabaseUrl . '/rest/v1/pauses?select=pause_id&employee_id=eq.' . urlencode($employee_id) . '&limit=1';
                
                curl_setopt_array($ch, [
                    CURLOPT_URL => $allPausesUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => [
                        'apikey: ' . $supabaseKey,
                        'Authorization: Bearer ' . $supabaseKey,
                        'Content-Type: application/json',
                        'Accept: application/json'
                    ]
                ]);
                
                $pausesResponse = curl_exec($ch);
                $hasPauses = $pausesResponse && !empty(json_decode($pausesResponse, true));
                curl_close($ch);
                
                if ($hasPauses) {
                    throw new Exception('El empleado tiene pausas registradas, pero ninguna está activa actualmente');
                } else {
                    throw new Exception('El empleado no tiene pausas registradas');
                }
            }
            
            // Validar que activePauses sea un array y contenga elementos
            if (!is_array($activePauses) || empty($activePauses)) {
                throw new Exception('No se encontraron pausas activas para el empleado');
            }

            // Ordenar por start_time descendente y tomar la más reciente
            usort($activePauses, function($a, $b) {
                // Asegurarse de que los elementos tengan el formato esperado
                if (!is_array($a) || !isset($a['start_time']) || !is_array($b) || !isset($b['start_time'])) {
                    return 0; // No ordenar si la estructura no es la esperada
                }
                $timeA = strtotime($a['start_time']);
                $timeB = strtotime($b['start_time']);
                return $timeB - $timeA; // Orden descendente
            });
            
            // Tomar el ID de la primera pausa (la más reciente)
            if (!isset($activePauses[0]['pause_id'])) {
                error_log('Estructura inesperada de activePauses: ' . print_r($activePauses, true));
                throw new Exception('Error al obtener el ID de la pausa activa');
            }
            
            $pause_id = $activePauses[0]['pause_id'];
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'message' => 'Error al buscar pausas activas: ' . $e->getMessage(),
                'debug' => [
                    'employee_id' => $employee_id,
                    'url' => $url ?? null,
                    'response' => $response ?? null,
                    'activePauses' => $activePauses ?? null
                ]
            ]);
            exit;
        }
        
        $pause_id = $activePauses[0]['pause_id'];
    }
    
    // Actualizar pausa existente - solo actualizamos los campos necesarios
    $pauseData = [
        'end_time' => $data['end_time'],
        'updated_at' => date('c')
    ];
    
    // Asegurarnos de que estamos actualizando la pausa correcta del empleado correcto
    $url = "$supabaseUrl/rest/v1/pauses?and=(pause_id.eq.$pause_id,employee_id.eq.$employee_id)";
    $method = 'PATCH';
    
    // Depuración
    error_log("Updating pause with data: " . print_r($pauseData, true));
    error_log("Using URL: $url");
}

// Configurar la petición cURL
$ch = curl_init($url);
$curlOptions = [
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
];

curl_setopt_array($ch, $curlOptions);

// Ejecutar la petición
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Manejar errores
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
    // Si es una pausa nueva, devolver el ID generado
    echo json_encode([
        'success' => true, 
        'pause_id' => $responseData[0]['pause_id'],
        'message' => 'Pausa guardada correctamente'
    ]);
} else {
    // Si es una actualización
    echo json_encode([
        'success' => true, 
        'message' => 'Pausa actualizada correctamente'
    ]);
}
?>