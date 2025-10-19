<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Function to get department from local database
function getEmployeeDepartment($employeeId, $users) {
    foreach ($users as $manager) {
        // Check if this is the manager
        if ($manager['id'] === $employeeId) {
            return $manager['DEPARTMENT'] ?? 'N/A';
        }
        
        // Check manager's employees
        foreach ($manager['employees'] as $employee) {
            if ($employee['id'] === $employeeId) {
                return $manager['DEPARTMENT'] ?? 'N/A';
            }
        }
    }
    return 'N/A';
}

// Supabase configuration
$supabaseUrl = 'https://chrskbosiqcphyqbdeqy.supabase.co';
$supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImNocnNrYm9zaXFjcGh5cWJkZXF5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0OTU3MTM1MiwiZXhwIjoyMDY1MTQ3MzUyfQ.l6KJrNHKVm8eGgIC024ibiG1NIXkj_1yBarKhYRy3og';

$employeeIds = $_GET['employee_ids'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';



if (empty($employeeIds)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Employee IDs are required']);
    exit;
}

$employeeIds = explode(',', $employeeIds);
$result = [];

try {
    $ch = curl_init();
     
    foreach ($employeeIds as $employeeId) {
        // Get all pauses (both active and historical) in a single request
        $pausesUrl = $supabaseUrl . '/rest/v1/pauses?select=*&order=start_time.desc';
        $pausesUrl .= '&employee_id=eq.' . urlencode($employeeId);
        $pausesUrl .= '&start_time=gte.' . urlencode($startDate . 'T00:00:00');
        $pausesUrl .= '&start_time=lte.' . urlencode($endDate . 'T23:59:59');
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $pausesUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'apikey: ' . $supabaseKey,
                'Authorization: Bearer ' . $supabaseKey,
                'Content-Type: application/json',
                'Prefer: return=representation'
            ]
        ]);
        
        $response = curl_exec($ch);
        $allPauses = json_decode($response, true);
        $allPauses = is_array($allPauses) ? $allPauses : [];
        
        // Get department from local database
        $department = getEmployeeDepartment($employeeId, $GLOBALS['users']);
        
        // Separate active and historical pauses
        $activePauses = [];
        $historyPauses = [];
        $totalPauseTime = 0;
        
        foreach ($allPauses as $pause) {
            if (empty($pause['end_time'])) {
                $activePauses[] = $pause;
            } else {
                $historyPauses[] = $pause;
                // Calculate pause duration for historical pauses
                if (isset($pause['start_time']) && isset($pause['end_time'])) {
                    $start = new DateTime($pause['start_time']);
                    $end = new DateTime($pause['end_time']); 
                    $totalPauseTime += ($end->getTimestamp() - $start->getTimestamp());
                }
            }
        }
        
        // Format total time
        $hours = floor($totalPauseTime / 3600);
        $minutes = floor(($totalPauseTime % 3600) / 60);
        $seconds = $totalPauseTime % 60;
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        
        $result[$employeeId] = [
            'active_pauses' => count($activePauses),
            'total_pauses' => count($historyPauses),
            'total_pause_time' => $formattedTime,
            'total_pause_seconds' => $totalPauseTime,
            'department' => $department,
            'pauses' => []
        ];
        
        // Add active pause details
        if (!empty($activePauses)) {
            foreach ($activePauses as $pause) {
                $startTime = new DateTime($pause['start_time']);
                $now = new DateTime();
                $elapsed = $now->getTimestamp() - $startTime->getTimestamp();
                
                $hours = floor($elapsed / 3600);
                $minutes = floor(($elapsed % 3600) / 60);
                $seconds = $elapsed % 60;
                $elapsedFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                
                $result[$employeeId]['pauses'][] = [
                    'start_time' => $startTime->format('Y-m-d\TH:i:s'), // Full ISO format including date
                    'elapsed_time' => $elapsedFormatted,
                    'reason' => $pause['reason'] ?? 'Sin razón',
                    'display_time' => $startTime->format('H:i:s') // For display purposes only
                ];
            }
        }
    }
    
    curl_close($ch);
    
    echo json_encode(['success' => true, 'data' => $result]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
