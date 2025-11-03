<?php
require_once '../php/db.php';

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: fisio_dashboard.php');
    exit;
}

$stmt = $pdo->prepare("SELECT nome_paciente, descricao_servico, data, hora FROM agenda WHERE id_Agenda = ?");
$stmt->execute([$id]);
$agendamento = $stmt->fetch();

if (!$agendamento) {
    header('Location: fisio_dashboard.php');
    exit;
}

// Atualização após envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_data'], $_POST['nova_hora'])) {
    $novaData = $_POST['nova_data'];
    $novaHora = $_POST['nova_hora'];

    $stmtUpdate = $pdo->prepare("UPDATE agenda SET data = ?, hora = ?, status = 'remarcado' WHERE id_Agenda = ?");
    $stmtUpdate->execute([$novaData, $novaHora, $id]);

    header('Location: agendamentos.php');
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
      background-color: rgba(255, 255, 255, 0.85);
      border: none;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      color: #000;
      width: 100%;
      max-width: 500px;
      padding: 2rem;
    }

    h3 {
      text-align: center;
      margin-bottom: 1.5rem;
      font-weight: 500;
    }

    .form-control {
      border-radius: 8px;
      border: 1px solid rgba(0,0,0,0.2);
      background-color: #fff;
      color: #000;
    }

    .form-control:focus {
      border-color: #6b4ffc;
      box-shadow: 0 0 5px rgba(107, 79, 252, 0.5);
    }

    .btn-warning {
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-warning:hover {
      background-color: #ffae00ff;
      border-color: #ffae00ff;
    }

    .btn-secondary {
      background-color: #b0b0c0;
      border-color: #b0b0c0;
      color: #000;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-secondary:hover {
      background-color: #9898a8;
      border-color: #9898a8;
      color: #000;
    }

    .text-center > .btn {
      margin: 0 0.5rem;
    }
  </style>
</head>
<body>
  <div class="card">
    <h3>Remarcar Agendamento</h3>
    <form method="post">
      <input type="hidden" name="id" value="<?= (int)$id ?>">

      <div class="mb-3">
        <label class="form-label">Nome do Paciente</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($agendamento['nome_paciente']) ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Tipo de Serviço</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($agendamento['descricao_servico']) ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Nova Data</label>
        <input type="date" name="nova_data" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Novo Horário</label>
      <input type="time" name="nova_hora" class="form-control" required min="08:00" max="18:00" step="60"> <!-- step="900" = 15 minutos -->
    </div>
      <div class="text-center">
        <button type="submit" class="btn btn-warning px-4">Confirmar Remarcação</button>
        <a href="agendamentos.php" class="btn btn-secondary px-4">Cancelar</a>
      </div>
    </form>
  </div>
</body>
</html>

