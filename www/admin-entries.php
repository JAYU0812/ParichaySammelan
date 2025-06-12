<?php
// admin-entries.php - Secure endpoint to fetch user entries with Bearer token authentication
// Following Default Design Guidelines: clean, minimal, semantic JSON API response

// Disable error display to clients, enable error logging for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');
error_reporting(E_ALL);

// Set JSON content-type header and CORS headers for cross-origin requests
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, OPTIONS");

// Early handle OPTIONS preflight request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Require database connection (db.php must create $pdo as PDO instance)
require 'db.php';

// Function to send an Unauthorized JSON response and exit
function unauthorizedResponse($message = "Unauthorized") {
    http_response_code(401);
    echo json_encode(["error" => $message]);
    exit;
}

// Function to send a Bad Request JSON response and exit
function badRequestResponse($message = "Bad Request") {
    http_response_code(400);
    echo json_encode(["error" => $message]);
    exit;
}

// Retrieve Authorization header (case-insensitive)
$headers = function_exists('getallheaders') ? getallheaders() : [];
$authHeader = null;
foreach ($headers as $name => $value) {
    if (strtolower($name) === 'authorization') {
        $authHeader = trim($value);
        break;
    }
}

if (!$authHeader) {
    unauthorizedResponse("Missing Authorization header");
}

// Extract Bearer token using regex
if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
    unauthorizedResponse("Invalid Authorization header format");
}

$token = $matches[1];

// === Security note ===
// Replace this with your own robust authentication/authorization method!
// For demo, using a static token for validation:
$VALID_TOKEN = 'SAMMELAN';

if ($token !== $VALID_TOKEN) {
    unauthorizedResponse("Invalid token");
}

// If here, token is valid, proceed to fetch entries
try {
    // Prepare and execute query to fetch minimal user entries listing
    $stmt = $pdo->prepare("
        SELECT 
            id, 
            name, 
            email, 
            whatsapp, 
            age, 
            gender
        FROM user_entries 
        ORDER BY id DESC
    ");
    $stmt->execute();
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Respond with structured JSON in a clean, readable manner
    echo json_encode([
        "success" => true,
        "count" => count($entries),
        "entries" => $entries
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    // Log detailed error internally (do not expose full details to client)
    error_log("Database error in admin-entries.php: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "error" => "Internal server error retrieving entries"
    ]);
    exit;
}
?>
