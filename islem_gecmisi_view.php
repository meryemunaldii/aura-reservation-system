<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura | İşlem Geçmişi</title>
    <link rel="stylesheet" href="islem_gecmisi.css">
</head>
<body>

<div class="history-container">
    <h2>HESAP HAREKETLERİ</h2>
    <p class="description">Hesabınızda gerçekleştirilen son işlemlerin dökümü.</p>
    
    <div class="table-wrapper">
        <table class="action-table">
            <thead>
                <tr>
                    <th>İşlem Türü</th>
                    <th>Tarih ve Saat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($actions) > 0): ?>
                    <?php foreach ($actions as $action): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($action['ActionType']); ?></td>
                            <td class="date-cell"><?php echo date("d.m.Y - H:i", strtotime($action['ActionTime'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="no-data">Henüz kaydedilmiş bir işlem hareketi bulunamadı.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer-actions">
        <a href="mainpage.html" class="back-btn">← Ana Sayfaya Dön</a>
    </div>
</div>

</body>
</html>