<?php
require_once 'db.php';
session_start();

// Garante que o usuário esteja logado
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo_usuario'])) {
    echo json_encode([]);
    exit;
}

$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario']; // 'paciente' ou 'fisioterapeuta'

// Busca notificações mais recentes (as não lidas primeiro)
$sql = "SELECT id_notificacao, mensagem, status, data_envio 
        FROM notificacao 
        WHERE destinatario_id = ? AND tipo_destinatario = ?
        ORDER BY status ASC, data_envio DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id_usuario, $tipo_usuario);
$stmt->execute();
$result = $stmt->get_result();

$notificacoes = [];
while ($row = $result->fetch_assoc()) {
    $notificacoes[] = $row;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($notificacoes);
?>
