document.addEventListener("DOMContentLoaded", function() {
    // 1. Tarihi Bugüne Ayarla
    const dateInput = document.getElementById('filterDate');
    if(dateInput) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }
   
    loadRooms(); 
});

function loadRooms() {
    const tarih = document.getElementById('filterDate').value;
    const saat = document.getElementById('filterTime').value;
    const tipi = document.getElementById('filterType').value;

    const container = document.getElementById('roomContainer');
    container.innerHTML = "<p style='color:white; text-align:center;'>Odalar aranıyor...</p>";

    fetch(`mainpage.php?ajax=1&tarih=${tarih}&saat=${saat}&oda_tipi=${tipi}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('userNameDisplay').innerText = data.user_name;
            container.innerHTML = ""; 

            if (data.rooms.length === 0) {
                container.innerHTML = "<p style='color:white; grid-column: 1/-1; text-align:center;'>Uygun oda bulunamadı.</p>";
                return;
            }

            data.rooms.forEach(room => {
               
                const roomData = encodeURIComponent(JSON.stringify(room));
                
                container.innerHTML += `
                    <div class="room-card">
                        <h4>Oda: ${room.RoomNo}</h4>
                        <p>Tip: ${room.RoomType}</p>
                        <p>Kapasite: ${room.Capacity}</p>
                        <div style="display: flex; gap: 5px; margin-top: 10px;">
                            <button onclick="confirmRes(${room.RoomID})" class="rezerve-btn" style="flex: 2;">REZERVE ET</button>
                            <button onclick="showDetails('${roomData}')" class="detail-btn" 
                                    style="flex: 1; background: #444; color: #ffd700; border: 1px solid #ffd700; cursor: pointer; border-radius: 5px;">
                                DETAY
                            </button>
                        </div>
                    </div>`;
            });
        })
        .catch(error => {
            console.error('Hata:', error);
            container.innerHTML = "<p style='color:red; text-align:center;'>Veriler yüklenemedi!</p>";
        });
}

function showDetails(encodedRoom) {
    const room = JSON.parse(decodeURIComponent(encodedRoom));
    const modal = document.getElementById('roomDetailModal');
    const modalBody = document.getElementById('modalBody');
    const modalTitle = document.getElementById('modalRoomNo');

    modalTitle.innerText = "Oda Detayı: " + room.RoomNo;
    
    let ozellikler = "";
    if(room.RoomType === 'Group') {
        ozellikler = `<li><b>Projeksiyon:</b> ${room.HasProjector === 'Y' ? 'Var ✅' : 'Yok ❌'}</li>`;
    } else if(room.RoomType === 'Individual') {
        ozellikler = `
            <li><b>Sessiz Alan:</b> ${room.IsQuietZone === 'Y' ? 'Evet 🤫' : 'Hayır'}</li>
            <li><b>Masa Lambası:</b> ${room.HasDeskLamp === 'Y' ? 'Var 💡' : 'Yok'}</li>`;
    } else if(room.RoomType === 'Seminar') {
        ozellikler = `
            <li><b>Ses Sistemi:</b> ${room.HasSoundSystem === 'Y' ? 'Var 🎤' : 'Yok'}</li>
            <li><b>Sahne:</b> ${room.HasStage === 'Y' ? 'Var 🎭' : 'Yok'}</li>
            <li><b>Koltuk Tipi:</b> ${room.SeatType}</li>`;
    }

    modalBody.innerHTML = `
        <ul style="list-style: none; padding: 0; color: white; line-height: 2;">
            <li><b>Kat:</b> ${room.Floor}. Kat</li>
            <li><b>Kapasite:</b> ${room.Capacity} Kişi</li>
            ${ozellikler}
            <li style="margin-top: 10px; color: #ffd700; border-top: 1px solid #444; padding-top: 10px;">
                <b>Mevcut Ekipmanlar:</b><br> ${room.Ekipmanlar || 'Standart Donanım'}
            </li>
        </ul>
    `;

    modal.style.display = "block";

    document.getElementById('modalReserveBtn').onclick = function() {
        closeModal();
        confirmRes(room.RoomID);
    };
}

function closeModal() {
    document.getElementById('roomDetailModal').style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById('roomDetailModal');
    if (event.target == modal) {
        closeModal();
    }
}

function sifirlaForm() {
    document.getElementById("filterForm").reset();
    document.getElementById('filterDate').value = new Date().toISOString().split('T')[0];
    loadRooms();
}

function confirmRes(id) {
    const tarih = document.getElementById('filterDate').value;
    const saat = document.getElementById('filterTime').value;

    if (!confirm(`Oda ID: ${id} için rezervasyon yapmak istiyor musunuz?`)) return;

    const formData = new FormData();
    formData.append('room_id', id);
    formData.append('tarih', tarih);
    formData.append('saat', saat);

    fetch('rezervasyon_yap.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert(data.message);
            loadRooms();
        } else {
            alert("Hata: " + data.message);
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        alert("Rezervasyon sırasında bir bağlantı hatası oluştu.");
    });
}