<?php
$servername = "localhost";
$username   = "root";
$password_db = ""; 
$dbname     = "officereservationdb";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ad    = $conn->real_escape_string(trim($_POST['ad']));
    $soyad = $conn->real_escape_string(trim($_POST['soyad']));
    $mail  = $conn->real_escape_string(trim($_POST['mail']));
    $tel   = $conn->real_escape_string(trim($_POST['tel']));   

    $gelen_sifre = $_POST['password']; 

    $sifre_hash = strtolower(hash('sha256', $gelen_sifre));

    $tip = $_POST['tip']; 
    $userType = ($tip == "bireysel") ? "Student" : "Academician";

    $randomID = rand(100000, 999999); 

    $sql = "INSERT INTO Users (UserID, FirstName, LastName, Email, Phone, Password, UserType) 
            VALUES ('$randomID', '$ad', '$soyad', '$mail', '$tel', '$sifre_hash', '$userType')";

    if ($conn->query($sql) === TRUE) {

        echo "<script>alert('Kayıt Başarılı! Kullanıcı ID: $randomID'); window.location.href='giris.html';</script>";
    } else {
        echo "Hata: " . $conn->error;
    }
}
$conn->close();
?>