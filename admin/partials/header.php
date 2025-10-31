<?php
// partials/header.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Adaptação para o banco "fisiovida"
$userName = $_SESSION['nome'] ?? null;           
$userRole = $_SESSION['tipo_usuario'] ?? null;   
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FisioVida - Adiministrador</title>
  <link rel="icon" href="../img/Icone fisiovida.jfif">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style> 
  body {
    min-height: 100vh;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-size: cover;
  }

  /* Sidebar lateral moderna */
  #sidebar {
    width: 230px;
    background: #0b8ecb; /* azul escuro elegante */
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
    z-index: 1000;
  }

  #sidebar .nav-link {
    width: 85%;
    color: #e6e6e6;
    padding: 12px 15px;
    margin: 6px 0;
    border-radius: 10px;
    font-weight: normal;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
  }

  #sidebar .nav-link i {
    font-size: 1.2rem;
  }

  #sidebar .nav-link:hover {
    background-color: #0078ff;
    color: #fff;
    transform: translateX(4px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
    text-decoration: none;
  }

  #sidebar .nav-link.active {
    background-color: #0078ff;
    color: #fff;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
  }

  /* Conteúdo principal */
  #main-content {
    margin-left: 230px;
    padding: 25px;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
  }

  /* Navbar superior */
  .navbar {
    z-index: 1100;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }

  /* Responsividade */
  @media (max-width: 768px) {
    #sidebar {
      position: relative;
      width: 100%;
      height: auto;
      flex-direction: row;
      justify-content: space-around;
      padding-top: 10px;
    }

    #sidebar .nav-link {
      flex: 1;
      text-align: center;
      margin: 0 5px;
      border-radius: 6px;
    }

    #main-content {
      margin-left: 0;
    }
  }

  #sidebar .nav-link:active,
  #sidebar .nav-link:focus,
  #sidebar .nav-link:visited {
    font-weight: normal;
  }
</style>
</head>

<body>

<!-- Navbar superior -->
<nav class="navbar navbar-expand-md bg-light sticky-top">
  <div class="container-fluid d-flex justify-content-between align-items-center" style="height: 33px;">
    <a href="admin.php" class="navbar-brand">
      <img src="../img/Fisiovida logo.png" alt="imagemfisiovida" width="120" height="78">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menunavbar" aria-controls="menunavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="menunavbar">
      <div class="ms-3 d-flex align-items-center">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="btn btn-outline-danger btn-sm" href="../php/logout.php">Sair</a>
          </li>
        </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- Sidebar lateral -->
<div id="sidebar">
  <a href="admin.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin.php') ? 'active' : ''; ?>"><i class="bi bi-house-door"></i> Início</a>
  <a href="servicos.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'servicos.php') ? 'active' : ''; ?>"><i class="bi bi-briefcase"></i> Serviços</a>
  <a href="agendamentos.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'agendamentos.php') ? 'active' : ''; ?>"><i class="bi bi-calendar-check"></i> Agendamentos</a>
  <a href="usuarios.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'usuarios.php') ? 'active' : ''; ?>"><i class="bi bi-people"></i> Usuários</a>
  <a href="prontuario.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'prontuario.php') ? 'active' : ''; ?>"><i class="bi bi-clipboard"></i> Prontuários</a>
</div>
  

<!-- Conteúdo principal -->
<div id="main-content">
  <!-- Aqui vai todo o seu conteúdo -->
