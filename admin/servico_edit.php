<?php
require_once '../php/db.php';
session_start();
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
    header('Location: servicos.php');
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
            $_SESSION['msg'] = "Serviço editado com sucesso!";
            $_SESSION['msg_tipo'] = "sucesso";
            header('Location: servicos.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}
include __DIR__ . '../partials/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Serviço</title>
<style>
/* Títulos */
h2 {
    color: #003c82;
    font-weight: 600;
}

/* Card do formulário */
.card {
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    padding: 25px;
    background-color: rgba(255, 255, 255, 0.95);
    width: 100%;
    max-width: 100%;
    margin-top: 20px;
}

/* Labels */
label.form-label {
    font-weight: 500;
    color: #004b87;
}

/* Inputs e selects */
.form-control, .form-select {
    border-radius: 12px;
    border: 1px solid #a9d3ff;
    padding: 10px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #6ddccf;
    box-shadow: 0 0 8px rgba(109,220,207,0.5);
}

/* Botões */
.btn-primary {
    border-radius: 12px;
    padding: 8px 25px;
    background: linear-gradient(90deg, #6ddccf 0%, #7cc6fe 100%);
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
    color: #fff;
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

/* Alertas */
.alert-danger {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Campos em linha e responsivos */
.row.g-4 > [class*='col-'] {
    margin-bottom: 15px;
}

/* Botão alinhado à direita */
.text-end {
    text-align: right;
}

@media (max-width: 768px) {
    .card {
      padding: 20px 15px;
    }
}
</style>
</head>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h2 class="h4 mb-0" data-aos="fade-right">Editar Serviço #<?= (int)$servico['id_servico'] ?></h2>
  <a class="btn btn-outline-primary btn-sm" href="servicos.php" data-aos="fade-left">Voltar</a>
</div>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="card mb-5" data-aos="zoom-in">
  <div class="row g-4">
    <div class="col-md-6" data-aos="zoom-in" data-aos-delay="400">
      <label class="form-label">Nome do Serviço</label>
      <input type="text" name="nome_servico" class="form-control" value="<?= htmlspecialchars($nome_servico) ?>" required>
    </div>

    <div class="col-md-6" data-aos="zoom-in" data-aos-delay="500">
      <label class="form-label">Descrição</label>
      <input type="text" name="descricao_servico" class="form-control" value="<?= htmlspecialchars($descricao_servico) ?>" required>
    </div>

    <div class="col-md-3" data-aos="zoom-in" data-aos-delay="600">
      <label class="form-label">Status</label>
      <select name="status" class="form-select" required>
        <option value="Ativo" <?= $status==='Ativo'?'selected':''; ?>>Ativo</option>
        <option value="Inativo" <?= $status==='Inativo'?'selected':''; ?>>Inativo</option>
      </select>
    </div>
  </div>

  <div class="mt-4 text-end">
    <button class="btn btn-primary">Salvar</button>
  </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
