<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Aura | Güncelle</title>
    <link rel="stylesheet" href="feedback.css">
</head>
<body>
    <div class="form-container">
        <h2>YORUMU GÜNCELLE</h2>
        <form method="POST">
            <p style="color: #c5a059; margin-bottom: 20px;">
                Oda No: <strong><?php echo $feedback['RoomNo']; ?></strong>
            </p>
            
            <div class="star-rating">
                <?php for($i=5; $i>=1; $i--): ?>
                    <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" 
                        <?php echo ($feedback['Rating'] == $i) ? 'checked' : ''; ?>>
                    <label for="star<?php echo $i; ?>" class="star">&#9733;</label>
                <?php endfor; ?>
            </div>

            <textarea name="comment" rows="5" placeholder="Yorumunuzu güncelleyin..."><?php echo htmlspecialchars($feedback['Comment']); ?></textarea>
            
            <button type="submit" class="submit-btn">DEĞİŞİKLİKLERİ KAYDET</button>
            
            <div class="back-container">
                <a href="geribildirimlerim.php" class="back-link">Vazgeç ve Geri Dön</a>
            </div>
        </form>
    </div>

    <script src="feedback_guncelle.js"></script>
</body>
</html>