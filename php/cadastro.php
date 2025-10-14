<?php

include 'db.php';

// Recebe os dados do formulário, com filtro para evitar XSS
$nome = htmlspecialchars(trim($_POST['nome']));
$email = htmlspecialchars(trim($_POST['email']));
$senha = $_POST['senha']; // senha será tratada depois
$telefone = htmlspecialchars(trim($_POST['telefone']));
$cep = htmlspecialchars(trim($_POST['cep']));
$cpf = htmlspecialchars(trim($_POST['cpf']));
$sexo = htmlspecialchars(trim($_POST['sexo']));
$data_nasc = htmlspecialchars(trim($_POST['data_nasc']));

// Validações básicas
if (empty($nome) || empty($email) || empty($senha) || empty($cpf) || empty($sexo) || empty($data_nasc)){
    die("Por favor, preencha todos os campos obrigatórios.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido.");
}

if (strlen($senha) < 6) {
    die("A senha deve ter no mínimo 6 caracteres.");
}

// Verificar se o email já está cadastrado
$stmt_check = $conn->prepare("SELECT id FROM usuario WHERE email = ?");
if (!$stmt_check) {
    die("Falha no prepare: (" . $conn->errno . ") " . $conn->error);
}
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    die("Este email já está cadastrado. Tente fazer login.");
}

$stmt_check->close();

// Criptografa a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Tipo de usuário fixo para paciente
$tipo_usuario = 'paciente';

// Preparar e executar inserção
$stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha, cpf, telefone, cep, tipo_usuario, data_nasc) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Falha no prepare: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("ssssssss", $nome, $email, $senhaHash, $cpf, $telefone, $cep, $tipo_usuario, $data_nasc);

if ($stmt->execute()) {
    // Cadastro realizado com sucesso, redirecionar para login
    header("Location: ../site/login.html");
    exit();
} else {
    echo "Erro no cadastro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
