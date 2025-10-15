<?php
session_start();
include 'db.php';  // seu arquivo de conexão com o DB

// Recebe os dados do POST com tratamento básico
$nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';
$telefone = htmlspecialchars(trim($_POST['telefone'] ?? ''));
$cep = htmlspecialchars(trim($_POST['cep'] ?? ''));
$cpf = htmlspecialchars(trim($_POST['cpf'] ?? ''));
$sexo = htmlspecialchars(trim($_POST['sexo'] ?? ''));
$data_nasc = htmlspecialchars(trim($_POST['data_nasc'] ?? ''));

// Validação
if (!$nome || !$email || !$senha || !$cpf || !$sexo || !$data_nasc) {
    $_SESSION['error_msg'] = "Por favor, preencha todos os campos obrigatórios.";
    header("Location: ../site/cadastro.php");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_msg'] = "Email inválido.";
    header("Location: ../site/cadastro.php");
    exit();
}

if (strlen($senha) < 6) {
    $_SESSION['error_msg'] = "A senha deve ter no mínimo 6 caracteres.";
    header("Location: ../site/cadastro.php");
    exit();
}

// Verifica se email já existe
$stmt_check = $conn->prepare("SELECT id FROM usuario WHERE email = ?");
if (!$stmt_check) {
    $_SESSION['error_msg'] = "Erro no sistema, tente mais tarde.";
    header("Location: ../site/cadastro.php");
    exit();
}
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $_SESSION['error_msg'] = "Este email já está cadastrado. Faça login.";
    $stmt_check->close();
    header("Location: ../site/cadastro.php");
    exit();
}
$stmt_check->close();

// Criptografa senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$tipo_usuario = 'paciente';

// Insere usuário
$stmt1 = $conn->prepare("INSERT INTO usuario (nome, email, senha, cpf, data_nasc, telefone, cep, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt1) {
    $_SESSION['error_msg'] = "Erro no sistema, tente mais tarde.";
    header("Location: ../site/cadastro.php");
    exit();
}
$stmt1->bind_param("ssssssss", $nome, $email, $senhaHash, $cpf, $data_nasc, $telefone, $cep, $tipo_usuario);

if (!$stmt1->execute()) {
    $_SESSION['error_msg'] = "Erro ao cadastrar usuário: " . $stmt1->error;
    header("Location: ../site/cadastro.php");
    exit();
}

$id_usuario = $stmt1->insert_id;
$stmt1->close();

// Insere dados na tabela paciente
$stmt2 = $conn->prepare("INSERT INTO paciente (nome, telefone, cep, sexo, cpf) VALUES (?, ?, ?, ?, ?)");
if (!$stmt2) {
    $_SESSION['error_msg'] = "Erro no sistema, tente mais tarde.";
    header("Location: ../site/cadastro.php");
    exit();
}
$stmt2->bind_param("sssss", $nome, $telefone, $cep, $sexo, $cpf);

if (!$stmt2->execute()) {
    $_SESSION['error_msg'] = "Erro ao cadastrar paciente: " . $stmt2->error;
    header("Location: ../site/cadastro.php");
    exit();
}

$stmt2->close();
$conn->close();

// Cadastro OK, redireciona para login
header("Location: ../site/login.php");
exit();
