<?php
header('Content-Type: application/json');
$host = 'sql207.infinityfree.com';
$db = 'if0_39177314_sammelan';
$user = 'if0_39177314';
$pass = 'OUbbY98vNiBFB';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) die(json_encode(['error' => $conn->connect_error]));

$action = $_GET['action'] ?? '';
if ($action === 'read') {
  $res = $conn->query("SELECT * FROM users");
  $rows = [];
  while ($r = $res->fetch_assoc()) $rows[] = $r;
  echo json_encode($rows);
}

elseif ($action === 'toggle_view') {
  // This should toggle a flag in your DB, e.g., view_data_enabled table.
  $conn->query("UPDATE view_data SET enabled = NOT enabled");
  echo json_encode(['status' => 'toggled']);
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  if ($data['action'] === 'update') {
    $stmt = $conn->prepare("UPDATE users SET name=? WHERE unique_id=?");
    $stmt->bind_param('ss', $data['name'], $data['unique_id']);
    $stmt->execute();
    echo json_encode(['status' => 'updated']);
  }
  if ($data['action'] === 'delete') {
    $stmt = $conn->prepare("DELETE FROM users WHERE unique_id=?");
    $stmt->bind_param('s', $data['unique_id']);
    $stmt->execute();
    echo json_encode(['status' => 'deleted']);
  }
}
?>
