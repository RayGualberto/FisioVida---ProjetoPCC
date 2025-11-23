<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'Usuário não logado']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

try {
    // Buscar id_paciente associado ao usuário
    $stmt = $pdo->prepare("SELECT id_paciente FROM paciente WHERE cpf = (SELECT cpf FROM usuario WHERE id = ?)");
    $stmt->execute([$usuarioId]);
    $id_paciente = $stmt->fetchColumn();

    if ($id_paciente) {
        // Marcar todas notificações como lidas
        $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE destinatario_id = ?");
        $stmt->execute([$id_paciente]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
