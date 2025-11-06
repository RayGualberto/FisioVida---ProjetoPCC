<?php
require_once 'db.php';
session_start();

$user_id = $_SESSION['usuario_id'] ?? 0;
if ($user_id === 0) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, mensagem, tipo, data_envio, lida 
                       FROM notificacoes 
                       WHERE destinatario_id = ? 
                       ORDER BY data_envio DESC 
                       LIMIT 10");
$stmt->execute([$user_id]);
$notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Conta notificações não lidas
$stmtCount = $pdo->prepare("SELECT COUNT(*) AS total FROM notificacoes WHERE destinatario_id = ? AND lida = 0");
$stmtCount->execute([$user_id]);
$total_nao_lidas = $stmtCount->fetchColumn();

echo json_encode([
    "total_nao_lidas" => $total_nao_lidas,
    "notificacoes" => $notificacoes
]);
