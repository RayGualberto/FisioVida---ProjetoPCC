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
    $pdo->beginTransaction();

    // 1️⃣ Busca o CPF do usuário antes de deletar
    $stmtCpf = $pdo->prepare('SELECT cpf FROM usuario WHERE id = ?');
    $stmtCpf->execute([$id]);
    $cpf = $stmtCpf->fetchColumn();

    if ($cpf) {
        // 2️⃣ Busca o ID do paciente com o mesmo CPF (se existir)
        $stmtPacienteId = $pdo->prepare('SELECT id_paciente FROM paciente WHERE cpf = ?');
        $stmtPacienteId->execute([$cpf]);
        $id_paciente = $stmtPacienteId->fetchColumn();

        if ($id_paciente) {
            // 3️⃣ Exclui registros da agenda relacionados a esse paciente
            $stmtAgenda = $pdo->prepare('DELETE FROM agenda WHERE paciente_id_paciente = ?');
            $stmtAgenda->execute([$id_paciente]);

            // 4️⃣ Exclui o paciente da tabela 'paciente'
            $stmtPaciente = $pdo->prepare('DELETE FROM paciente WHERE id_paciente = ?');
            $stmtPaciente->execute([$id_paciente]);
        }
    }

    // 5️⃣ Exclui o usuário da tabela 'usuario'
    $stmtUsuario = $pdo->prepare('DELETE FROM usuario WHERE id = ?');
    $stmtUsuario->execute([$id]);

    $pdo->commit();

} catch (PDOException $e) {
    $pdo->rollBack();
    die('Erro ao excluir: ' . $e->getMessage());
}

header('Location: admin.php');
exit;
