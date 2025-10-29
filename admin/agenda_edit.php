<?php
require_once '../php/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: agendamentos.php');
    exit;
}

// Busca completa do agendamento
$stmt = $pdo->prepare('SELECT a.id_Agenda, a.nome_paciente, a.data, a.hora, s.nome_servico 
                       FROM agenda a
                       JOIN servico s ON a.servico_id_servico = s.id_servico
                       WHERE id_Agenda=?');
$stmt->execute([$id]);
$agenda = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agenda) {
    header('Location: agendamentos.php');
    exit;
}

$errors = [];
$data = $agenda['data'];
$hora = $agenda['hora'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = trim($_POST['data'] ?? '');
    $hora             = trim($_POST['hora'] ?? '');

    // Validação
    if ($data === '') $errors[] = 'A data do agendamento é obrigatória.';
    if ($hora === '') $errors[] = 'A hora do agendamento é obrigatória.';

      if (!$errors) {
          try {
              $pdo->beginTransaction();

              $stmt = $pdo->prepare('
                  UPDATE agenda
                  SET data = ?, 
                      hora = ?, 
                      status = "remarcado"
                  WHERE id_Agenda = ?
              ');
              $stmt->execute([$data, $hora, $id]);

              $pdo->commit();

              header('Location: agendamentos.php');
              exit;
          } catch (PDOException $e) {
              $pdo->rollBack();
              $errors[] = 'Erro ao salvar: ' . $e->getMessage();
          }
      }
}

include __DIR__ . '/partials/header.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Agendamento</title>
  <style>
  body {
    margin: 0;
    padding: 0;
    font-family: 'Roboto';
    background: linear-gradient(135deg, #ffffff 0%, #9df7c2 50%, #acb7f7 100%);
    min-height: 100vh;
  }

  h2 {
    color: #003c82;
    font-weight: 600;
  }

  .card {
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    padding: 25px;
    background-color: rgba(255, 255, 255, 0.95);
  }

  label.form-label {
    font-weight: 500;
    color: #004b87;
  }

  .form-control {
    border-radius: 12px;
    border: 1px solid #a9d3ff;
    transition: all 0.3s ease;
    padding: 10px;
  }

  .form-control:focus {
    border-color: #6ddccf;
    box-shadow: 0 0 8px rgba(109,220,207,0.5);
  }

  .btn-primary {
    border-radius: 12px;
    padding: 8px 25px;
    background: linear-gradient(90deg, #6ddccf 0%, #7cc6fe 100%);
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-2px);
  }

  .btn-outline-primary {
    border-radius: 12px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-outline-primary:hover {
    background: #6ddccf;
    border-color: #6ddccf;
    color: #fff;
    transform: translateY(-2px);
  }

  .alert-danger {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }

  @media (max-width: 768px) {
    .card {
      padding: 20px 15px;
    }
  }
</style>
</head>
<body>

<div class="d-flex align-items-center justify-content-between mb-4">
  <h2 class="h4 mb-0">Editar Agendamento #<?= (int)$agenda['id_Agenda'] ?></h2>
  <a class="btn btn-outline-primary btn-sm" href="agendamentos.php">Voltar</a>
</div>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="card mb-5">
  <div class="row g-4">
    <div class="col-md-6">
      <label class="form-label">Paciente</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($agenda['nome_paciente']) ?>" disabled>
    </div>

    <div class="col-md-6">
      <label class="form-label">Serviço</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($agenda['nome_servico']) ?>" disabled>
    </div>

    <div class="col-md-6">
      <label class="form-label">Data da Consulta</label>
      <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($data) ?>" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Hora do Agendamento</label>
      <input type="time" name="hora" class="form-control" 
            value="<?= htmlspecialchars($hora) ?>" 
            required min="08:00" max="18:00" step="900"> <!-- step="900" = 15 minutos -->
    </div>


  <div class="mt-4 text-end">
    <button class="btn btn-primary">Salvar</button>
  </div>
</form>


<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
