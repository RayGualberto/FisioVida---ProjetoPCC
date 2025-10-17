<?php
session_start();
require_once '../php/db.php'; // Conexão PDO

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'paciente') {
    die("Acesso negado.");
}

$usuarioId = $_SESSION['usuario_id'];
$nome_paciente = $_POST['nome_paciente'];
$data = $_POST['data'];
$hora = $_POST['hora'];
$servico_id = $_POST['servico_id'];
$data_agendamento = date('Y-m-d');

try {
    // Buscar id_paciente correspondente ao usuário
    $stmt = $conn->prepare("
        SELECT p.id_paciente
        FROM paciente p
        INNER JOIN usuario u ON p.cpf = u.cpf
        WHERE u.id = ?
        LIMIT 1
    ");
    $stmt->execute([$usuarioId]);
    $id_paciente = $stmt->fetchColumn();

    if (!$id_paciente) {
        throw new Exception("Paciente não encontrado.");
    }

    // Buscar descrição do serviço
    $stmt = $conn->prepare("SELECT descricao_servico FROM servico WHERE id_servico = ?");
    $stmt->execute([$servico_id]);
    $descricao_servico = $stmt->fetchColumn();

    if (!$descricao_servico) {
        throw new Exception("Serviço não encontrado.");
    }

    // Inserir agendamento
    $stmt = $conn->prepare("
        INSERT INTO agenda (nome_paciente, data, data_agendamento, hora, descricao_servico, paciente_id_paciente, servico_id_servico)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nome_paciente, $data, $data_agendamento, $hora, $descricao_servico, $id_paciente, $servico_id]);

    header("Location: paciente_dashboard.php?agendado=sucesso");
    exit();

} catch (PDOException $e) {
    die("Erro ao processar o agendamento: " . $e->getMessage());
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>
