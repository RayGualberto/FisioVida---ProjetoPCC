<?php
require_once '../php/db.php';
session_start();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: agendamentos.php');
    exit;
}

// Buscar agendamento atual
$stmt = $pdo->prepare("SELECT nome_paciente, descricao_servico, data, hora, paciente_id_paciente, fisioterapeuta_id FROM agenda WHERE id_Agenda = ?");
$stmt->execute([$id]);
$agendamento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agendamento) {
    header('Location: agendamentos.php');
    exit;
}

// Atualização após envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_data'], $_POST['nova_hora'])) {
    $novaData = $_POST['nova_data'];
    $novaHora = $_POST['nova_hora'];

    // Atualizar agendamento
    $stmtUpdate = $pdo->prepare("UPDATE agenda SET data = ?, hora = ?, status = 'remarcado' WHERE id_Agenda = ?");
    $stmtUpdate->execute([$novaData, $novaHora, $id]);

    // Criar mensagem de notificação
    $mensagem = "🔁 O paciente {$agendamento['nome_paciente']} remarcou a sessão de {$agendamento['descricao_servico']} para $novaData às $novaHora.";

    // Preparar inserção de notificação
    $stmtNotif = $pdo->prepare("
        INSERT INTO notificacoes (remetente_id, destinatario_id, mensagem, tipo)
        VALUES (?, ?, ?, 'remarcar')
    ");

    if ($agendamento['fisioterapeuta_id']) {
        // Notificação para fisioterapeuta vinculado
        $stmtNotif->execute([$agendamento['paciente_id_paciente'], $agendamento['fisioterapeuta_id'], $mensagem]);
    } else {
        // Notificação para todos os fisioterapeutas
        $stmtFisio = $pdo->query("SELECT id_Fisioterapeuta FROM fisioterapeuta");
        $todosFisio = $stmtFisio->fetchAll(PDO::FETCH_ASSOC);
        foreach ($todosFisio as $f) {
            $stmtNotif->execute([$agendamento['paciente_id_paciente'], $f['id_Fisioterapeuta'], $mensagem]);
        }
    }

    $_SESSION['msg'] = "Sessão remarcada com sucesso!";
    $_SESSION['msg_tipo'] = "sucesso";

    header('Location: agendamentos.php');
    exit;
}
