<?php
include 'db.php';
session_start();


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM Feedbacks WHERE FeedbackID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: geribildirimlerim.php?mesaj=silindi");
    } else {
        echo "Hata: " . $conn->error;
    }
    $stmt->close();
}

?>