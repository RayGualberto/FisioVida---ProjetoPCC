<?php
session_start();
require '../php/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$idUsuario = $_SESSION['usuario_id'];

try {

    $sql = "UPDATE usuario SET nome = ?, email = ?, telefone = ?, cep = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data['nome'],
        $data['email'],
        $data['telefone'],
        $data['cep'],
        $idUsuario
    ]);

    echo json_encode(['status' => 'ok']);

    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => $e->getMessage()
    ]);
}
?>