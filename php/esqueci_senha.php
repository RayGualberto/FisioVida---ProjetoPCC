<?php
session_start();
require_once '../php/db.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $usuario = buscarUm("SELECT id FROM usuario WHERE email = ?", 
                       [$email]);

    if ($usuario) {
        // Armazena o ID do usuário na sessão para validação na próxima página
        $_SESSION['redefinir_senha_usuario'] = $usuario['id'];
        header("Location: redefinir_senha.php");
        exit();
    } else {
        $erro = "Dados não encontrados. Verifique seu e-mail.";
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
    <link rel="stylesheet" href="../css/esquecisenha.css">
    <style>
       
    </style>
</head>
<body>
    <div class="auth-container" data-aos="fade-up">
        <div class="logo" data-aos="fade-down" data-aos-delay="200">
            <img src="../img/Icone fisiovida.png" 
            alt="logofisiovida" 
            class="mx-auto d-block" 
            width="120px" 
            height="160px">
        <h1>Recuperar Senha</h1>
        </div>
        
        <?php if ($erro): ?>
            <div class="notification error"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group" data-aos="fade-down" data-aos-delay="400">
                <label for="">Digite seu gmail:</label>
                <input type="email" class="form-control" name="email" placeholder="Seu e-mail" required>
            </div>
            <button type="submit" class="btn" data-aos="fade-down" data-aos-delay="600">Validar</button>
        </form>
        
        <div class="auth-links" data-aos="fade-down" data-aos-delay="800">
            <a href="../site/login.php" class="text-dark">Voltar para o login</a>
        </div>
    </div>
    </script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
</script>
</body>
</html>