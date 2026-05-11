<?php
include 'db.php';
session_start();

$sql = "SELECT f.*, r.RoomNo FROM Feedbacks f JOIN Rooms r ON f.RoomID = r.RoomID ORDER BY f.FeedbackDate DESC";
$result = $conn->query($sql);

$feedbacks = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}


include 'geribildirimlerim_tasarim.php'; 
?>