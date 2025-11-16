<?php
session_start();
include 'db.php';

// Recebe os dados do formulário com filtro para evitar XSS
$nome = htmlspecialchars(trim($_POST['nome']));
$email = htmlspecialchars(trim($_POST['email']));
$senha = $_POST['senha'];
$telefone = htmlspecialchars(trim($_POST['telefone']));
$cep = htmlspecialchars(trim($_POST['cep']));
$cpf = htmlspecialchars(trim($_POST['cpf']));
$sexo = htmlspecialchars(trim($_POST['sexo']));
$data_nasc = htmlspecialchars(trim($_POST['data_nasc']));

// Validações básicas
if (empty($nome) || empty($email) || empty($senha) || empty($cpf) || empty($sexo) || empty($data_nasc)) {
    $_SESSION['msg'] = "Por favor, preencha todos os campos obrigatórios.";
    $_SESSION['msg_tipo'] = "erro";
    header("Location: ../site/cadastro.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['msg'] = "Email inválido.";
    $_SESSION['msg_tipo'] = "erro";
    header("Location: ../site/cadastro.php");
    exit;
}

if (strlen($senha) < 6) {
    $_SESSION['msg'] = "A senha deve ter pelo menos 6 caracteres.";
    $_SESSION['msg_tipo'] = "erro";
    header("Location: ../site/cadastro.php");
    exit;
}

try {
    // Verificar se o e-mail ou CPF já está cadastrado
    $stmt_check = $pdo->prepare("SELECT id FROM usuario WHERE email = ? OR cpf = ?");
    $stmt_check->execute([$email, $cpf]);

    if ($stmt_check->rowCount() > 0) {
        $_SESSION['msg'] = "Este e-mail ou CPF já está cadastrado.";
        $_SESSION['msg_tipo'] = "erro";
        header("Location: ../site/cadastro.php");
        exit;
    }

    // Criptografar senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo_usuario = 'paciente';
    $fotoPadrao = "../img/imagem_perfil.JPEG";

    $pdo->beginTransaction();

    $stmt1 = $pdo->prepare("INSERT INTO usuario (nome, email, senha, cpf, data_nasc, telefone, cep, sexo, foto, tipo_usuario)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt1->execute([$nome, $email, $senhaHash, $cpf, $data_nasc, $telefone, $cep, $sexo, $fotoPadrao, $tipo_usuario]);

    $stmt2 = $pdo->prepare("INSERT INTO paciente (nome, telefone, cep, sexo, cpf) VALUES (?, ?, ?, ?, ?)");
    $stmt2->execute([$nome, $telefone, $cep, $sexo, $cpf]);

    $pdo->commit();

    // 🔥 AQUI: mensagem para aparecer na página de login
    $_SESSION['msg'] = "Cadastro realizado com sucesso!";
    $_SESSION['msg_tipo'] = "sucesso";

    header("Location: ../site/login.php");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['msg'] = "Erro no cadastro: " . $e->getMessage();
    $_SESSION['msg_tipo'] = "erro";
    header("Location: ../site/cadastro.php");
    exit;
}
?>
