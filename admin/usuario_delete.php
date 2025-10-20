<?php

require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: admin.php');
    exit;
}

// Evita deletar a si mesmo
if ($id === (int)($_SESSION['user_id'] ?? 0)) {
    header('Location: admin.php');
    exit;
}

// Exclui o usuário da tabela 'usuario'
$stmt1 = $pdo->prepare('DELETE FROM usuario WHERE id=?');
$stmt1->execute([$id]);

header('Location: admin.php');
exit;
