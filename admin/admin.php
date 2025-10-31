<?php
require_once '../php/db.php';

// ===== CONTADORES =====

// Total de serviços
$stmt = $pdo->query("SELECT COUNT(*) FROM servico");
$totalServicos = (int)$stmt->fetchColumn();

// Total de agendamentos
$stmt = $pdo->query("SELECT COUNT(*) FROM agenda");
$totalAgendamentos = (int)$stmt->fetchColumn();

// Total de pacientes
$stmt = $pdo->query("SELECT COUNT(*) FROM paciente");
$totalPacientes = (int)$stmt->fetchColumn();

// Total de fisioterapeutas
$stmt = $pdo->query("SELECT COUNT(*) FROM fisioterapeuta");
$totalFisioterapeutas = (int)$stmt->fetchColumn();

// Total de usuários no sistema
$stmt = $pdo->query("SELECT COUNT(*) FROM usuario");
$totalUsuarios = (int)$stmt->fetchColumn();

include __DIR__ . '/partials/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
  .container {
  display: flex;
  flex-direction: column;
  align-items: center;   /* Centraliza horizontalmente */
  justify-content: center; /* Centraliza verticalmente */
  min-height: 60vh;     /* Faz ocupar a altura da tela */
}

.container .row {
  justify-content: center; /* Centraliza os cards dentro da row */
  width: 100%;
  max-width: 1200px;       /* Evita que os cards fiquem muito esticados */
}

</style>
  <div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="h4 mb-0">Painel de Administrador - FisioVida</h2>
  <span class="badge text-bg-primary">Perfil: Adiministrador</span>
</div>

<div class="container mt-4">
  <div class="row g-4">

    <!-- Serviços -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-primary mb-3" style="min-height: 180px;">
        <div class="card-header text-white" style="background-color: #0078ff;">Serviços na Clínica</div>
        <div class="card-body">
          <h5 class="card-title">Total de Serviços</h5>
          <p class="card-text fs-4 fw-bold text-primary counter" data-target="<?= $totalServicos ?>">0</p>
        </div>
      </div>
    </div>

    <!-- Agendamentos -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-success mb-3" style="min-height: 180px;">
        <div class="card-header text-white" style="background-color: #28a745;">Agendamentos</div>
        <div class="card-body">
          <h5 class="card-title">Total de Agendamentos</h5>
          <p class="card-text fs-4 fw-bold text-success counter" data-target="<?= $totalAgendamentos ?>">0</p>
        </div>
      </div>
    </div>

    <!-- Pacientes -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-info mb-3" style="min-height: 180px;">
        <div class="card-header text-white" style="background-color: #17a2b8;">Pacientes</div>
        <div class="card-body">
          <h5 class="card-title">Pacientes Cadastrados</h5>
          <p class="card-text fs-4 fw-bold text-info counter" data-target="<?= $totalPacientes ?>">0</p>
        </div>
      </div>
    </div>

    <!-- Fisioterapeutas -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-warning mb-3" style="min-height: 180px;">
        <div class="card-header text-dark" style="background-color: #ffc107;">Fisioterapeutas</div>
        <div class="card-body">
          <h5 class="card-title">Total de Fisioterapeutas</h5>
          <p class="card-text fs-4 fw-bold text-warning counter" data-target="<?= $totalFisioterapeutas ?>">0</p>
        </div>
      </div>
    </div>

    <!-- Usuários -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-danger mb-3" style="min-height: 180px;">
        <div class="card-header text-white" style="background-color: #dc3545;">Usuários</div>
        <div class="card-body">
          <h5 class="card-title">Usuários do Sistema</h5>
          <p class="card-text fs-4 fw-bold text-danger counter" data-target="<?= $totalUsuarios ?>">0</p>
        </div>
      </div>
    </div>

  </div>
</div>
</html>
<!-- Contador animado -->
<script>
  const counters = document.querySelectorAll('.counter');

  counters.forEach(counter => {
    const updateCount = () => {
      const target = +counter.getAttribute('data-target');
      const count = +counter.innerText;
      const increment = target / 800; // animação mais lenta

      if(count < target) {
        counter.innerText = Math.ceil(count + increment);
        setTimeout(updateCount, 90);
      } else {
        counter.innerText = target;
      }
    }
    updateCount();
  });
</script>
