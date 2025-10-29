<?php

require_once '../php/db.php';

// Parâmetros de filtro e paginação
$qAgenda = trim($_GET['q_agenda'] ?? '');
$statusFiltro = $_GET['status'] ?? ''; // Novo filtro de status
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 8;

// ==== Filtro Agenda ====
$agendaClauses = [];
$agendaParams  = [];

if ($qAgenda !== '') {
    $agendaClauses[] = "(nome_paciente LIKE ? OR data LIKE ? OR descricao_servico LIKE ?)";
    $like = "%$qAgenda%";
    $agendaParams[] = $like;
    $agendaParams[] = $like;
    $agendaParams[] = $like;
}

if ($statusFiltro !== '' && in_array($statusFiltro, ['pendente', 'confirmado', 'remarcado', 'recusado'])) {
    $agendaClauses[] = "status = ?";
    $agendaParams[] = $statusFiltro;
}

$agendaWhereSql = $agendaClauses ? ('WHERE ' . implode(' AND ', $agendaClauses)) : '';

// ==== Paginação ====
$stmt = $pdo->prepare("SELECT COUNT(*) FROM agenda $agendaWhereSql");
$stmt->execute($agendaParams);
$totalAgendamentos = (int)$stmt->fetchColumn();
$pagesAgendamentos = max(1, (int)ceil($totalAgendamentos / $perPage));
$offsetAgendamentos = ($page - 1) * $perPage;

// ==== Consulta da agenda ====
$sqlAgendamentos = "SELECT id_Agenda, nome_paciente, data, data_agendamento, hora, descricao_servico, status
                    FROM agenda
                    $agendaWhereSql
                    ORDER BY id_Agenda DESC
                    LIMIT $perPage OFFSET $offsetAgendamentos";
$stmt = $pdo->prepare($sqlAgendamentos);
$stmt->execute($agendaParams);
$agenda = $stmt->fetchAll();

include __DIR__ . '/partials/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo - FisioVida</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  /* Corpo e fundo */
  body {
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(135deg, #5b4fc7 0%, #7a5df0 50%, #a69afc 100%);
    color: #000; /* TEXTO PRETO */
    min-height: 100vh;
  }

  /* Cabeçalho */
  h2.h4 {
    color: #000; /* TEXTO PRETO */
    font-weight: 500;
  }

  .badge.text-bg-primary {
    background-color: #3c2f91;
    color: #fff; /* Mantemos contraste no badge */
    font-weight: 500;
  }

  /* Formulário de filtro */
  form.card {
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 12px;
    border: none;
  }

  form.card .form-control,
  form.card .form-select {
    border-radius: 8px;
    background-color: #fff;
    color: #000; /* TEXTO PRETO */
    border: 1px solid rgba(0, 0, 0, 0.2);
  }

  form.card .form-control::placeholder {
    color: rgba(0, 0, 0, 0.5);
  }

  /* Botões do filtro */
  form.card .btn-primary {
    background-color: #6b4ffc;
    border-color: #6b4ffc;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    color: #fff;
  }

  form.card .btn-primary:hover {
    background-color: #5841d3;
    border-color: #5841d3;
  }

  form.card .btn-outline-secondary {
    color: #000;
    border-color: #000;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  form.card .btn-outline-secondary:hover {
    background-color: rgba(0, 0, 0, 0.1);
  }

  /* Tabela */
  .card.shadow-sm {
    border-radius: 12px;
    background-color: rgba(255, 255, 255, 0.85);
    border: none;
    color: #000; /* TEXTO PRETO */
  }

  .card-header {
    background-color: rgba(60, 47, 145, 0.8);
    border-bottom: none;
    font-weight: 500;
    font-size: 1rem;
    color: #fff;
  }

  table.table {
    color: #000; /* TEXTO PRETO */
  }

  table.table th {
    color: #000; /* TEXTO PRETO */
    font-weight: 500;
  }

  table.table td {
    color: #000; /* TEXTO PRETO */
  }

  table.table tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
  }

  /* Badges de status */
  .badge.text-bg-success { background-color: #4cd964; color: #000; }
  .badge.text-bg-danger  { background-color: #ff5c5c; color: #000; }
  .badge.text-bg-warning { background-color: #ffcc00; color: #000; }
  .badge.text-bg-info    { background-color: #5ac8fa; color: #000; }
  .badge.text-bg-secondary { background-color: #b0b0c0; color: #000; }

  /* Botões de ações na tabela */
  .table .btn-sm {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.25rem 0.6rem;
    transition: all 0.3s ease;
    color: #fff;
  }

  .table .btn-success { background-color: #4cd964; border-color: #4cd964; }
  .table .btn-success:hover { background-color: #3ac13a; }

  .table .btn-danger  { background-color: #ff5c5c; border-color: #ff5c5c; }
  .table .btn-danger:hover  { background-color: #e64c4c; }

  .table .btn-warning { background-color: #ffcc00; border-color: #ffcc00; color: #000; }
  .table .btn-warning:hover { background-color: #e6b800; color: #000; }

  /* Responsividade e cards */
  .table-responsive {
    border-radius: 12px;
    overflow: hidden;
  }
</style>

</head>
<body>
  
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0 text-white">Painel de Fisioterapeuta - FisioVida</h2>

    <span class="badge text-bg-primary">Perfil: Fisioterapeuta</span>
  </div>

  <!-- Filtro de Agenda -->
  <form method="get" class="card card-body shadow-sm mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-5">
        <label class="form-label">Buscar Agendamento</label>
        <input type="text" name="q_agenda" class="form-control" 
               value="<?= htmlspecialchars($qAgenda) ?>" 
               placeholder="Nome do paciente, data ou tipo de serviço">
      </div>
      <div class="col-md-3">
        <label class="form-label">Filtrar por Status</label>
        <select name="status" class="form-select">
          <option value="">Todos</option>
          <option value="pendente"   <?= $statusFiltro === 'pendente'   ? 'selected' : '' ?>>Pendente</option>
          <option value="confirmado" <?= $statusFiltro === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
          <option value="remarcado"  <?= $statusFiltro === 'remarcado'  ? 'selected' : '' ?>>Remarcado</option>
          <option value="recusado"   <?= $statusFiltro === 'recusado'   ? 'selected' : '' ?>>Recusado</option>
        </select>
      </div>
      <div class="col-md-4 text-end">
        <a class="btn btn-outline-secondary mt-3" href="fisio_dashboard.php">Limpar</a>
        <button class="btn btn-primary mt-3">Filtrar</button>
      </div>
    </div>
  </form>

  <!-- Tabela de Agendamentos -->
  <div class="card shadow-sm mb-4">
    <div class="card-header">Agendamentos encontrados (<?= $totalAgendamentos ?>)</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0 align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome do Paciente</th>
              <th>Data da Consulta</th>
              <th>Data do Agendamento</th>
              <th>Hora da Consulta</th>
              <th>Tipo do Serviço</th>
              <th>Status</th>
              <th class="text-end">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($agenda as $a): ?>
              <?php
                // Define cor do status
                $status = htmlspecialchars($a['status']);
                $badgeClass = match ($status) {
                    'confirmado' => 'success',
                    'recusado' => 'danger',
                    'remarcado' => 'info',
                    'pendente' => 'warning',
                    default => 'secondary'
                };
              ?>
              <tr>
                <td><?= (int)$a['id_Agenda'] ?></td>
                <td><?= htmlspecialchars($a['nome_paciente']) ?></td>
                <td><?= htmlspecialchars($a['data']) ?></td>
                <td><?= htmlspecialchars($a['data_agendamento']) ?></td>
                <td><?= htmlspecialchars($a['hora']) ?></td>
                <td><?= htmlspecialchars($a['descricao_servico']) ?></td>
                <td><span class="badge text-bg-<?= $badgeClass ?>"><?= ucfirst($status) ?></span></td>
                <td class="text-end">
                  <form action="confirmar_agenda.php" method="post" class="d-inline">
                    <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
                    <button type="submit" class="btn btn-sm btn-success">Confirmar</button>
                  </form>
                  <form action="recusar_agenda.php" method="post" class="d-inline">
                    <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Recusar</button>
                  </form>
                  <form action="remarcar_agenda.php" method="post" class="d-inline">
                    <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
                    <button type="submit" class="btn btn-sm btn-warning">Remarcar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>

            <?php if (!$agenda): ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  Nenhum agendamento encontrado.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
