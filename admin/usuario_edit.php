<?php

require_once '../php/db.php';

$id = (int)($_GET['id'] ?? 0);

// Ajuste da tabela e campos
$stmt = $pdo->prepare('SELECT id, nome, email, tipo_usuario FROM usuario WHERE id=?');
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) {
    header('Location: admin.php');
    exit;
}

$errors = [];
$nome = $user['nome'];
$email = $user['email'];
$tipo_usuario = $user['tipo_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome         = trim($_POST['first_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $password     = $_POST['password'] ?? '';
    $tipo_usuario = $_POST['role'] ?? 'paciente';

    if ($nome === '') $errors[] = 'Nome é obrigatório.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
    if (!in_array($tipo_usuario, ['admin','paciente','fisioterapeuta'], true)) $errors[] = 'Perfil inválido.';

    if (!$errors) {
        try {
            if ($password && strlen($password) < 6) $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
            if (!$errors) {
                // Verificar duplicidade de e-mail em outro ID
                $chk = $pdo->prepare('SELECT id FROM usuario WHERE email=? AND id<>?');
                $chk->execute([$email, $id]);
                if ($chk->fetch()) {
                    $errors[] = 'Já existe um usuário com este e-mail.';
                } else {
                    if ($password) {
                        $stmt = $pdo->prepare('UPDATE usuario SET nome=?, email=?, tipo_usuario=?, senha=? WHERE id=?');
                        $stmt->execute([$nome, $email, $tipo_usuario, password_hash($password, PASSWORD_DEFAULT), $id]);
                    } else {
                        $stmt = $pdo->prepare('UPDATE usuario SET nome=?, email=?, tipo_usuario=? WHERE id=?');
                        $stmt->execute([$nome, $email, $tipo_usuario, $id]);
                    }
                    header('Location: admin.php');
                    exit;
                }
            }
        } catch (PDOException $e) {
            $errors[] = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/partials/header.php';
?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="h4 mb-0">Editar Usuário #<?php echo (int)$user['id']; ?></h2>
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
      <label class="form-label">Nova Senha (opcional)</label>
      <input type="password" name="password" class="form-control" placeholder="Deixe em branco para manter">
    </div>
  </div>
  <div class="mt-3 text-end">
    <button class="btn btn-primary">Salvar</button>
  </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
