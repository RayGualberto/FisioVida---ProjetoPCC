<?php
require_once '../php/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: agendamentos.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: agendamentos.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // Verifica se o agendamento existe antes de excluir
    $stmtCheck = $pdo->prepare('SELECT id_Agenda FROM agenda WHERE id_Agenda = ?');
    $stmtCheck->execute([$id]);
    $existe = $stmtCheck->fetchColumn();

    if (!$existe) {
        $pdo->rollBack();
        header('Location: agendamentos.php');
        exit;
    }

    // Exclui o agendamento
    $stmt = $pdo->prepare('DELETE FROM agenda WHERE id_Agenda = ?');
    $stmt->execute([$id]);

    $pdo->commit();
    $_SESSION['msg'] = "Sessão excluída!";
    $_SESSION['msg_tipo'] = "erro";
} catch (PDOException $e) {
    $pdo->rollBack();
    die('Erro ao excluir agendamento: ' . $e->getMessage());
}

header('Location: agendamentos.php');
exit;
