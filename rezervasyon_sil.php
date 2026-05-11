<?php
include 'baglan.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Oturum açmanız gerekiyor."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'] ?? null;
    $user_id = $_SESSION['user_id'];

    if (!$reservation_id) {
        echo json_encode(["status" => "error", "message" => "Geçersiz rezervasyon bilgisi."]);
        exit;
    }

    // 1. Önce silme işlemini gerçekleştir
    $sql = "DELETE FROM reservations WHERE ReservationID = ? AND UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $reservation_id, $user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            
            // --- 2. SİLME BAŞARILIYSA LOG KAYDI AT ---
            // Hangi rezervasyonun silindiğini de mesaja ekleyelim
            $log_mesaj = "Rezervasyon İptal Edildi (ID: " . $reservation_id . ")";
            $log_sql = "INSERT INTO actionhistory (UserID, ActionType, ActionTime) VALUES (?, ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param("is", $user_id, $log_mesaj);
            $log_stmt->execute();
            // ----------------------------------------

            echo json_encode(["status" => "success", "message" => "Rezervasyon başarıyla silindi."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Rezervasyon bulunamadı veya silme yetkiniz yok."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Veritabanı hatası: " . $conn->error]);
    }
    $stmt->close();
}

$conn->close();
?>