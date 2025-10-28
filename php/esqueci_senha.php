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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:rgb(204, 255, 0);
            --dark: #121212;
            --light-dark: #181818;
            --lighter-dark: #282828;
            --light-text: #b3b3b3;
            --white: #ffffff;
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: rgb(255, 255, 255);
            height: 100vh;
            font-family: roboto;

            background: linear-gradient(135deg, 
            #ffffff 0%,    
            #9df7c2 50%,    
            #acb7f7 100%   
            );
            min-height: 100vh;
            margin: 0;

            background-attachment: fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
}
        
        .auth-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo i {
            font-size: 50px;
            color: var(--primary);
        }
        
        .logo h1 {
            margin-top: 10px;
            font-size: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--light-text);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            color: var(--white);
            font-size: 14px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: black;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .btn:hover {
            background: #1ed760;
            transform: translateY(-2px);
        }
        
        .auth-links {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }
        
        .auth-links a {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .auth-links a:hover {
            text-decoration: underline;
        }
        
        .notification {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .error {
            background: rgba(255, 51, 51, 0.1);
            border: 1px solid #ff3333;
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo">
            <i class="fas fa-compact-disc"></i>
            <h1>Recuperar Senha</h1>
        </div>
        
        <?php if ($erro): ?>
            <div class="notification error"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="">Digite seu gmail:</label>
                <input type="email" class="form-control" name="email" placeholder="Seu e-mail" required>
            </div>
            <button type="submit" class="btn">Validar</button>
        </form>
        
        <div class="auth-links">
            <a href="../site/login.php" class="text-dark">Voltar para o login</a>
        </div>
    </div>
</body>
</html>