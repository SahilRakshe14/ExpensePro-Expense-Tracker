<?php
$host = "localhost";
$port = "5432";
$db = "expense_tracker";
$user = "postgres"; // Tumcha PSQL username
$pass = "1234"; // Tumcha PSQL password

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Connection check karnya sathi (Nantar hi line delete kara)
    // echo "Connected successfully!"; 
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
