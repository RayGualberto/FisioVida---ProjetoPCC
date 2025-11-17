<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../php/db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // Verifica se o usuário está logado
        $remetente_id = $_SESSION['usuario_id'] ?? $_SESSION['id'] ?? null;
        if (empty($remetente_id)) {
            throw new Exception("Usuário não autenticado. ID ausente na sessão.");
        }

        // Atualiza status do agendamento para confirmado
        $stmt = $pdo->prepare("UPDATE agenda SET status = 'confirmado' WHERE id_Agenda = ?");
        $stmt->execute([$id]);

        // Buscar dados do paciente
        $stmtInfo = $pdo->prepare("SELECT paciente_id_paciente, nome_paciente FROM agenda WHERE id_Agenda = ?");
        $stmtInfo->execute([$id]);
        $agenda = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if ($agenda) {
            // Envia notificação ao paciente
            $msg = "✅ Sua sessão foi confirmada com sucesso.";
            $stmtNotif = $pdo->prepare("
                INSERT INTO notificacoes (remetente_id, destinatario_id, mensagem, tipo, lida)
                VALUES (?, ?, ?, ?, 0)
            ");
            $stmtNotif->execute([
                $remetente_id,                    // remetente
                $agenda['paciente_id_paciente'],  // destinatário
                $msg,
                'aceito'
            ]);
        }

        $_SESSION['msg'] = "Sessão confirmada com sucesso!";
        $_SESSION['msg_tipo'] = "sucesso";
    } catch (Exception $e) {
        $_SESSION['msg'] = "⚠️ Erro ao confirmar: " . $e->getMessage();
    }
}

header("Location: agenda.php");
exit;
