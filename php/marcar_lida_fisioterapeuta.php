<?php
require_once 'db.php';
session_start();

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'Usuário não logado']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

try {
    // Buscar id_fisioterapeuta associado ao usuário
    $stmt = $pdo->prepare("
        SELECT f.id_fisioterapeuta 
        FROM fisioterapeuta f
        INNER JOIN usuario u ON f.cpf = u.cpf
        WHERE u.id = ?
        LIMIT 1
    ");
    $stmt->execute([$usuarioId]);
    $id_fisioterapeuta = $stmt->fetchColumn();

    if (!$id_fisioterapeuta) {
        echo json_encode(['error' => 'Fisioterapeuta não encontrado para o usuário informado']);
        exit;
    }

    // Marcar todas as notificações como lidas para este fisioterapeuta
    $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE destinatario_id = ?");
    $stmt->execute([$id_fisioterapeuta]);

    echo json_encode(['success' => true, 'msg' => 'Notificações marcadas como lidas']);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
