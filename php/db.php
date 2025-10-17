<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fisiovida";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$db = $conn;