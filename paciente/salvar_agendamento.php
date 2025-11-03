<?php
require_once '../php/db.php';
// partials/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../site/login.php');
    exit;
}

header('Content-Type: application/json');

// Usuário logado?
$idUsuario = $_SESSION['usuario_id'] ?? null;
if (!$idUsuario) {
    echo json_encode(['success' => false, 'msg' => 'Usuário não autenticado']);
    exit;
}

// Recebe dados do POST
$data = $_POST['data'] ?? null;
$hora = $_POST['hora'] ?? null;
$idServico = $_POST['servico'] ?? null;

if (!$data || !$hora || !$idServico) {
    echo json_encode(['success' => false, 'msg' => 'Preencha todos os campos']);
    exit;
}

// Buscar paciente
$stmt = $pdo->prepare("
    SELECT p.id_paciente, p.nome 
    FROM paciente p
    JOIN usuario u ON u.cpf = p.cpf
    WHERE u.id = ?
");
$stmt->execute([$idUsuario]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$paciente) {
    echo json_encode(['success' => false, 'msg' => 'Paciente não encontrado']);
    exit;
}

$nomePaciente = $paciente['nome'];
$idPaciente = $paciente['id_paciente'];

// Buscar descrição do serviço
$stmt = $pdo->prepare("SELECT descricao_servico FROM servico WHERE id_servico = ?");
$stmt->execute([$idServico]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);
$descricaoServico = $servico['descricao_servico'] ?? '';

try {
    // Inserir agendamento
    $stmt = $pdo->prepare("INSERT INTO agenda 
        (nome_paciente, data, hora, descricao_servico, paciente_id_paciente, servico_id_servico)
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nomePaciente, $data, $hora, $descricaoServico, $idPaciente, $idServico]);

    $novoId = $pdo->lastInsertId();

    echo json_encode([
        "success" => true,
        "msg" => "Agendamento realizado com sucesso!",
        "id" => $novoId,
        "nome_paciente" => $nomePaciente,
        "data" => $data,
        "hora" => $hora,
        "descricao_servico" => $descricaoServico
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'msg' => 'Erro ao salvar: '.$e->getMessage()]);
}
