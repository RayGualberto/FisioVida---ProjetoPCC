<?php
require_once '../php/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: servicos.php');
    exit;
}

// Busca completa dos dados do serviço
$stmt = $pdo->prepare('SELECT id_servico, nome_servico, descricao_servico, status FROM servico WHERE id_servico=?');
$stmt->execute([$id]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servico) {
    header('Location: admin.php');
    exit;
}

$errors = [];
$nome_servico = $servico['nome_servico'];
$descricao_servico = $servico['descricao_servico'];
$status = $servico['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_servico      = trim($_POST['nome_servico'] ?? '');
    $descricao_servico = trim($_POST['descricao_servico'] ?? '');
    $status            = $_POST['status'] ?? 'Ativo';

    // Validação
    if ($nome_servico === '') $errors[] = 'O nome do serviço é obrigatório.';
    if ($descricao_servico === '') $errors[] = 'A descrição do serviço é obrigatória.';
    if (!in_array($status, ['Ativo', 'Inativo'], true)) $errors[] = 'Status inválido.';

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('
                UPDATE servico
                SET nome_servico=?, descricao_servico=?, status=?
                WHERE id_servico=?
            ');
            $stmt->execute([$nome_servico, $descricao_servico, $status, $id]);

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
  <title>Editar Serviço</title>
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
  <h2 class="h4 mb-0">Editar Serviço #<?php echo (int)$servico['id_servico']; ?></h2>
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
      <label class="form-label">Nome do Serviço</label>
      <input type="text" name="nome_servico" class="form-control" value="<?php echo htmlspecialchars($nome_servico); ?>" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Descrição</label>
      <input type="text" name="descricao_servico" class="form-control" value="<?php echo htmlspecialchars($descricao_servico); ?>" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Status</label>
      <select name="status" class="form-select" required>
        <option value="Ativo" <?php echo $status==='Ativo'?'selected':''; ?>>Ativo</option>
        <option value="Inativo" <?php echo $status==='Inativo'?'selected':''; ?>>Inativo</option>
      </select>
    </div>
  </div>

  <div class="mt-3 text-end">
    <button class="btn btn-primary">Salvar</button>
  </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
