<?php
session_start();
require '../php/db.php'; // conexão

// ======= PEGAR CPF DO FISIOTERAPEUTA =======
// Aqui você escolhe de onde vem o CPF:

// 1. da sessão:
$cpfFisioterapeuta = $_SESSION['cpf_fisioterapeuta'] ?? null;

// 2. ou do formulário:
// $cpfFisioterapeuta = $_POST['cpf'] ?? null;

// Se não houver CPF → não deixa continuar
if (!$cpfFisioterapeuta) {
    header('Location: agenda.php');
    exit;
}

// ======= BUSCAR O ID DO FISIO PELO CPF =======
$stmtFisio = $pdo->prepare("
    SELECT id_fisioterapeuta 
    FROM fisioterapeuta 
    WHERE cpf = ?
");
$stmtFisio->execute([$cpfFisioterapeuta]);
$fisioterapeuta = $stmtFisio->fetch(PDO::FETCH_ASSOC);

if (!$fisioterapeuta) {
    header('Location: agenda.php');
    exit;
}

$fisioterapeutaId = (int)$fisioterapeuta['id_fisioterapeuta'];


// ======= PEGAR O ID DA AGENDA =======
if (!isset($_POST['id']) || empty($_POST['id'])) {
    header('Location: agenda.php');
    exit;
}

$idAgenda = (int)$_POST['id'];

try {
    // Verificar se a agenda existe
    $stmt = $pdo->prepare("SELECT id_Agenda FROM agenda WHERE id_Agenda = ?");
    $stmt->execute([$idAgenda]);
    $agenda = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$agenda) {
        header('Location: agenda.php');
        exit;
    }

    // Iniciar transação
    $pdo->beginTransaction();

    // Inserir atendimento COM fisioterapeuta
    $stmtInsert = $pdo->prepare("
        INSERT INTO atendimento (data, agenda_id, fisioterapeuta_id)
        VALUES (NOW(), ?, ?)
    ");
    $stmtInsert->execute([$idAgenda, $fisioterapeutaId]);

    // Atualizar status
    $stmtUpdate = $pdo->prepare("
        UPDATE agenda SET status = 'concluido'
        WHERE id_Agenda = ?
    ");
    $stmtUpdate->execute([$idAgenda]);

    // Commit
    $pdo->commit();

    $_SESSION['msg'] = "Sessão concluída com sucesso!";
    $_SESSION['msg_tipo'] = "sucesso";

    header('Location: agenda.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    header('Location: agenda.php');
    exit;
}
header("Location: agenda.php?concluido=1&id=$idAgenda");
exit;

