require_once('tcpdf/tcpdf.php');
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$host = 'sql207.infinityfree.com';
$db = 'if0_39177314_sammelan';
$user = 'if0_39177314';
$pass = 'OUbbY98vNiBFB';
$conn = new mysqli($host, $user, $pass, $db);

$res = $conn->query("SELECT * FROM users");
$html = '<h1>Users Data</h1><table border="1"><tr><th>Unique ID</th><th>Name</th><th>Email</th></tr>';
while ($r = $res->fetch_assoc()) {
  $html .= "<tr><td>{$r['unique_id']}</td><td>{$r['name']}</td><td>{$r['email']}</td></tr>";
}
$html .= '</table>';
$pdf->writeHTML($html);
$pdf->Output('users_data.pdf', 'D');
