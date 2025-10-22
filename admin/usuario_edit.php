<?php
require_once '../php/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: admin.php');
    exit;
}

// Busca completa dos dados do usuário
$stmt = $pdo->prepare('SELECT id, nome, email, tipo_usuario, cpf, telefone, cep, sexo, data_nasc FROM usuario WHERE id=?');
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: admin.php');
    exit;
}

$errors = [];
$nome = $user['nome'];
$email = $user['email'];
$tipo_usuario = $user['tipo_usuario'];
$cpf = $user['cpf'];
$telefone = $user['telefone'];
$cep = $user['cep'];
$sexo = $user['sexo'];
$data_nasc = $user['data_nasc'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome         = trim($_POST['first_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $password     = $_POST['password'] ?? '';
    $tipo_usuario = $_POST['role'] ?? 'paciente';
    $cpf          = trim($_POST['cpf'] ?? '');
    $telefone     = trim($_POST['telefone'] ?? '');
    $cep          = trim($_POST['cep'] ?? '');
    $sexo         = $_POST['sexo'] ?? '';
    $data_nasc    = $_POST['data_nasc'] ?? '';

    // Validação
    if ($nome === '') $errors[] = 'Nome é obrigatório.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
    if (!in_array($tipo_usuario, ['admin','paciente','fisioterapeuta'], true)) $errors[] = 'Perfil inválido.';
    if (!in_array($sexo, ['M', 'F', 'Outro'], true)) $errors[] = 'Sexo inválido.';
    if ($cpf === '') $errors[] = 'CPF é obrigatório.';
    if ($telefone === '') $errors[] = 'Telefone é obrigatório.';
    if ($cep === '') $errors[] = 'CEP é obrigatório.';
    if ($data_nasc === '') $errors[] = 'Data de nascimento é obrigatória.';
    if ($password && strlen($password) < 6) $errors[] = 'Senha deve ter pelo menos 6 caracteres.';

    if (!$errors) {
        try {
            // Verifica duplicidade de e-mail em outro ID
            $chk = $pdo->prepare('SELECT id FROM usuario WHERE email=? AND id<>?');
            $chk->execute([$email, $id]);
            if ($chk->fetch()) {
                $errors[] = 'Já existe um usuário com este e-mail.';
            } else {
                $pdo->beginTransaction();

                // Atualiza dados do usuário
                if ($password) {
                    $stmt = $pdo->prepare('
                        UPDATE usuario
                        SET nome=?, email=?, tipo_usuario=?, senha=?, cpf=?, telefone=?, cep=?, sexo=?, data_nasc=?
                        WHERE id=?
                    ');
                    $stmt->execute([
                        $nome, $email, $tipo_usuario,
                        password_hash($password, PASSWORD_DEFAULT),
                        $cpf, $telefone, $cep, $sexo, $data_nasc, $id
                    ]);
                } else {
                    $stmt = $pdo->prepare('
                        UPDATE usuario
                        SET nome=?, email=?, tipo_usuario=?, cpf=?, telefone=?, cep=?, sexo=?, data_nasc=?
                        WHERE id=?
                    ');
                    $stmt->execute([
                        $nome, $email, $tipo_usuario,
                        $cpf, $telefone, $cep, $sexo, $data_nasc, $id
                    ]);
                }

                // Atualiza (ou cria) paciente, se for paciente
                if ($tipo_usuario === 'paciente') {
                    $stmtCheck = $pdo->prepare('SELECT id_paciente FROM paciente WHERE cpf=?');
                    $stmtCheck->execute([$cpf]);
                    if ($stmtCheck->fetch()) {
                        $stmtPac = $pdo->prepare('
                            UPDATE paciente
                            SET nome=?, telefone=?, cep=?, sexo=?
                            WHERE cpf=?
                        ');
                        $stmtPac->execute([$nome, $telefone, $cep, $sexo, $cpf]);
                    } else {
                        $stmtPac = $pdo->prepare('
                            INSERT INTO paciente (nome, telefone, cep, sexo, cpf)
                            VALUES (?, ?, ?, ?, ?)
                        ');
                        $stmtPac->execute([$nome, $telefone, $cep, $sexo, $cpf]);
                    }
                }

                $pdo->commit();

                header('Location: admin.php');
                exit;
            }
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
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>  
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

    <!-- Máscara CPF -->
    <script>
    $(document).ready(function(){
    $('#cpf').mask('000.000.000-00');
    });
    </script>

    <!-- Máscara para telefone -->

    <script>
    $(document).ready(function(){
    $('#telefone').mask('(00) 00000-0000');
    });
    </script>

    <!-- Máscara para CEP -->
    <script>
    $(document).ready(function(){
    $('#cep').mask('00000-000');
    });
    </script>

</head>
<body>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="h4 mb-0">Editar Usuário #<?php echo (int)$user['id']; ?></h2>
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
      <label class="form-label">Nome</label>
      <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($nome); ?>" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">E-mail</label>
      <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">CPF</label>
      <input type="text" name="cpf" id="cpf" class="form-control" value="<?php echo htmlspecialchars($cpf); ?>" required maxlength="14">
    </div>

    <div class="col-md-3">
      <label class="form-label">Telefone</label>
      <input type="text" name="telefone" id="telefone" class="form-control" value="<?php echo htmlspecialchars($telefone); ?>" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">CEP</label>
      <input type="text" name="cep" id="cep" class="form-control" value="<?php echo htmlspecialchars($cep); ?>" required>
    </div>

    <div class="col-md-3">
      <label class="form-label">Sexo</label>
      <select name="sexo" class="form-select" required>
        <option value="M" <?php echo $sexo === 'M' ? 'selected' : ''; ?>>Masculino</option>
        <option value="F" <?php echo $sexo === 'F' ? 'selected' : ''; ?>>Feminino</option>
        <option value="Outro" <?php echo $sexo === 'Outro' ? 'selected' : ''; ?>>Outros...</option>
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Data de Nascimento</label>
      <input type="date" name="data_nasc" class="form-control" value="<?php echo htmlspecialchars($data_nasc); ?>" required>
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
</body>
</html>
