<?php
require_once '../php/db.php'; // conexão $conn

// Variável de array vazio para receber erros
$errors = [];
$nome = $email = $tipo_usuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome         = trim($_POST['first_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $password     = $_POST['password'] ?? '';
    $tipo_usuario = $_POST['role'] ?? 'paciente';

    if ($nome === '') $errors[] = 'Nome é obrigatório.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
    if (strlen($password) < 6) $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
    if (!in_array($tipo_usuario, ['admin','paciente','fisioterapeuta'], true)) $errors[] = 'Perfil inválido.';

    if (!$errors) {
        try {
            $stmt = $conn->prepare('INSERT INTO usuario (nome, email, senha, tipo_usuario) VALUES (?,?,?,?)');
            $stmt->execute([$nome, $email, password_hash($password, PASSWORD_DEFAULT), $tipo_usuario]);
            header('Location: admin.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // Se já existe um e-mail cadastrado
                $errors[] = 'Já existe um usuário com este e-mail.';
            } else {
                $errors[] = 'Erro ao salvar: ' . $e->getMessage();
            }
        }
    }
}

include __DIR__ . '/partials/header.php';
?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0">Novo Usuário</h2>
    <a class="btn btn-outline-secondary btn-sm" href="admin.php">Voltar</a>
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
            <label class="form-label">Nome</label>
            <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($nome); ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Perfil</label>
            <select name="role" class="form-select">
                <option value="paciente" <?php echo $tipo_usuario==='paciente'?'selected':''; ?>>Paciente</option>
                <option value="fisioterapeuta" <?php echo $tipo_usuario==='fisioterapeuta'?'selected':''; ?>>Fisioterapeuta</option>
                <option value="admin" <?php echo $tipo_usuario==='admin'?'selected':''; ?>>Admin</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Senha</label>
            <input type="password" name="password" class="form-control" required>
        </div>
    </div>
    <div class="mt-3 text-end">
        <button type="submit" class="btn btn-primary">Adicionar Usuário</button>
    </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
