<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php-error.log');
error_reporting(E_ALL);

header('Content-Type: application/json');

// Read input JSON
$input = file_get_contents('php://input');
if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Empty request']);
    exit;
}

$data = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// Check credentials (hardcoded here)
$validUsername = 'SAMMELAN';
$validPassword = 'Secure@2005';

if (strcasecmp($username, $validUsername) === 0 && $password === $validPassword) {
    // Credentials OK, generate a simple token (for demo)
    $token = bin2hex(random_bytes(16));
    echo json_encode(['success' => true, 'token' => $token]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
}
