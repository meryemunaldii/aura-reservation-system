<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Geri Bildirimlerim</title>
    <link rel="stylesheet" href="feedback_list.css">
</head>
<body>
    <nav class="aura-navbar">
        <div class="nav-logo">AURA</div>
        <ul class="nav-links">
            <li><a href="mainpage.php">Ana Sayfa</a></li>
            <li><a href="profil.php">Profilim</a></li>
            <li><a href="gecmis_rezervasyonlar.html" class="active">Geçmiş Rezervasyonlarım</a></li>
            <li><a href="cikis.php" class="logout-btn">Çıkış Yap</a></li>
        </ul>
    </nav>

   
    <header>
        <h1>Geri Bildirimlerim</h1>
    </header>

    <div class="table-container">
        <?php if (!empty($feedbacks)): ?>
        <table>
            <thead>
                <tr>
                    <th>Oda No</th>
                    <th>Puan</th>
                    <th>Yorum Detayı</th>
                    <th>Tarih</th>
                    <th style="text-align: center;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $fb): ?>
                <tr>
                    <td><strong style="color: #c5a059;"><?php echo $fb['RoomNo']; ?></strong></td>
                    <td class="stars-cell"><?php echo str_repeat('★', $fb['Rating']) . str_repeat('☆', 5-$fb['Rating']); ?></td>
                    <td><?php echo htmlspecialchars($fb['Comment']); ?></td>
                    <td><?php echo date("d.m.Y", strtotime($fb['FeedbackDate'])); ?></td>
                    <td style="text-align: center;">
                        <a href="feedback_guncelle.php?id=<?php echo $fb['FeedbackID']; ?>" class="edit-btn">DÜZENLE</a>
                        <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $fb['FeedbackID']; ?>)" class="delete-btn">SİL</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p style="text-align:center; padding:50px; color:#c5a059;">Henüz bir geri bildirim bırakmamışsınız.</p>
        <?php endif; ?>
    </div>

    <div class="nav-buttons">
        <a href="mainpage.php" class="btn btn-main">Ana Sayfaya Dön</a>
        <a href="feedback.php" class="btn btn-main">Yeni Geri Bildirim</a>
    </div>

    <script src="feedback_list.js"></script>
</body>
</html>
