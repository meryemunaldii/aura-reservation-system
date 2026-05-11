document.getElementById('feedbackForm').addEventListener('submit', function(event) {
    const comment = document.getElementById('Comment').value;
    const rating = document.getElementById('Rating').value;

    if (comment.length < 5) {
        alert("Lütfen biraz daha detaylı bir yorum yazın.");
        event.preventDefault(); // Formun gönderilmesini durdurur
    }

    if (rating < 1 || rating > 5) {
        alert("Puan 1 ile 5 arasında olmalıdır.");
        event.preventDefault();
    }
});