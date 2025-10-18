<?php
// partials/header.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Adaptação para o banco "fisiovida"
$userName = $_SESSION['nome'] ?? null;           // nome do usuário
$userRole = $_SESSION['tipo_usuario'] ?? null;   // tipo de usuário (paciente, fisioterapeuta, admin)
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FisioVida - Sistema</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-light sticky-xxl-top">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <!-- Logo colado à esquerda -->
    <a href="#" class="navbar-brand">
      <img src="../img/Fisiovida logo.png" alt="imagemfisiovida" width="120" height="90">
    </a>

    <!-- Botão toggle para mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menunavbar" aria-controls="menunavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu e botões alinhados à direita -->
    <div class="collapse navbar-collapse justify-content-end" id="menunavbar">
        <div class="ms-3 d-flex">
        <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="btn btn-outline-danger btn-sm" href="../php/logout.php">Sair</a></li>
        </ul>
         <div class="ms-3 d-flex">
        <button id="themeToggle" class="btn btn-sm btn-outline-primary" type="button" title="Alternar tema">Tema</button>
      </div>
      </div>
    </div>
  </div>
</nav>
<div class="container-fluid my-4 px-4">
