<?php
include 'baglan.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: giris.html");
    exit();
}

$oda_tipi = $_GET['oda_tipi'] ?? '';
$tarih = $_GET['tarih'] ?? '';
$saat = $_GET['saat'] ?? '';

date_default_timezone_set('Europe/Istanbul');
$su_an = date('Y-m-d H:i:s');
$secilen_zaman = $tarih . ' ' . $saat;

$rooms = [];

if (!empty($tarih) && !empty($saat) && $secilen_zaman < $su_an) {
    $rooms = []; 
} else {
    $sql = "SELECT r.*, 
            gr.HasProjector, 
            ir.IsQuietZone, ir.HasDeskLamp,
            sr.HasSoundSystem, sr.HasStage, sr.SeatType
            FROM rooms r
            LEFT JOIN grouprooms gr ON r.RoomID = gr.RoomID
            LEFT JOIN individualrooms ir ON r.RoomID = ir.RoomID
            LEFT JOIN seminarrooms sr ON r.RoomID = sr.RoomID
            WHERE 1=1";

    if (!empty($tarih) && !empty($saat)) {
        $sql .= " AND r.RoomID NOT IN (
                    SELECT RoomID FROM reservations 
                    WHERE ReservationDate = '" . $conn->real_escape_string($tarih) . "' 
                    AND StartTime = '" . $conn->real_escape_string($saat) . "'
                )";
    }

    if (!empty($oda_tipi)) {
        $sql .= " AND r.RoomType = '" . $conn->real_escape_string($oda_tipi) . "'";
    }

    $result = $conn->query($sql);

    if ($result) {
        while($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
    }
}

if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        "user_name" => $_SESSION['user_name'] ?? "Meryem",
        "rooms" => $rooms
    ]);
    exit;
}

include 'mainpage.html';
?>