<?php
require_once 'db.php';
session_start();

$user_id = $_SESSION['usuario_id'] ?? 0;
if ($user_id === 0) exit;

$stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE destinatario_id = ?");
$stmt->execute([$user_id]);

echo json_encode(["status" => "ok"]);
