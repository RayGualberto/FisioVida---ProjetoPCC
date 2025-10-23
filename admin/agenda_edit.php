<?php
require_once '../php/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: admin.php');
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
    header('Location: admin.php');
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
                SET data=?, hora=?
                WHERE id_Agenda=?
            ');
            $stmt->execute([$data, $hora, $id]);

            $pdo->commit();

            header('Location: admin.php');
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
      width: 100%;
      height: 100vh;
      font-family: roboto;
      background: linear-gradient(135deg, #ffffff 0%, #9df7c2 50%, #acb7f7 100%);
      background-attachment: fixed;
      background-size: cover;
    }
  </style>
</head>
<body>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="h4 mb-0">Editar Agendamento #<?= (int)$agenda['id_Agenda'] ?></h2>
  <a class="btn btn-outline-primary btn-sm fs-6" href="admin.php">Voltar</a>
</div>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="card shadow-sm p-3">
  <div class="row g-3">
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
      <input type="time" name="hora" class="form-control" value="<?= htmlspecialchars($hora) ?>" required>
    </div>
  </div>

  <div class="mt-3 text-end">
    <button class="btn btn-primary">Salvar</button>
  </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
