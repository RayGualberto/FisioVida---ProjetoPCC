<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'Usuário não logado']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

try {
    $stmt = $pdo->prepare("SELECT id_paciente FROM paciente WHERE cpf = (SELECT cpf FROM usuario WHERE id = ?)");
    $stmt->execute([$usuarioId]);
    $id_paciente = $stmt->fetchColumn();

    if (!$id_paciente) {
        echo json_encode(['notificacoes' => [], 'total_nao_lidas' => 0]);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT n.id, n.mensagem, n.tipo, n.data_envio, n.lida, u.nome AS remetente_nome
        FROM notificacoes n
        LEFT JOIN usuario u ON u.id = n.remetente_id
        WHERE n.destinatario_id = ?
        ORDER BY n.data_envio DESC
    ");
    $stmt->execute([$id_paciente]);
    $notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_nao_lidas = 0;
    foreach ($notificacoes as $n) {
        if ($n['lida'] == 0) $total_nao_lidas++;
    }

    echo json_encode(['notificacoes' => $notificacoes, 'total_nao_lidas' => $total_nao_lidas]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
