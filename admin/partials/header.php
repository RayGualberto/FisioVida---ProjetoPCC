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
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">FisioVida</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <?php if ($userRole === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="admin.php">Dashboard</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if ($userName): ?>
          <li class="nav-item me-3 align-self-center text-secondary">Olá, <strong><?= htmlspecialchars($userName) ?></strong></li>
          <li class="nav-item me-2"><a class="btn btn-outline-secondary btn-sm" href="profile.php">Meu Perfil</a></li>
          <li class="nav-item"><a class="btn btn-outline-danger btn-sm" href="logout.php">Sair</a></li>
        <?php endif; ?>
      </ul>
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
<div class="container">
