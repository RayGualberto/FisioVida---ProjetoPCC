<?php
// Inicia sessão (apenas se ainda não iniciada)
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../php/db.php'; // Conexão com o banco

// Inclusão correta do header
include __DIR__ . '/partials/header.php'; // ajuste o caminho conforme a localização da pasta 'partials'

// Garantir que a variável $nomePaciente exista
$nomePaciente = $_SESSION['usuario_nome'] ?? 'Paciente';
?>

<!-- Conteúdo principal do dashboard -->
<div id="main-content">
    <!-- Cabeçalho do painel -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h4 mb-0">Painel Paciente - FisioVida</h2>
        <span class="badge text-bg-primary">Perfil: paciente</span>
    </div>

    <!-- Seção HOME -->
    <div class="container">
        <h1 class="display-5 fw-bold">Bem-vindo à Fisiovida, <?= htmlspecialchars($nomePaciente); ?>!</h1>
        <p class="lead mt-3">Cuidando de você com excelência, humanidade e tecnologia.</p>
    </div>

    <!-- Aqui você pode adicionar cards ou outras seções do dashboard -->
</div>
