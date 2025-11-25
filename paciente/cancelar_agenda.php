<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../php/db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // Atualiza status do agendamento
        $stmt = $pdo->prepare("UPDATE agenda SET status = 'recusado' WHERE id_Agenda = ?");
        $stmt->execute([$id]);

        // Buscar dados do agendamento
        $stmtInfo = $pdo->prepare("
            SELECT a.nome_paciente, a.descricao_servico, a.data, a.hora, a.paciente_id_paciente, a.fisioterapeuta_id
            FROM agenda a
            WHERE a.id_Agenda = ?
        ");
        $stmtInfo->execute([$id]);
        $ag = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if ($ag) {
            $mensagem = "❌ O paciente {$ag['nome_paciente']} cancelou a sessão de {$ag['descricao_servico']} marcada para {$ag['data']} às {$ag['hora']}.";

            // Buscar CPF do paciente (remetente)
            $stmtCpf = $pdo->prepare("SELECT cpf FROM paciente WHERE id_paciente = ?");
            $stmtCpf->execute([$ag['paciente_id_paciente']]);
            $remetenteCpf = $stmtCpf->fetchColumn();

            // Preparar inserção de notificação (usando CPF)
            $stmtNotif = $pdo->prepare("
                INSERT INTO notificacoes (remetente_cpf, destinatario_cpf, mensagem, tipo)
                VALUES (?, ?, ?, 'cancelamento')
            ");

            if ($ag['fisioterapeuta_id']) {
                // Notificação para fisioterapeuta vinculado
                $stmtCpfFisio = $pdo->prepare("SELECT cpf FROM fisioterapeuta WHERE id_fisioterapeuta = ?");
                $stmtCpfFisio->execute([$ag['fisioterapeuta_id']]);
                $destinatarioCpf = $stmtCpfFisio->fetchColumn();

                $stmtNotif->execute([$remetenteCpf, $destinatarioCpf, $mensagem]);
            } else {
                // Notificação para todos os fisioterapeutas
                $stmtFisio = $pdo->query("SELECT cpf FROM fisioterapeuta");
                $todosFisio = $stmtFisio->fetchAll(PDO::FETCH_ASSOC);
                foreach ($todosFisio as $f) {
                    $stmtNotif->execute([$remetenteCpf, $f['cpf'], $mensagem]);
                }
            }
        }

        $_SESSION['msg'] = "Sessão cancelada!";
        $_SESSION['msg_tipo'] = "erro";

    } catch (PDOException $e) {
        $_SESSION['msg'] = "⚠️ Erro ao recusar: " . $e->getMessage();
        $_SESSION['msg_tipo'] = "erro";
    }
}

header("Location: agendamentos.php");
exit;
