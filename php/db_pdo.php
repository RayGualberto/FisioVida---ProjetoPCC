<?php


function conecta(){

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "fisiovida";

    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}

function buscarUm($sql, $params = []) {

    try {
        $pdo = conecta();
        // 1. Prepara a consulta
        $stmt = $pdo->prepare($sql);

        // 2. Executa com os parâmetros passados
        $stmt->execute($params);

        // 3. Retorna um único registro (ou null se não houver resultado)
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;

    } catch (PDOException $e) {
        // Tratamento de erro (opcional, pode registrar em log)
        error_log("Erro no banco: " . $e->getMessage());
        return null;
    }
}