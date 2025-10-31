<?php
require_once '../php/db.php';
session_start();

$idUsuario = $_SESSION['usuario_id'];
$fotoPadrao = '../img/imagem_perfil.JPEG';

$stmt = $pdo->prepare("UPDATE usuario SET foto = ? WHERE id = ?");
$stmt->execute([$fotoPadrao, $idUsuario]);

// Atualiza sessão
$_SESSION['foto_perfil'] = $fotoPadrao;

echo 'ok';
