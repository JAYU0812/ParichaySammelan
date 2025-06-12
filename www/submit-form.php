<?php
header('Content-Type: application/json');
require 'db.php';

// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["error" => "Method not allowed"]);
  exit;
}

// Parse form data (assumes JSON POST body)
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
  http_response_code(400);
  echo json_encode(["error" => "Invalid JSON"]);
  exit;
}

// Validate required fields (simplified)
$fields = [
  'email', 'photo_url', 'name', 'age', 'whatsapp', 'gender', 'height_cm', 'manglik', 'divorcee',
  'study', 'yearly_income', 'aadhaar', 'father_name', 'father_gautra',
  'mother_name', 'mother_gautra', 'present_address', 'permanent_address'
];
foreach($fields as $field){
  if (empty($data[$field])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing field: $field"]);
    exit;
  }
}

try {
  $stmt = $pdo->prepare('INSERT INTO entries
  (email, photo, name, age, whatsapp, gender, height_cm, manglik, divorcee, study, yearly_income,
   aadhaar, father_name, father_gautra, mother_name, mother_gautra, present_address, permanent_address)
   VALUES (:email, :photo, :name, :age, :whatsapp, :gender, :height_cm, :manglik,
           :divorcee, :study, :yearly_income, :aadhaar, :father_name, :father_gautra,
           :mother_name, :mother_gautra, :present_address, :permanent_address)');

  $stmt->execute([
    ':email' => $data['email'],
    ':photo' => $data['photo_url'], // You can handle uploads separately or base64 images
    ':name' => $data['name'],
    ':age' => $data['age'],
    ':whatsapp' => $data['whatsapp'],
    ':gender' => $data['gender'],
    ':height_cm' => $data['height_cm'],
    ':manglik' => $data['manglik'],
    ':divorcee' => $data['divorcee'],
    ':study' => $data['study'],
    ':yearly_income' => $data['yearly_income'],
    ':aadhaar' => $data['aadhaar'],
    ':father_name' => $data['father_name'],
    ':father_gautra' => $data['father_gautra'],
    ':mother_name' => $data['mother_name'],
    ':mother_gautra' => $data['mother_gautra'],
    ':present_address' => $data['present_address'],
    ':permanent_address' => $data['permanent_address'],
  ]);

  $insertedId = $pdo->lastInsertId();

  // Send email with unique id to user (example with mail())
  $to = $data['email'];
  $subject = "Your Submission ID from SBSS Yuva Samaj";
  $message = "Thank you for your submission.\nYour unique ID is: $insertedId\nPlease keep this for your records.";
  $headers = "From: no-reply@yourdomain.com";

  @mail($to, $subject, $message, $headers);

  echo json_encode(["success" => true, "unique_id" => $insertedId]);

} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
