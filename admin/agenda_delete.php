<?php
require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: admin.php');
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
        header('Location: admin.php');
        exit;
    }

    // Exclui o agendamento
    $stmt = $pdo->prepare('DELETE FROM agenda WHERE id_Agenda = ?');
    $stmt->execute([$id]);

    $pdo->commit();

} catch (PDOException $e) {
    $pdo->rollBack();
    die('Erro ao excluir agendamento: ' . $e->getMessage());
}

header('Location: admin.php');
exit;
