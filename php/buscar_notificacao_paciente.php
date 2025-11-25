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
    // Busca o CPF do usuário logado
    $stmt = $pdo->prepare("SELECT cpf FROM usuario WHERE id = ?");
    $stmt->execute([$usuarioId]);
    $cpf_usuario = $stmt->fetchColumn();

    if (!$cpf_usuario) {
        echo json_encode(['notificacoes' => [], 'total_nao_lidas' => 0]);
        exit;
    }

    // Busca notificações do paciente pelo CPF
    $stmt = $pdo->prepare("
        SELECT n.id, n.mensagem, n.tipo, n.data_envio, n.lida,
               u.nome AS remetente_nome
        FROM notificacoes n
        LEFT JOIN usuario u ON u.cpf = n.remetente_cpf
        WHERE n.destinatario_cpf = ?
        ORDER BY n.data_envio DESC
    ");
    $stmt->execute([$cpf_usuario]);
    $notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contar notificações não lidas
    $total_nao_lidas = 0;
    foreach ($notificacoes as $n) {
        if ($n['lida'] == 0) {
            $total_nao_lidas++;
        }
    }

    echo json_encode([
        'notificacoes' => $notificacoes,
        'total_nao_lidas' => $total_nao_lidas
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
