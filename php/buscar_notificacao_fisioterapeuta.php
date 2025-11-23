<?php
// buscar_notificacao_fisioterapeuta.php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['notificacoes' => [], 'total_nao_lidas' => 0]);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

try {
    // Busca o ID do fisioterapeuta logado
    $stmt = $pdo->prepare("
        SELECT f.id_fisioterapeuta
        FROM fisioterapeuta f
        JOIN usuario u ON f.cpf = u.cpf
        WHERE u.id = ?
        LIMIT 1
    ");
    $stmt->execute([$usuarioId]);
    $id_fisioterapeuta = $stmt->fetchColumn();

    if (!$id_fisioterapeuta) {
        echo json_encode(['notificacoes' => [], 'total_nao_lidas' => 0]);
        exit;
    }

    // Busca notificações do fisioterapeuta
    $stmt = $pdo->prepare("
        SELECT n.id AS id_notificacao, n.mensagem, n.tipo, n.data_envio, n.lida,
               u.nome AS remetente_nome
        FROM notificacoes n
        LEFT JOIN usuario u ON u.id = n.remetente_id
        WHERE n.destinatario_id = ?
        ORDER BY n.data_envio DESC
        LIMIT 50
    ");
    $stmt->execute([$id_fisioterapeuta]);
    $notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Conta não lidas
    $total_nao_lidas = 0;
    foreach ($notificacoes as $n) {
        if ($n['lida'] == 0) $total_nao_lidas++;
    }

    echo json_encode([
        'notificacoes' => $notificacoes,
        'total_nao_lidas' => $total_nao_lidas
    ]);

} catch (PDOException $e) {
    echo json_encode(['notificacoes' => [], 'total_nao_lidas' => 0, 'error' => $e->getMessage()]);
}
