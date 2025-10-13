<?php
session_start();
include '../php/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'paciente') {
    die("Acesso negado.");
}

$usuarioId = $_SESSION['usuario_id'];
$nome_paciente = $_POST['nome_paciente'];
$data = $_POST['data'];
$hora = $_POST['hora'];
$servico_id = $_POST['servico_id'];
$data_agendamento = date('Y-m-d');

// Buscar id_paciente correspondente ao usuário
$stmt = $conn->prepare("SELECT id_paciente FROM paciente WHERE cpf = (SELECT cpf FROM usuario WHERE id = ?)");
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$stmt->bind_result($id_paciente);
$stmt->fetch();
$stmt->close();

if (!$id_paciente) {
    die("Paciente não encontrado.");
}

// Buscar descrição do serviço
$stmt = $conn->prepare("SELECT descricao_servico FROM servico WHERE id_servico = ?");
$stmt->bind_param("i", $servico_id);
$stmt->execute();
$stmt->bind_result($descricao_servico);
$stmt->fetch();
$stmt->close();

// Inserir agendamento
$stmt = $conn->prepare("INSERT INTO agenda (nome_paciente, data, data_agendamento, hora, descricao_servico, paciente_id_paciente, servico_id_servico)
VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssii", $nome_paciente, $data, $data_agendamento, $hora, $descricao_servico, $id_paciente, $servico_id);

if ($stmt->execute()) {
    header("Location: paciente_dashboard.php?agendado=sucesso");
    exit();
} else {
    echo "Erro ao agendar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
