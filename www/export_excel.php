<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=users_data.xls");
$host = 'sql207.infinityfree.com';
$db = 'if0_39177314_sammelan';
$user = 'if0_39177314';
$pass = 'OUbbY98vNiBFB';
$conn = new mysqli($host, $user, $pass, $db);

$res = $conn->query("SELECT * FROM users");
echo "<table border='1'>";
echo "<tr><th>Unique ID</th><th>Name</th><th>Email</th><th>Mobile</th></tr>";
while ($r = $res->fetch_assoc()) {
  echo "<tr>
    <td>{$r['unique_id']}</td>
    <td>{$r['name']}</td>
    <td>{$r['email']}</td>
    <td>{$r['mobile']}</td>
  </tr>";
}
echo "</table>";
?>
