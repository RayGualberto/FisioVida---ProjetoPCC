<?php
require_once '../php/db.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../site/login.php');
    exit;
}

$idUsuario = $_SESSION['usuario_id'];

if (isset($_FILES['novaFoto']) && $_FILES['novaFoto']['error'] === UPLOAD_ERR_OK) {
    $fotoTmp = $_FILES['novaFoto']['tmp_name'];
    $fotoConteudo = file_get_contents($fotoTmp);
    $hashFoto = md5($fotoConteudo); // ou sha1($fotoConteudo)

    // Verifica se já existe essa foto para o usuário
    $stmt = $pdo->prepare("SELECT foto FROM usuario WHERE id = ? AND foto_hash = ?");
    $stmt->execute([$idUsuario, $hashFoto]);
    $fotoExistente = $stmt->fetchColumn();

    if ($fotoExistente) {
        // Foto já existe, não salva novamente
        $_SESSION['foto_perfil'] = $fotoExistente;
        echo "Foto já existe";
        exit;
    }

    // Gera novo nome único
    $extensao = strtolower(pathinfo($_FILES['novaFoto']['name'], PATHINFO_EXTENSION));
    $novoNome = 'foto_' . $idUsuario . '_' . time() . '.' . $extensao;
    $pastaDestino = '../uploads/';
    if (!is_dir($pastaDestino)) mkdir($pastaDestino, 0777, true);
    $caminhoFinal = $pastaDestino . $novoNome;

    if (move_uploaded_file($fotoTmp, $caminhoFinal)) {
        // Atualiza banco com caminho e hash
        $stmt = $pdo->prepare("UPDATE usuario SET foto = ?, foto_hash = ? WHERE id = ?");
        $stmt->execute([$caminhoFinal, $hashFoto, $idUsuario]);

        $_SESSION['foto_perfil'] = $caminhoFinal;
        echo "Foto enviada com sucesso";
    } else {
        echo "Erro ao enviar a foto.";
    }
}

?>
