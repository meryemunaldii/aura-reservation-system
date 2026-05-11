<?php
include 'baglan.php'; 
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT r.ReservationID, r.ReservationDate, r.StartTime, r.ApprovalStatus, rooms.RoomNo 
        FROM reservations r 
        JOIN rooms ON r.RoomID = rooms.RoomID 
        WHERE r.UserID = ? 
        ORDER BY r.ReservationDate DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$rezervasyonlar = [];
while ($row = $result->fetch_assoc()) {
    $rezervasyonlar[] = $row;
}

echo json_encode($rezervasyonlar);
?>