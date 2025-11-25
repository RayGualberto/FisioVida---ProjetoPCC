<?php
session_start();

require_once __DIR__ . '/../php/db.php';

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

    // Busca usuário
    $stmt = $pdo->prepare("
        SELECT id, nome, senha, cpf, tipo_usuario, foto 
        FROM usuario 
        WHERE email = ?
    ");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        echo "<script>
                alert('Email ou senha inválido.');
                window.history.back();
              </script>";
        exit;
    }

    // --- LOGIN OK - SALVA DADOS GERAIS ---
    $_SESSION['usuario_id']      = $usuario['id'];
    $_SESSION['usuario_nome']    = $usuario['nome'];
    $_SESSION['usuario_tipo']    = $usuario['tipo_usuario'];
    $_SESSION['foto_perfil']     = $usuario['foto'] ?? '../img/imagem_perfil.JPEG';
    $_SESSION['cpf']             = $usuario['cpf'];

    // --- SE FOR FISIOTERAPEUTA ---
    if ($usuario['tipo_usuario'] === 'fisioterapeuta') {

        // Buscar ID do fisioterapeuta na tabela apropriada
        $stmtFisio = $pdo->prepare("
            SELECT id_fisioterapeuta 
            FROM fisioterapeuta 
            WHERE cpf = ?
        ");
        $stmtFisio->execute([$usuario['cpf']]);
        $fisio = $stmtFisio->fetch(PDO::FETCH_ASSOC);

        if ($fisio) {
            $_SESSION['fisioterapeuta_id'] = $fisio['id_fisioterapeuta'];
            $_SESSION['cpf_fisioterapeuta'] = $usuario['cpf'];
        } else {
            echo "<script>
                    alert('Erro: CPF não encontrado na tabela de fisioterapeuta.');
                    window.history.back();
                </script>";
            exit;
        }
    }

    // Redireciona
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
