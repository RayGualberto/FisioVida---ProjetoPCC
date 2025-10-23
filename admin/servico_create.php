<?php
require_once '../php/db.php'; // conexão $pdo

$errors = [];
$nome_servico = $descricao_servico = '';
$status = 'Ativo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_servico      = trim($_POST['nome_servico'] ?? '');
    $descricao_servico = trim($_POST['descricao_servico'] ?? '');
    $status            = $_POST['status'] ?? 'Ativo';

    // Validações básicas
    if ($nome_servico === '') $errors[] = 'O nome do serviço é obrigatório.';
    if ($descricao_servico === '') $errors[] = 'A descrição do serviço é obrigatória.';
    if (!in_array($status, ['Ativo', 'Inativo'], true)) $errors[] = 'Status inválido.';

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('
                INSERT INTO servico (nome_servico, descricao_servico, status)
                VALUES (?, ?, ?)
            ');
            $stmt->execute([$nome_servico, $descricao_servico, $status]);

            $pdo->commit();

            header('Location: admin.php');
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
    <h2 class="h4 mb-0">Novo Serviço</h2>
    <a class="btn btn-outline-primary btn-sm" href="admin.php">Voltar</a>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>'; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" class="card shadow-sm p-3">
    <div class="row g-3">
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

    <div class="mt-3 text-end">
        <button type="submit" class="btn btn-primary">Adicionar Serviço</button>
    </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
