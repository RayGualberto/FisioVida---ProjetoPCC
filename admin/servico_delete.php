<?php
require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: servicos.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: servicos.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // Verifica se o serviço existe antes de excluir
    $stmtCheck = $pdo->prepare('SELECT id_servico FROM servico WHERE id_servico = ?');
    $stmtCheck->execute([$id]);
    $existe = $stmtCheck->fetchColumn();

    if (!$existe) {
        $pdo->rollBack();
        header('Location: servicos.php');
        exit;
    }

    // Remove registros relacionados em outras tabelas
    $stmtAgenda = $pdo->prepare('DELETE FROM agenda WHERE servico_id_servico = ?');
    $stmtAgenda->execute([$id]);

    // Exclui o serviço principal
    $stmt = $pdo->prepare('DELETE FROM servico WHERE id_servico = ?');
    $stmt->execute([$id]);

    $pdo->commit();

} catch (PDOException $e) {
    $pdo->rollBack();
    die('Erro ao excluir serviço: ' . $e->getMessage());
}

header('Location: servicos.php');
exit;
