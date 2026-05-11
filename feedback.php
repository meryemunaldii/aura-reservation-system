<?php
include 'db.php'; 
session_start();

$user_id = $_SESSION['user_id'] ?? $_SESSION['UserID'] ?? null;

if (!$user_id) {
    header("Location: giris.html");
    exit();
}


$sql = "SELECT DISTINCT r.RoomID, r.RoomNo 
        FROM Reservations res
        JOIN Rooms r ON res.RoomID = r.RoomID
        WHERE res.UserID = ?"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Aura | Geri Bildirim</title>
    <link rel="stylesheet" href="feedback.css">

    <style>
        .back-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>GERI BILDIRIM</h2>
    <p>Sadece rezervasyon yaptığınız odalar listelenmektedir.</p>
    
    <form action="feedback_kaydet.php" method="POST">
        <label>Rezervasyon Yaptığınız Odayı Seçin</label>
        <select name="RoomID" required>
            <option value="">Oda Seçiniz...</option>
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['RoomID']."'>".$row['RoomNo']."</option>";
                }
            } else {
                echo "<option value='' disabled>Henüz bir rezervasyonunuz bulunmuyor.</option>";
            }
            ?>
        </select>

        <div class="star-rating">
            <input type="radio" id="star5" name="Rating" value="5" required><label for="star5">★</label>
            <input type="radio" id="star4" name="Rating" value="4"><label for="star4">★</label>
            <input type="radio" id="star3" name="Rating" value="3"><label for="star3">★</label>
            <input type="radio" id="star2" name="Rating" value="2"><label for="star2">★</label>
            <input type="radio" id="star1" name="Rating" value="1"><label for="star1">★</label>
        </div>

        <textarea name="Comment" placeholder="Oda hakkındaki deneyiminizi yazın..." rows="4"></textarea>
        
        <button type="submit" class="submit-btn">YORUMU GÖNDER</button>
        
        <a href="mainpage.html" class="back-btn">Vazgeç ve Ana Sayfaya Dön</a>
    </form>
</div>
</body>
</html>