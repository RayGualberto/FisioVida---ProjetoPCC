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

try {
    // Consulta com PDO
    $stmt = $conn->prepare("SELECT id, nome, senha, tipo_usuario FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Email ou senha inválidos.");
    }

    // Verifica senha
    if (!password_verify($senha, $usuario['senha'])) {
        die("Email ou senha inválidos.");
    }

    // Login OK - grava dados na sessão
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];

    // Redireciona conforme tipo de usuário
    switch ($usuario['tipo_usuario']) {
        case 'paciente':
            header("Location: ../paciente/paciente_dashboard.php");
            break;
        case 'admin':
            header("Location: ../admin/admin.php");
            break;
        case 'fisioterapeuta':
            header("Location: ../fisioterapeuta/fisio_dashboard.php");
            break;
        default:
            die("Tipo de usuário inválido.");
    }

    exit();

} catch (PDOException $e) {
    die("Erro ao acessar o banco de dados: " . $e->getMessage());
}
?>
