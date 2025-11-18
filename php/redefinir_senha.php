<?php
session_start();
require_once '../php/db.php';

$erro = '';

// Verifica se o usuário está autorizado (veio da página de recuperação)
if (!isset($_SESSION['redefinir_senha_usuario'])) {
    $_SESSION['mensagem'] = "Acesso não autorizado.";
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['redefinir_senha_usuario'];

// Busca email do usuário para mostrar na tela
$usuario = buscarUm("SELECT email FROM usuario WHERE id = ?", [$id_usuario]);

if (!$usuario) {
    $_SESSION['mensagem'] = "Usuário não encontrado.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if (strlen($nova_senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
    } else {
        // Atualizar senha
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("UPDATE usuario SET senha = ? WHERE id = ?");
        if ($stmt->execute([$senha_hash, $id_usuario])) {
            // Limpa a sessão e redireciona
            unset($_SESSION['redefinir_senha_usuario']);
            $_SESSION['mensagem'] = "Senha redefinida com sucesso!";
            header("Location: ../site/login.php");   
            exit();
        } else {
            $erro = "Erro ao redefinir senha. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Fisiovida</title>
      <link rel="icon" href="../img/Icone fisiovida.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="../css/redefinir.css">
</head>
<body>
    <div class="auth-container" data-aos="fade-up">
        <div class="logo" data-aos="fade-down" data-aos-delay="200">
            <img src="../img/Icone fisiovida.png" 
            alt="logofisiovida" 
            class="mx-auto d-block" 
            width="120px" 
            height="160px">
            <h1>Redefinir Senha</h1>
        </div>
        
        <div class="info-box" data-aos="fade-down" data-aos-delay="200">
            Redefinindo senha para: <strong><?= htmlspecialchars($usuario['email']) ?></strong>
        </div>
        
        <?php if ($erro): ?>
            <div class="notification error"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST" data-aos="fade-down" data-aos-delay="400">
            <div class="form-label">
                <input type="password" class="form-label opacity-75 col-12 rounded" style="height: 40px;" name="nova_senha" placeholder="Nova senha" required>
            </div>
            <div class="form-label">
                <input type="password" class="form-label col-12 opacity-75 rounded" style="height: 40px;" name="confirmar_senha" placeholder="Confirme a nova senha" required>
            </div>
            <button type="submit" class="btn">Redefinir Senha</button>
        </form>
        
        <div class="auth-links">
            <a href="../site/login.php" class="text-dark">Voltar para o login</a>
        </div>
    </div>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
</script>
</body>
</html>