<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuración de Supabase
$supabaseUrl = 'https://chrskbosiqcphyqbdeqy.supabase.co';
$supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImNocnNrYm9zaXFjcGh5cWJkZXF5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0OTU3MTM1MiwiZXhwIjoyMDY1MTQ3MzUyfQ.l6KJrNHKVm8eGgIC024ibiG1NIXkj_1yBarKhYRy3og';

$employeeId = $_GET['employee_id'] ?? '';

if (empty($employeeId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de empleado requerido']);
    exit;
}

try {
    // Obtener fechas de filtro o usar la fecha actual por defecto
    $startDate = $_GET['start_date'] ?? '';
    $endDate = $_GET['end_date'] ?? '';
    
    // Si no se proporcionan fechas, usar el día actual
    if (empty($startDate) || empty($endDate)) {
        $startDate = date('Y-m-d');
        $endDate = $startDate;
    }
    
    // Construir la URL base
    $url = $supabaseUrl . '/rest/v1/pauses?select=*';
    $url .= '&employee_id=eq.' . urlencode($employeeId);
    
    // Crear objetos DateTime para Honduras (UTC-6)
    $timezone = new DateTimeZone('America/Tegucigalpa');
    $startDateObj = new DateTime($startDate, $timezone);
    $endDateObj = new DateTime($endDate, $timezone);
    
    // Ajustar la fecha de fin para incluir todo el día
    $endDateObj->modify('+1 day');
    
    // Formatear fechas para la consulta (UTC)
    $startDateUTC = $startDateObj->format('Y-m-d\TH:i:s.000\Z');
    $endDateUTC = $endDateObj->format('Y-m-d\TH:i:s.000\Z');
    
    // Aplicar filtro de rango de fechas
    $url .= '&start_time=gte.' . urlencode($startDateUTC);
    $url .= '&start_time=lt.' . urlencode($endDateUTC);
    
    // Ordenar por fecha de inicio descendente
    $url .= '&order=start_time.desc';
    
    // Inicializar cURL
    $ch = curl_init();
    
    // Configurar opciones de cURL
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'apikey: ' . $supabaseKey,
            'Authorization: Bearer ' . $supabaseKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ]
    ]);
    
    // Ejecutar la petición
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    // Manejar errores
    if ($error) {
        throw new Exception('Error de conexión con Supabase: ' . $error);
    }
    
    if ($httpCode >= 400) {
        throw new Exception('Error en la petición a Supabase. Código: ' . $httpCode);
    }
    
    // Decodificar la respuesta
    $pauses = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar la respuesta de Supabase');
    }
    
    // Formatear las fechas para que sean más legibles
    foreach ($pauses as &$pause) {
        if (isset($pause['start_time'])) {
            $date = new DateTime($pause['start_time']);
            $date->setTimezone(new DateTimeZone('America/Tegucigalpa')); // Ajustar a la zona horaria de Honduras
            $pause['start_time_formatted'] = $date->format('d/m/Y H:i:s');
        }
        if (isset($pause['end_time'])) {
            $date = new DateTime($pause['end_time']);
            $date->setTimezone(new DateTimeZone('America/Tegucigalpa')); // Ajustar a la zona horaria de Honduras
            $pause['end_time_formatted'] = $date->format('d/m/Y H:i:s');
        }
        if (isset($pause['created_at'])) {
            $date = new DateTime($pause['created_at']);
            $date->setTimezone(new DateTimeZone('America/Tegucigalpa')); // Ajustar a la zona horaria de Honduras
            $pause['created_at_formatted'] = $date->format('d/m/Y H:i:s');
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $pauses
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener las pausas: ' . $e->getMessage(),
        'error_details' => $e->getMessage()
    ]);
}
?>
