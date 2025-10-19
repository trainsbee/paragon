<?php
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

$response = ['success' => false, 'message' => 'Usuario o contraseña incorrectos'];

// Check managers
foreach ($users as $manager) {
    if ($manager['username'] === $username && $manager['password'] === $password) {
        $response = [
            'success' => true,
            'user' => [
                'id' => $manager['id'],
                'username' => $manager['username'],
                'name' => $manager['name'],
                'role' => $manager['role'],
                'department' => $manager['DEPARTMENT'] ?? ''
            ]
        ];
        echo json_encode($response);
        exit;
    }
    
    // Check employees
    foreach ($manager['employees'] as $employee) {
        if ($employee['username'] === $username && $employee['password'] === $password) {
            $response = [
                'success' => true,
                'user' => [
                    'id' => $employee['id'],
                    'username' => $employee['username'],
                    'name' => $employee['name'],
                    'role' => $employee['role'],
                    'department' => $manager['DEPARTMENT'] ?? ''
                ]
            ];
            echo json_encode($response);
            exit;
        }
    }
}

echo json_encode($response);
?>
