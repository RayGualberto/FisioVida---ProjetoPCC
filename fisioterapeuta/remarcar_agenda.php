<?php
require_once '../php/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: fisio_dashboard.php');
    exit;
}

// Buscar dados do agendamento
$stmt = $pdo->prepare("SELECT nome_paciente, descricao_servico, data, hora, paciente_id_paciente FROM agenda WHERE id_Agenda = ?");
$stmt->execute([$id]);
$agendamento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agendamento) {
    header('Location: fisio_dashboard.php');
    exit;
}

// Atualização após envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_data'], $_POST['nova_hora'])) {
    $novaData = $_POST['nova_data'];
    $novaHora = $_POST['nova_hora'];

    // Atualiza agendamento
    $stmtUpdate = $pdo->prepare("UPDATE agenda SET data = ?, hora = ?, status = 'remarcado' WHERE id_Agenda = ?");
    $stmtUpdate->execute([$novaData, $novaHora, $id]);

    $pacienteId = $agendamento['paciente_id_paciente'];
    $msg = "📅 Sua sessão foi remarcado para " . date('d/m/Y', strtotime($novaData)) . " às " . $novaHora . ".";
    
    // Envia notificação
    $stmtNotif = $pdo->prepare("
        INSERT INTO notificacoes (remetente_id, destinatario_id, mensagem, tipo, lida)
        VALUES (?, ?, ?, ?, 0)
    ");
    $stmtNotif->execute([
        $_SESSION['usuario_id'], // remetente
        $pacienteId,          // destinatário
        $msg,
        'remarcado'
    ]);

    $_SESSION['msg'] = "📅 Agendamento remarcado e notificação enviada!";
    header('Location: fisio_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Remarcar Agendamento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: whitesmoke;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .card {
      border-radius: 12px;
      background-color: rgba(255, 255, 255, 0.9);
      border: none;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 500px;
      padding: 2rem;
    }
  </style>
</head>
<body>
  <div class="card">
    <h3 class="text-center mb-4">Remarcar Agendamento</h3>
    <form method="post">
      <input type="hidden" name="id" value="<?= (int)$id ?>">

      <div class="mb-3">
        <label class="form-label">Nome do Paciente</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($agendamento['nome_paciente']) ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Serviço</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($agendamento['descricao_servico']) ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Data Atual</label>
        <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($agendamento['data'])) ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Hora Atual</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($agendamento['hora']) ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Nova Data</label>
        <input type="date" name="nova_data" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Novo Horário</label>
        <input type="time" name="nova_hora" class="form-control" required min="08:00" max="18:00" step="900">
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-warning px-4">Confirmar</button>
        <a href="fisio_dashboard.php" class="btn btn-secondary px-4">Cancelar</a>
      </div>
    </form>
  </div>
</body>
</html>
