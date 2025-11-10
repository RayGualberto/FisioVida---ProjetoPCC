<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_POST['notificacao_id'])) {
    echo json_encode(['error' => 'Dados insuficientes']);
    exit;
}

$notificacaoId = (int) $_POST['notificacao_id'];

try {
    $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE id = ?");
    $stmt->execute([$notificacaoId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
