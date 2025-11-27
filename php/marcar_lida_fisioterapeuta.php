<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

// Buscar o CPF do fisioterapeuta logado
$stmt = $pdo->prepare("SELECT cpf FROM usuario WHERE id = ?");
$stmt->execute([$usuarioId]);
$cpf_usuario = $stmt->fetchColumn();

if (!$cpf_usuario) {
    echo json_encode(['success' => false]);
    exit;
}

// Marcar todas como lidas pelo CPF
$stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE destinatario_cpf = ?");
$stmt->execute([$cpf_usuario]);

echo json_encode(['success' => true]);
