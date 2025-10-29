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

<div class="container mt-4">
  <div class="row g-4">

    <!-- Serviços -->
    <div class="col-md-6 col-lg-4">
      <div class="card text-white mb-3" style="background-color: #0078ff; min-height: 180px;">
        <div class="card-header">Serviços na Clínica</div>
        <div class="card-body">
          <h5 class="card-title">Total de Serviços</h5>
          <p class="card-text counter" data-target="<?= $totalServicos ?>">0</p>
        </div>
      </div>
    </div>

    <!-- Agendamentos -->
    <div class="col-md-6 col-lg-4">
      <div class="card text-white mb-3" style="background-color: #28a745; min-height: 180px;">
        <div class="card-header">Agendamentos</div>
        <div class="card-body">
          <h5 class="card-title">Total de Agendamentos</h5>
          <p class="card-text counter" data-target="<?= $totalAgendamentos ?>">0</p>
        </div>
      </div>
    </div>

    <!-- Pacientes -->
    <div class="col-md-6 col-lg-4">
      <div class="card text-white mb-3" style="background-color: #17a2b8; min-height: 180px;">
        <div class="card-header">Pacientes</div>
        <div class="card-body">
          <h5 class="card-title">Pacientes Cadastrados</h5>
          <p class="card-text counter" data-target="<?= $totalPacientes ?>">0</p>
        </div>
      </div>
    </div>

    <!-- Fisioterapeutas -->
    <div class="col-md-6 col-lg-4">
      <div class="card text-white mb-3" style="background-color: #ffc107; min-height: 180px;">
        <div class="card-header">Fisioterapeutas</div>
        <div class="card-body">
          <h5 class="card-title">Total de Fisioterapeutas</h5>
          <p class="card-text counter" data-target="<?= $totalFisioterapeutas ?>">0</p>
        </div>
      </div>
    </div>

    <!-- Usuários -->
    <div class="col-md-6 col-lg-4">
      <div class="card text-white mb-3" style="background-color: #dc3545; min-height: 180px;">
        <div class="card-header">Usuários</div>
        <div class="card-body">
          <h5 class="card-title">Usuários do Sistema</h5>
          <p class="card-text counter" data-target="<?= $totalUsuarios ?>">0</p>
        </div>
      </div>
    </div>

  </div>
</div>

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
