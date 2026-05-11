<?php
include 'db.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: geribildirimlerim.php");
    exit;
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newRating = $_POST['rating'];
    $newComment = $conn->real_escape_string($_POST['comment']);
    
    $update = "UPDATE Feedbacks SET Rating = '$newRating', Comment = '$newComment' WHERE FeedbackID = $id";
    if ($conn->query($update)) {
        header("Location: geribildirimlerim.php?durum=guncellendi");
        exit;
    }
}

$query = "SELECT f.*, r.RoomNo FROM Feedbacks f JOIN Rooms r ON f.RoomID = r.RoomID WHERE f.FeedbackID = $id";
$result = $conn->query($query);
$feedback = $result->fetch_assoc();

include 'feedback_guncelle_tasarim.php';
?>