<?php
require_once '../php/db.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../site/login.php');
    exit;
}

$idUsuario = $_SESSION['usuario_id'];

// Verifica se um arquivo foi enviado corretamente
if (isset($_FILES['novaFoto']) && $_FILES['novaFoto']['error'] === UPLOAD_ERR_OK) {
    $fotoTmp = $_FILES['novaFoto']['tmp_name'];
    $fotoNome = basename($_FILES['novaFoto']['name']);
    $extensao = strtolower(pathinfo($fotoNome, PATHINFO_EXTENSION));

    // Extensões permitidas
    $extPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extensao, $extPermitidas)) {
        echo "<script>alert('Formato de imagem inválido!'); window.history.back();</script>";
        exit;
    }

    // Gera novo nome único
    $novoNome = 'foto_' . $idUsuario . '_' . time() . '.' . $extensao;

    // Caminho destino
    $pastaDestino = '../uploads/';
    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    $caminhoFinal = $pastaDestino . $novoNome;

    // Move a imagem para a pasta uploads
    if (move_uploaded_file($fotoTmp, $caminhoFinal)) {
        // Atualiza no banco
        $stmt = $pdo->prepare("UPDATE usuario SET foto = ? WHERE id = ?");
        $stmt->execute([$caminhoFinal, $idUsuario]);

        // Atualiza a sessão com a nova foto
        $_SESSION['foto_perfil'] = $caminhoFinal;
    } else {
        echo "<script>alert('Erro ao enviar a foto.'); window.history.back();</script>";
        exit;
    }
}

// Redireciona para a página anterior
// NÃO use header("Location: ...") mais, só retorna OK
echo "Foto enviada com sucesso";
exit;

?>
