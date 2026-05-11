function silmeOnayi(id) {
    if (confirm("Bu yorumu silmek istediğinizden emin misiniz?")) {
        window.location.href = "feedback_sil.php?id=" + id;
    }
}
console.log("JavaScript dosyası başarıyla bağlandı!");
function confirmDelete(id) {
    if (confirm("Bu geri bildirimi kalıcı olarak silmek istediğinize emin misiniz?")) {
        window.location.href = "feedback_sil.php?id=" + id;
    }
}