<?php
require_once '../php/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../site/login.php');
    exit;
}

header('Content-Type: application/json');

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
    echo json_encode(['success' => false, 'msg' => 'Paciente não encontrado para o usuário informado']);
    exit;
}

$nomePaciente = $paciente['nome'];
$idPaciente = $paciente['id_paciente'];

// Buscar descrição do serviço
$stmt = $pdo->prepare("SELECT descricao_servico, fisioterapeuta_id FROM servico WHERE id_servico = ?");
$stmt->execute([$idServico]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

$descricaoServico = $servico['descricao_servico'] ?? '';
$idFisio = $servico['fisioterapeuta_id'] ?? null;

try {
    // Inserir agendamento
    $stmt = $pdo->prepare("
        INSERT INTO agenda 
            (nome_paciente, data, hora, descricao_servico, paciente_id_paciente, servico_id_servico, fisioterapeuta_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nomePaciente, $data, $hora, $descricaoServico, $idPaciente, $idServico, $idFisio]);
    $novoId = $pdo->lastInsertId();

    // Criar mensagem de notificação
    $mensagem = "📅 Novo agendamento: $nomePaciente marcou $descricaoServico em $data às $hora";

    // Verifica se há fisioterapeuta vinculado
    if ($idFisio) {
        // Se houver fisioterapeuta específico, envia para ele
        $stmtNotif = $pdo->prepare("
            INSERT INTO notificacoes (remetente_id, destinatario_id, mensagem, tipo)
            VALUES (?, ?, ?, 'agendamento')
        ");
        $stmtNotif->execute([$idPaciente, $idFisio, $mensagem]);
    } else {
        // Se não houver fisioterapeuta vinculado, envia para todos os fisioterapeutas cadastrados
        $stmtFisio = $pdo->query("SELECT id_Fisioterapeuta FROM fisioterapeuta");
        $todosFisio = $stmtFisio->fetchAll(PDO::FETCH_ASSOC);

        $stmtNotif = $pdo->prepare("
            INSERT INTO notificacoes (remetente_id, destinatario_id, mensagem, tipo)
            VALUES (?, ?, ?, 'agendamento')
        ");
        foreach ($todosFisio as $f) {
            $stmtNotif->execute([$idPaciente, $f['id_Fisioterapeuta'], $mensagem]);
        }
    }

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
    echo json_encode(['success' => false, 'msg' => 'Erro ao salvar: ' . $e->getMessage()]);
}
