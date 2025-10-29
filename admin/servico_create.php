<?php
require_once '../php/db.php'; // conexão $pdo

$errors = [];
$nome_servico = $descricao_servico = '';
$status = 'Ativo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_servico      = trim($_POST['nome_servico'] ?? '');
    $descricao_servico = trim($_POST['descricao_servico'] ?? '');
    $status            = $_POST['status'] ?? 'Ativo';

    if ($nome_servico === '') $errors[] = 'O nome do serviço é obrigatório.';
    if ($descricao_servico === '') $errors[] = 'A descrição do serviço é obrigatória.';
    if (!in_array($status, ['Ativo', 'Inativo'], true)) $errors[] = 'Status inválido.';

    if (!$errors) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('INSERT INTO servico (nome_servico, descricao_servico, status) VALUES (?, ?, ?)');
            $stmt->execute([$nome_servico, $descricao_servico, $status]);
            $pdo->commit();
            header('Location: servicos.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() === '23000') {
                $errors[] = 'Já existe um serviço com este nome.';
            } else {
                $errors[] = 'Erro ao salvar: ' . $e->getMessage();
            }
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
<style>
body {
    margin: 0;

    font-family: 'Roboto';
    background: linear-gradient(135deg, #ffffff 0%, #9df7c2 50%, #acb7f7 100%);
    min-height: 100vh;
}

/* Título */
h2 {
    color: #003c82;
    font-weight: 600;
}

/* Card do formulário expandido */
.card {
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    padding: 25px;
    background-color: rgba(255, 255, 255, 0.95);
    width: 100%;        /* ocupa toda a largura disponível */
    max-width: 100%;    /* remove limite anterior */
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
    transition: all 0.3s ease;
    padding: 10px;
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

/* Em telas pequenas, mantém card adaptado */
@media (max-width: 768px) {
    .card {
      padding: 20px 15px;
    }
}
</style>
</head>
<body>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="h4 mb-0">Novo Serviço</h2>
    <a class="btn btn-outline-primary btn-sm" href="servicos.php">Voltar</a>
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
            <label class="form-label">Nome do Serviço</label>
            <input type="text" name="nome_servico" class="form-control" value="<?= htmlspecialchars($nome_servico) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Descrição</label>
            <input type="text" name="descricao_servico" class="form-control" value="<?= htmlspecialchars($descricao_servico) ?>" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Ativo" <?= $status==='Ativo'?'selected':''; ?>>Ativo</option>
                <option value="Inativo" <?= $status==='Inativo'?'selected':''; ?>>Inativo</option>
            </select>
        </div>
    </div>

    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary">Adicionar Serviço</button>
    </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
