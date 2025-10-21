<?php
function conecta(){
    $host = 'localhost';
    $db   = 'fisiovida';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $conn = new PDO($dsn, $user, $pass, $options);
        return $conn;
    } catch (PDOException $e) {
        die("Erro de conexão: " . $e->getMessage());
    }
}

function buscarUm($sql, $params){
    $pdo = conecta();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$pdo = conecta();
?>
