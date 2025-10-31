<?php
session_start();

include 'db.php';

$email = htmlspecialchars(trim($_POST['email']));
$senha = $_POST['senha'];

if (empty($email) || empty($senha)) {
    echo "<script>
            alert('Por favor preencha o campo Email ou senha.');
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

try {
    // Consulta com PDO, agora incluindo a coluna 'foto'
    $stmt = $pdo->prepare("SELECT id, nome, senha, tipo_usuario, foto FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "<script>
                alert('Email ou senha inválido.');
                window.history.back();
              </script>";
        exit;
    }

    // Verifica senha
    if (!password_verify($senha, $usuario['senha'])) {
        echo "<script>
                alert('Email ou senha inválido.');
                window.history.back();
              </script>";
        exit;
    }

    // Login OK - grava dados na sessão
    $_SESSION['usuario_id']      = $usuario['id'];
    $_SESSION['usuario_nome']    = $usuario['nome'];
    $_SESSION['usuario_tipo']    = $usuario['tipo_usuario'];
    $_SESSION['foto_perfil']     = $usuario['foto'] ?? '../img/imagem_perfil.JPEG';

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
