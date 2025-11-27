<?php
require_once 'db.php';
session_start();

header('Content-Type: application/json; charset=utf-8');

// Verifica login
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não logado']);
    exit;
}

$usuarioId = (int) $_SESSION['usuario_id'];

try {

    // Buscar CPF do usuário logado
    $stmt = $pdo->prepare("SELECT cpf FROM usuario WHERE id = ?");
    $stmt->execute([$usuarioId]);
    $cpf = $stmt->fetchColumn();

    if (!$cpf) {
        echo json_encode(['success' => false, 'error' => 'CPF não encontrado']);
        exit;
    }

    // Atualizar notificações pelo CPF
    $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE destinatario_cpf = ?");
    $stmt->execute([$cpf]);

    $rows = $stmt->rowCount();

    echo json_encode([
        'success' => true,
        'rows_updated' => $rows,
        'cpf' => $cpf
    ]);
    exit;

} catch (PDOException $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}
