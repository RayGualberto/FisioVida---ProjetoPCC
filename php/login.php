<?php
session_start();

include 'db.php';

$email = htmlspecialchars(trim($_POST['email']));
$senha = $_POST['senha'];

if (empty($email) || empty($senha)) {
    die("Por favor, preencha email e senha.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido.");
}

$stmt = $conn->prepare("SELECT id, nome, senha, tipo_usuario FROM usuario WHERE email = ?");
if (!$stmt) {
    die("Erro na consulta: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    die("Email ou senha inválidos.");
}

$stmt->bind_result($id, $nome, $senha_hash, $tipo_usuario);
$stmt->fetch();

if (!password_verify($senha, $senha_hash)) {
    die("Email ou senha inválidos.");
}

// Login OK - grava dados na sessão
$_SESSION['usuario_id'] = $id;
$_SESSION['usuario_nome'] = $nome;
$_SESSION['usuario_tipo'] = $tipo_usuario;

// Redireciona conforme tipo
switch ($tipo_usuario) {
    case 'paciente':
        header("Location: ../pa_paciente/paciente_dashboard.php");
        break;
    case 'admin':
        header("Location: ../admin/admin_dashboard.php");
        break;
    case 'fisioterapeuta':
        header("Location: ../fisioterapeuta/fisio_dashboard.php");
        break;
    default:
        die("Tipo de usuário inválido.");
}

$stmt->close();
$conn->close();
exit();
?>
