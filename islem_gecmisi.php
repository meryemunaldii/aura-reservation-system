<?php
include 'db.php'; 
session_start();

header('Content-Type: application/json'); // Tarayıcıya JSON gönderdiğimizi söylüyoruz

$user_id = $_SESSION['user_id'] ?? $_SESSION['UserID'] ?? null;

if (!$user_id) {
    echo json_encode(["error" => "Oturum bulunamadı"]);
    exit();
}

$sql = "SELECT ActionType, ActionTime FROM actionhistory WHERE UserID = ? ORDER BY ActionTime DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$actions = [];
while ($row = $result->fetch_assoc()) {
    $actions[] = $row;
}

echo json_encode($actions); // Verileri JSON formatında basıyoruz
?>