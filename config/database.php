<?php
    $host = 'localhost';
    $dbname = 'observatorio_cti';
    $username = 'root';
    $password = '12345678';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    } catch(PDOException $e) {
        die(json_encode([
            "error" => "Error de conexión: " . $e->getMessage()
        ]));
    }   
?>