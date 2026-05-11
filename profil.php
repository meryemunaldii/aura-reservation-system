<?php
include 'baglan.php';
session_start();

$user_id = $_SESSION['user_id'];
$violation_sql = "SELECT * FROM UserViolations WHERE UserID = ? ORDER BY StartDate DESC";
$v_stmt = $conn->prepare($violation_sql);
$v_stmt->bind_param("i", $user_id);
$v_stmt->execute();
$violation_result = $v_stmt->get_result();


if (!isset($_SESSION['user_id'])) {
    header("Location: giris.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$mesaj = "";

$sorgu = $db->prepare("SELECT * FROM Users WHERE UserID = ?");
$sorgu->execute([$user_id]);
$user = $sorgu->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yeni_ad = $_POST['firstName'];
    $yeni_soyad = $_POST['lastName'];
    $yeni_email = $_POST['email'];
    $yeni_sifre = $_POST['sifre'];

if (!empty($yeni_sifre)) {

    $sifreli_sifre = hash('sha256', $yeni_sifre); 
    
    $sql = "UPDATE Users SET FirstName = ?, LastName = ?, Email = ?, Password = ? WHERE UserID = ?";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([$yeni_ad, $yeni_soyad, $yeni_email, $sifreli_sifre, $user_id]);
}

    if ($result) {
        $_SESSION['user_name'] = $yeni_ad . " " . $yeni_soyad;  
        $mesaj = "<p style='color: #d4af37;'>Bilgileriniz başarıyla güncellendi!</p>";
        header("Refresh: 2; url=profil.php");
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profilim | Aura Reservation</title>
    <link rel="stylesheet" href="mainpage.css">
    <style>
    
        .profile-wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 40px;
            padding: 50px 5%;
            flex-wrap: wrap;
        }

        .profile-card {
            background: #1e1e1e;
            width: 400px;
            padding: 30px;
            border-radius: 15px;
            border-top: 5px solid #d4af37;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            margin: 0;
        }

        .violations-container {
            background: #1e1e1e;
            flex: 1; 
            max-width: 600px;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #d4af37;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .profile-card h2, .violations-container h3 { color: #d4af37; text-align: center; margin-bottom: 20px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; color: #aaa; margin-bottom: 5px; font-size: 14px; }
        .form-group input {
            width: 100%;
            padding: 10px;
            background: #2a2a2a;
            border: 1px solid #444;
            color: white;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .update-btn {
            width: 100%;
            padding: 12px;
            background: #d4af37;
            border: none;
            color: #121212;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        .update-btn:hover { background: #b8962d; transform: translateY(-2px); }
        
        .info-text { font-size: 12px; color: #666; text-align: center; margin-top: 10px; }
        
        table { width: 100%; color: white; border-collapse: collapse; margin-top: 10px; }
        th { background: #d4af37; color: black; padding: 12px; }
        td { padding: 12px; border-bottom: 1px solid #333; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">AURA RESERVATION</div>
        <ul class="nav-links">
            <li><a href="mainpage.php">Ana Sayfa</a></li>
            <li><a href="gecmis_rezervasyonlar.html">Rezervasyonlarım</a></li>
            <li><a href="islem_gecmisi.html">İşlem Geçmişi</a></li>
            <li><a href="feedback.php">Geri Bildirim</a></li>
            <li><a href="logout.php" class="logout-btn">Çıkış Yap</a></li>
        </ul>
    </nav>

    <div class="profile-wrapper">
        
        <div class="profile-card">
            <h2>Profil Bilgileri</h2>
            <?php echo $mesaj; ?>
            <form action="profil.php" method="POST">
                <div class="form-group">
                    <label>Ad (FirstName)</label>
                    <input type="text" name="firstName" value="<?php echo htmlspecialchars($user['FirstName'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Soyad (LastName)</label>
                    <input type="text" name="lastName" value="<?php echo htmlspecialchars($user['LastName'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>E-posta</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Yeni Şifre (Boş bırakılabilir)</label>
                    <input type="password" name="sifre" placeholder="••••••••">
                </div>
                <button type="submit" class="update-btn">Bilgileri Güncelle</button>
            </form>
            <p class="info-text">ID: <?php echo $user['UserID']; ?> | Kayıt Tarihi: Sistem</p>
        </div>

        <div class="violations-container">
            <h3>İhlal ve Ceza Kayıtları</h3>
            <?php if ($violation_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Sebep</th>
                            <th>Ceza Tutarı</th>
                            <th>Bitiş Tarihi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($v = $violation_result->fetch_assoc()): ?>
                            <tr style="text-align: center;">
                                <td><?php echo $v['Reason']; ?></td>
                                <td><?php echo $v['FineAmount']; ?> TL</td>
                                <td style="color: <?php echo ($v['EndDate'] >= date('Y-m-d')) ? '#ff4444' : '#44ff44'; ?>; font-weight: bold;">
                                    <?php echo $v['EndDate']; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #44ff44; text-align: center;">Harika! Herhangi bir kural ihlaliniz bulunmuyor. ✨</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>