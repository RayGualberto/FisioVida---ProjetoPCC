<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../php/db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // Verifica se o usuário está logado
        $usuarioId = $_SESSION['usuario_id'] ?? null;
        if (empty($usuarioId)) {
            throw new Exception("Usuário não autenticado. ID ausente na sessão.");
        }

        // Buscar CPF do remetente (usuário logado)
        $stmtCpfRem = $pdo->prepare("SELECT cpf FROM usuario WHERE id = ?");
        $stmtCpfRem->execute([$usuarioId]);
        $remetenteCpf = $stmtCpfRem->fetchColumn();
        if (!$remetenteCpf) {
            throw new Exception("CPF do remetente não encontrado.");
        }

        // Atualiza status do agendamento para confirmado
        $stmt = $pdo->prepare("UPDATE agenda SET status = 'confirmado' WHERE id_Agenda = ?");
        $stmt->execute([$id]);

        // Buscar dados do paciente
        $stmtInfo = $pdo->prepare("
            SELECT paciente_id_paciente, nome_paciente
            FROM agenda
            WHERE id_Agenda = ?
        ");
        $stmtInfo->execute([$id]);
        $agenda = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if ($agenda) {
            // Buscar CPF do paciente (destinatário)
            $stmtCpfDest = $pdo->prepare("SELECT cpf FROM paciente WHERE id_paciente = ?");
            $stmtCpfDest->execute([$agenda['paciente_id_paciente']]);
            $destinatarioCpf = $stmtCpfDest->fetchColumn();
            if (!$destinatarioCpf) {
                throw new Exception("CPF do destinatário não encontrado.");
            }

            // Envia notificação ao paciente
            $msg = "✅ Sua sessão foi confirmada com sucesso.";
            $stmtNotif = $pdo->prepare("
                INSERT INTO notificacoes (remetente_cpf, destinatario_cpf, mensagem, tipo, lida)
                VALUES (?, ?, ?, 'aceito', 0)
            ");
            $stmtNotif->execute([
                $remetenteCpf,
                $destinatarioCpf,
                $msg
            ]);
        }

        $_SESSION['msg'] = "Sessão confirmada com sucesso!";
        $_SESSION['msg_tipo'] = "sucesso";

    } catch (Exception $e) {
        $_SESSION['msg'] = "⚠️ Erro ao confirmar: " . $e->getMessage();
        $_SESSION['msg_tipo'] = "erro";
    }
}

header("Location: agenda.php");
exit;

