<?php
$supabaseUrl = 'https://chrskbosiqcphyqbdeqy.supabase.co/rest/v1';
$supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImNocnNrYm9zaXFjcGh5cWJkZXF5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0OTU3MTM1MiwiZXhwIjoyMDY1MTQ3MzUyfQ.l6KJrNHKVm8eGgIC024ibiG1NIXkj_1yBarKhYRy3og';

// Function to make a GET request to Supabase
function fetchFromSupabase($url, $key, $endpoint) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/' . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $key,
        'apikey: ' . $key,
        'Content-Type: application/json'
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode != 200) {
        return false;
    }
    return json_decode($response, true);
}

// Fetch managers
$managers = fetchFromSupabase($supabaseUrl, $supabaseKey, 'managers?select=*');
if (!$managers) {
    die('Error fetching managers');
}

// Fetch employees
$employees = fetchFromSupabase($supabaseUrl, $supabaseKey, 'employees?select=*');
if (!$employees) {
    die('Error fetching employees');
}

// Initialize the users array
$users = [];

// Build the nested structure
foreach ($managers as $manager) {
    $managerId = $manager['manager_id'];
    $users[$managerId] = [
        'id' => $manager['id'],
        'name' => $manager['name'],
        'username' => $manager['username'],
        'password' => $manager['password'],
        'DEPARTMENT' => $manager['department'],
        'status' => $manager['status'],
        'role' => $manager['role'],
        'employees' => []
    ];

    // Find employees for this manager
    foreach ($employees as $employee) {
        if ($employee['manager_id'] === $managerId) {
            $users[$managerId]['employees'][] = [
                'id' => $employee['id'],
                'name' => $employee['name'],
                'username' => $employee['username'],
                'password' => $employee['password'],
                'status' => $employee['status'],
                'role' => $employee['role']
            ];
        }
    }
}

// Output the array (for testing, you can remove this or modify as needed)
print_r($users);
?>