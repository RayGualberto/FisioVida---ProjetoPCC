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
    die("Por favor, preencha todos os campos obrigatórios.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido.");
}

if (strlen($senha) < 6) {
    die("A senha deve ter no mínimo 6 caracteres.");
}

try {
    // Verificar se o e-mail já está cadastrado
    $stmt_check = $conn->prepare("SELECT id FROM usuario WHERE email = ?");
    $stmt_check->execute([$email]);

    if ($stmt_check->rowCount() > 0) {
        die("Este e-mail já está cadastrado. Tente fazer login.");
    }

    // Criptografar senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo_usuario = 'paciente';

    // Iniciar transação
    $conn->beginTransaction();

    // Inserir usuário
    $stmt1 = $conn->prepare("INSERT INTO usuario (nome, email, senha, cpf, data_nasc, telefone, cep, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt1->execute([$nome, $email, $senhaHash, $cpf, $data_nasc, $telefone, $cep, $tipo_usuario]);

    // Pega o ID do usuário recém-criado (caso precise)
    $id = $conn->lastInsertId();

    // Inserir paciente
    $stmt2 = $conn->prepare("INSERT INTO paciente (nome, telefone, cep, sexo, cpf) VALUES (?, ?, ?, ?, ?)");
    $stmt2->execute([$nome, $telefone, $cep, $sexo, $cpf]);

    // Commit na transação
    $conn->commit();

    // Redirecionar para login
    header("Location: ../site/login.php");
    exit();

} catch (PDOException $e) {
    // Rollback se algo falhar
    $conn->rollBack();
    die("Erro no cadastro: " . $e->getMessage());
}
?>
