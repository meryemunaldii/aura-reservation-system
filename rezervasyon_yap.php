<?php
include 'baglan.php'; // Veritabanı bağlantı dosyanızın adı bu olduğu için değiştirmedim
session_start();

$bugun = date('Y-m-d');
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Oturum kapalı!"]);
    exit;
}

// --- CEZA KONTROLÜ ---
$ceza_sql = "SELECT Reason, EndDate FROM UserViolations 
             WHERE UserID = ? AND EndDate >= ? 
             ORDER BY EndDate DESC LIMIT 1";

$ceza_stmt = $conn->prepare($ceza_sql);
$ceza_stmt->bind_param("is", $user_id, $bugun);
$ceza_stmt->execute();
$ceza_res = $ceza_stmt->get_result();

header('Content-Type: application/json');

if ($ceza_res->num_rows > 0) {
    $ceza = $ceza_res->fetch_assoc();
    echo json_encode([
        "status" => "error", 
        "message" => "DİKKAT: Rezervasyon yetkiniz askıya alınmıştır! \nSebep: " . $ceza['Reason'] . " \nCeza Bitiş Tarihi: " . $ceza['EndDate']
    ]);
    exit;
}

// --- VERİ ALMA ---
$room_id = $_POST['room_id'] ?? '';
$tarih = $_POST['tarih'] ?? '';
$saat = $_POST['saat'] ?? '';

if (empty($room_id) || empty($tarih) || empty($saat)) {
    echo json_encode(["status" => "error", "message" => "Eksik bilgi gönderildi!"]);
    exit;
}

// --- ZAMAN KONTROLÜ ---
date_default_timezone_set('Europe/Istanbul');
$su_an = date('Y-m-d H:i:s');
$secilen_zaman = $tarih . ' ' . $saat;

if ($secilen_zaman < $su_an) {
    echo json_encode([
        "status" => "error", 
        "message" => "Geçmiş bir tarihe veya saate rezervasyon yapamazsınız!"
    ]);
    exit;
}

// --- DOLULUK KONTROLÜ ---
$check_sql = "SELECT ReservationID FROM reservations 
              WHERE RoomID = ? AND ReservationDate = ? AND StartTime = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("iss", $room_id, $tarih, $saat);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Üzgünüz, bu oda tam şu anda başka birisi tarafından rezerve edildi!"]);
    exit;
}

// --- REZERVASYON KAYDI ---
$bitis_saati = date('H:i:s', strtotime($saat . ' +1 hour'));

$sql = "INSERT INTO reservations (UserID, RoomID, ReservationDate, StartTime, EndTime, ApprovalStatus) 
        VALUES (?, ?, ?, ?, ?, 'Pending')";

try {
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("iisss", $user_id, $room_id, $tarih, $saat, $bitis_saati);
    
    if ($stmt->execute()) {

        // --- İŞLEM GEÇMİŞİNE (ActionHistory) KAYDET ---
        // Hangi oda olduğunu da log mesajına ekledik
        $log_mesaj = "Oda No: " . $room_id . " için yeni rezervasyon oluşturuldu";
        $log_sql = "INSERT INTO actionhistory (UserID, ActionType, ActionTime) VALUES (?, ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("is", $user_id, $log_mesaj);
        $log_stmt->execute();
        // ----------------------------------------------

        echo json_encode(["status" => "success", "message" => "Rezervasyon başarıyla kaydedildi!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Hatası: " . $stmt->error]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Sistem Hatası: " . $e->getMessage()]);
}
?>