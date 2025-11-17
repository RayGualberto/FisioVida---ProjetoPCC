<?php
// Inicia sessão (apenas se ainda não iniciada)
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../php/db.php'; // Conexão com o banco

// Inclusão correta do header
include __DIR__ . '../partials/header.php'; // ajuste o caminho conforme a localização da pasta 'partials'

// Garantir que a variável $nomePaciente exista
$nomePaciente = $_SESSION['usuario_nome'] ?? 'Paciente';
?>

<!-- Conteúdo principal do dashboard -->
<div id="main-content">

    <!-- Cabeçalho do painel -->
    <div class="d-flex align-items-center justify-content-between mb-3"
         data-aos="fade-down">
        <h2 class="h4 mb-0" data-aos="fade-right" data-aos-delay="150">
            Painel Paciente - FisioVida
        </h2>

        <span class="badge text-bg-primary"
              data-aos="zoom-in"
              data-aos-delay="300">
            Perfil: paciente
        </span>
    </div>

    <!-- Seção HOME -->
    <div class="container"
         data-aos="fade-up"
         data-aos-delay="200">

        <h1 class="display-5 fw-bold"
            data-aos="fade-right"
            data-aos-delay="250">
            Bem-vindo à Fisiovida, <?= htmlspecialchars($nomePaciente); ?>!
        </h1>

        <p class="lead mt-3"
           data-aos="fade-left"
           data-aos-delay="350">
            Cuidando de você com excelência, humanidade e tecnologia.
        </p>

    </div>

    <!-- Futuras seções do dashboard podem receber animações também -->
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
    