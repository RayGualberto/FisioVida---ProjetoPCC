<?php
include 'db.php';

// Recebe os dados do formulário com filtro para evitar XSS
$nome = htmlspecialchars(trim($_POST['nome']));
$email = htmlspecialchars(trim($_POST['email']));
$senha = $_POST['senha']; // será tratada depois
$telefone = htmlspecialchars(trim($_POST['telefone']));
$cep = htmlspecialchars(trim($_POST['cep']));
$cpf = htmlspecialchars(trim($_POST['cpf']));
$sexo = htmlspecialchars(trim($_POST['sexo']));
$data_nasc = htmlspecialchars(trim($_POST['data_nasc']));

// Validações básicas
if (empty($nome) || empty($email) || empty($senha) || empty($cpf) || empty($sexo) || empty($data_nasc)) {
    echo "<script>
            alert('Por favor, preencha todos os campos obrigatórios.');
            window.history.back();
          </script>";
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
            alert('Email inválido.');
            window.history.back();
          </script>";
    exit;
}
if (strlen($senha) < 6) {
    echo "<script>
            alert('A senha deve ter pelo menos 6 caracteres.');
            window.history.back();
          </script>";
    exit;
}

try {
    // Verificar se o e-mail já está cadastrado
    $stmt_check = $pdo->prepare("SELECT id FROM usuario WHERE email = ? OR cpf = ?");
    $stmt_check->execute([$email, $cpf]);

    if ($stmt_check->rowCount() > 0) {
        echo "<script>
            alert('Este e-mail ou CPF já está cadastrado.');
            window.history.back();
          </script>";
        exit;
        }

    // Criptografar senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo_usuario = 'paciente';

    // Iniciar transação
    $pdo->beginTransaction();

    // Inserir usuário
    $stmt1 = $pdo->prepare("INSERT INTO usuario (nome, email, senha, cpf, data_nasc, telefone, cep, sexo, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt1->execute([$nome, $email, $senhaHash, $cpf, $data_nasc, $telefone, $cep, $sexo, $tipo_usuario]);

    // Pega o ID do usuário recém-criado (caso precise)
    $id = $pdo->lastInsertId();

    // Inserir paciente
    $stmt2 = $pdo->prepare("INSERT INTO paciente (nome, telefone, cep, sexo, cpf) VALUES (?, ?, ?, ?, ?)");
    $stmt2->execute([$nome, $telefone, $cep, $sexo, $cpf]);

    // Commit na transação
    $pdo->commit();

    // Redirecionar para login
    header("Location: ../site/login.php");
    exit();

} catch (PDOException $e) {
    // Rollback se algo falhar
    $pdo->rollBack();
    die("Erro no cadastro: " . $e->getMessage());
}
?>
