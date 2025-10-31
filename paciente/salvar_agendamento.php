<?php
require_once '../php/db.php';
session_start();

$idUsuario = $_SESSION['usuario_id'] ?? null;

if (!$idUsuario) {
    echo "Erro: usuário não autenticado.";
    exit;
}

// Buscar ID e nome do paciente
$stmt = $pdo->prepare("SELECT id_paciente, nome FROM paciente WHERE cpf = (SELECT cpf FROM usuario WHERE id = ?)");
$stmt->execute([$idUsuario]);
$paciente = $stmt->fetch();

$idPaciente = $paciente["id_paciente"];
$nomePaciente = $paciente["nome"];

$data = $_POST['data'];
$hora = $_POST['horario'];
$idServico = $_POST['servico'];
$descricao = $_POST['descricao_servico'];

// Buscar nome do serviço
$stmt = $pdo->prepare("SELECT nome_servico FROM servico WHERE id_servico = ?");
$stmt->execute([$idServico]);
$nomeServico = $stmt->fetchColumn();

$stmt = $pdo->prepare("INSERT INTO agenda 
    (nome_paciente, data, hora, descricao_servico, paciente_id_paciente, servico_id_servico)
    VALUES (?, ?, ?, ?, ?, ?)");

$stmt->execute([
    $nomePaciente,
    $data,
    $hora,
    $descricao,
    $idPaciente,
    $idServico
]);

echo "Agendamento realizado com sucesso!";
