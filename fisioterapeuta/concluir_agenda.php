<?php
session_start();
require '../php/db.php'; // conexão

// ======= PEGAR CPF DO FISIOTERAPEUTA =======
$cpfFisioterapeuta = $_SESSION['cpf_fisioterapeuta'] ?? null;

if (!$cpfFisioterapeuta) {
    header('Location: agenda.php');
    exit;
}

// ======= BUSCAR ID DO FISIO =======
$stmtFisio = $pdo->prepare("SELECT id_fisioterapeuta FROM fisioterapeuta WHERE cpf = ?");
$stmtFisio->execute([$cpfFisioterapeuta]);
$fisioterapeuta = $stmtFisio->fetch(PDO::FETCH_ASSOC);

if (!$fisioterapeuta) {
    header('Location: agenda.php');
    exit;
}

$fisioterapeutaId = (int)$fisioterapeuta['id_fisioterapeuta'];


// ======= PEGAR ID DA AGENDA =======
if (!isset($_POST['id']) || empty($_POST['id'])) {
    header('Location: agenda.php');
    exit;
}

$idAgenda = (int)$_POST['id'];

try {

    // ================================
    // 1️⃣ BUSCAR ID DO PACIENTE
    // ================================
    $stmt = $pdo->prepare("
        SELECT paciente_id_paciente
        FROM agenda
        WHERE id_Agenda = ?
        LIMIT 1
    ");
    $stmt->execute([$idAgenda]);
    $agenda = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$agenda) {
        throw new Exception("Agenda não encontrada.");
    }

    $idPaciente = $agenda['paciente_id_paciente'];

    // ================================
    // 2️⃣ BUSCAR CPF DO PACIENTE
    // ================================
    $stmtPac = $pdo->prepare("
        SELECT cpf 
        FROM paciente 
        WHERE id_paciente = ?
    ");
    $stmtPac->execute([$idPaciente]);
    $paciente = $stmtPac->fetch(PDO::FETCH_ASSOC);

    if (!$paciente) {
        throw new Exception("Paciente não encontrado.");
    }

    $cpfPaciente = $paciente['cpf']; // CPF REAL do paciente



    // === INICIAR TRANSAÇÃO ===
    $pdo->beginTransaction();

    // 3️⃣ INSERIR ATENDIMENTO
    $stmtInsert = $pdo->prepare("
        INSERT INTO atendimento (data, agenda_id, fisioterapeuta_id)
        VALUES (NOW(), ?, ?)
    ");
    $stmtInsert->execute([$idAgenda, $fisioterapeutaId]);

    // 4️⃣ MARCAR AGENDA COMO CONCLUÍDA
    $stmtUpdate = $pdo->prepare("
        UPDATE agenda 
        SET status = 'concluido'
        WHERE id_Agenda = ?
    ");
    $stmtUpdate->execute([$idAgenda]);

    // 5️⃣ ENVIAR NOTIFICAÇÃO AO PACIENTE
    $mensagem = "Sua sessão foi concluída pelo fisioterapeuta.";
    $tipo = "sessao_concluida";

    $stmtNotif = $pdo->prepare("
        INSERT INTO notificacoes (destinatario_cpf, remetente_cpf, mensagem, tipo)
        VALUES (?, ?, ?, ?)
    ");
    $stmtNotif->execute([$cpfPaciente, $cpfFisioterapeuta, $mensagem, $tipo]);

    // Confirmar tudo
    $pdo->commit();

    $_SESSION['msg'] = "Sessão concluída com sucesso!";
    $_SESSION['msg_tipo'] = "sucesso";

    header("Location: agenda.php?concluido=1&id=$idAgenda");
    exit;

} catch (Exception $e) {

    // rollback só se houver transação ativa
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo "Erro: " . $e->getMessage();
    exit;
}