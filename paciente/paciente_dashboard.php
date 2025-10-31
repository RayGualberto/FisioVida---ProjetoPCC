<?php
require_once '../php/db.php'; // Certifique-se que $pdo é PDO

include __DIR__ . '/partials/header.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0">Painel Paciente - FisioVida</h2>

    <span class="badge text-bg-primary">Perfil: paciente</span>
  </div>
<!-- Seção HOME -->
  <div class="container">
    <h1 class="display-5 fw-bold">Bem-vindo à Fisiovida, <?= htmlspecialchars($nomePaciente); ?>!</h1>
    <p class="lead mt-3">Cuidando de você com excelência, humanidade e tecnologia.</p>
  </div>

