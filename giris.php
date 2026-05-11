<?php
$servername = "localhost";
$username   = "root";
$password_db = ""; 
$dbname     = "officereservationdb";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $sifre = trim($_POST['password']); 

    $email = $conn->real_escape_string($email);
    $sifre_hash = strtolower(hash('sha256', $sifre)); 

    $sql = "SELECT UserID, FirstName, LastName, UserType FROM Users 
            WHERE Email = '$email' AND Password = '$sifre_hash'";
            
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
                                           
        $_SESSION['user_id']   = $user['UserID'];
        $_SESSION['user_name'] = $user['FirstName'] . " " . $user['LastName'];
        $_SESSION['user_type'] = $user['UserType']; 

        // --- LOG KAYDI BURADA OLMALI (Yönlendirmeden Önce) ---
        $current_user_id = $user['UserID'];
        $log_sql = "INSERT INTO actionhistory (UserID, ActionType, ActionTime) VALUES (?, 'Sisteme Giriş Yapıldı', NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("i", $current_user_id);
        $log_stmt->execute();
        // -----------------------------------------------------

        header("Location: mainpage.php");
        exit();
    } else {
        // Hata ayıklama modunu geçici olarak kapatabilirsin proje bitince
        echo "Hatalı email veya şifre!";
        exit;
    }
}

$conn->close();
?>