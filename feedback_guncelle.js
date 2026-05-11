console.log("Aura Güncelleme Modülü Hazır.");

document.querySelector('form').onsubmit = function() {
    let comment = document.querySelector('textarea').value;
    if(comment.trim() === "") {
        alert("Lütfen bir yorum yazın.");
        return false;
    }
    return true;
};