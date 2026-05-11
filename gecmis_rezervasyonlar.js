document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('historyTableContainer');

    fetch('gecmis_rezervasyonlar.php')
        .then(response => {
            if (!response.ok) throw new Error('Sunucu hatası!');
            return response.json();
        })
        .then(data => {
            container.innerHTML = ""; 

            if (data.length === 0) {
                container.innerHTML = "<p style='color: white; text-align: center;'>Henüz bir rezervasyonunuz bulunmamaktadır.</p>";
                return;
            }


            let html = `
                <table style="width:100%; color:white; border-collapse: collapse; border: 1.5px solid #ffd700; margin-top: 20px;">
                    <thead>
                        <tr style="background: #ffd700; color: black;">
                            <th style="padding: 12px; border: 1px solid #000;">Oda No</th>
                            <th style="padding: 12px; border: 1px solid #000;">Tarih</th>
                            <th style="padding: 12px; border: 1px solid #000;">Saat</th>
                            <th style="padding: 12px; border: 1px solid #000;">Durum</th>
                            <th style="padding: 12px; border: 1px solid #000;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>`;

            data.forEach(res => {
    // Veritabanından gelen veriye göre Türkçe karşılığını belirleyelim
    let durumMetni = res.ApprovalStatus === "Approved" ? "Onaylandı" : "Beklemede";
    let durumRengi = res.ApprovalStatus === "Approved" ? "#00ff00" : "#ffd700"; // Onaylıysa yeşil, değilse altın sarısı

    html += `
        <tr style="border-bottom: 1px solid #444; text-align: center;">
            <td style="padding: 15px;">${res.RoomNo}</td>
            <td>${res.ReservationDate}</td> 
            <td>${res.StartTime}</td> 
            <td style="color: ${durumRengi}; font-weight: bold;">${durumMetni}</td>
            <td style="padding: 10px;">
                <button onclick="rezervasyonSil(${res.ReservationID})" 
                        style="background-color: #ff4444; color: white; border: none; padding: 5px 15px; cursor: pointer; border-radius: 4px; font-weight: bold;">
                    SİL
                </button>
            </td>
        </tr>`;
});

            html += `</tbody></table>`;
            container.innerHTML = html;
        })
        .catch(error => {
            console.error("Hata detayı:", error);
            container.innerHTML = "<p style='color: red; text-align: center;'>Veriler ekrana basılırken bir hata oluştu!</p>"; 
        });
});

function rezervasyonSil(id) {
    if (confirm("Bu rezervasyonu kalıcı olarak silmek istediğinize emin misiniz?")) {
        const formData = new FormData();
        formData.append('reservation_id', id);

        fetch('rezervasyon_sil.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert(data.message);
                location.reload(); 
            } else {
                alert("Hata: " + data.message);
            }
        })
        .catch(error => {
            console.error("Hata:", error);
            alert("Silme işlemi sırasında bir sorun oluştu.");
        });
    }
}