<?php
include 'db.php'; 
session_start();

$UserID = $_SESSION['user_id'] ?? $_SESSION['UserID'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $UserID) {
    $RoomID = $_POST['RoomID'];
    $Rating = $_POST['Rating'];
    $Comment = $_POST['Comment'] ?? "";
    $FeedbackDate = date("Y-m-d");

    $sql = "INSERT INTO Feedbacks (UserID, RoomID, Rating, Comment, FeedbackDate) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("iiiss", $UserID, $RoomID, $Rating, $Comment, $FeedbackDate);

    if ($stmt->execute()) {
        header("Location: geribildirimlerim.php");
        exit();
    } else {
        echo "Bir hata oluştu: " . $stmt->error;
    }
} else {
    header("Location: mainpage.php");
    exit();
}

$log_sql = "INSERT INTO ActionHistory (UserID, ActionType) VALUES (?, 'Oda Hakkında Geri Bildirim Yapıldı')";
$log_stmt = $conn->prepare($log_sql);
$log_stmt->bind_param("i", $UserID);
$log_stmt->execute();
?>