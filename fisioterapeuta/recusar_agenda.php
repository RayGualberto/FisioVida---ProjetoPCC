<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../php/db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // Atualiza status do agendamento
        $stmt = $pdo->prepare("UPDATE agenda SET status = 'recusado' WHERE id_Agenda = ?");
        $stmt->execute([$id]);

        // Buscar dados
        $stmtInfo = $pdo->prepare("SELECT paciente_id_paciente, nome_paciente FROM agenda WHERE id_Agenda = ?");
        $stmtInfo->execute([$id]);
        $agenda = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if ($agenda) {
            // Envia notificação ao paciente
            $msg = "❌ Seu agendamento foi recusado pelo fisioterapeuta.";
            $stmtNotif = $pdo->prepare("INSERT INTO notificacoes (remetente_id, destinatario_id, mensagem, tipo, lida) VALUES (?, ?, ?, ?, 0)");
            $stmtNotif->execute([$_SESSION['user_id'], $agenda['paciente_id_paciente'], $msg, 'recusado']);
        }

        $_SESSION['msg'] = "❌ Agendamento recusado e notificação enviada.";
    } catch (PDOException $e) {
        $_SESSION['msg'] = "⚠️ Erro ao recusar: " . $e->getMessage();
    }
}

header("Location: fisio_dashboard.php");
exit;
