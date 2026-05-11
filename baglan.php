<?php
$host = 'localhost';
$dbname = 'officereservationdb';
$username = 'root';
$password = ''; 

try {
    
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
    }

} catch (PDOException $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}
?>