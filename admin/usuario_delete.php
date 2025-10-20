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

session_start();

// Evita deletar a si mesmo
if ($id === (int)($_SESSION['user_id'] ?? 0)) {
    header('Location: admin.php');
    exit;
}

try {
    $conn->beginTransaction();

    // 1️⃣ Busca o CPF do usuário antes de deletar
    $stmtCpf = $conn->prepare('SELECT cpf FROM usuario WHERE id = ?');
    $stmtCpf->execute([$id]);
    $cpf = $stmtCpf->fetchColumn();

    if ($cpf) {
        // 2️⃣ Deleta o paciente com o mesmo CPF (se existir)
        $stmtPaciente = $conn->prepare('DELETE FROM paciente WHERE cpf = ?');
        $stmtPaciente->execute([$cpf]);
    }

    // 3️⃣ Exclui o usuário da tabela 'usuario'
    $stmtUsuario = $conn->prepare('DELETE FROM usuario WHERE id = ?');
    $stmtUsuario->execute([$id]);

    $conn->commit();

} catch (PDOException $e) {
    $conn->rollBack();
    die('Erro ao excluir: ' . $e->getMessage());
}

header('Location: admin.php');
exit;
