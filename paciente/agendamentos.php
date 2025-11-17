<?php
require_once '../php/db.php';
include __DIR__ . '../partials/header.php';

// Parâmetros de filtro e paginação
$page         = max(1, (int)($_GET['page'] ?? 1));
$perPage      = 8;
$qAgenda      = trim($_GET['q_agenda'] ?? '');
$statusFiltro = $_GET['status'] ?? '';

// ===== Paginação =====
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

$stmt = $pdo->prepare("SELECT COUNT(*) FROM agenda $agendaWhereSql");
$stmt->execute($agendaParams);
$totalagendamentos = (int)$stmt->fetchColumn();
$pagesagendamentos = max(1, ceil($totalagendamentos / $perPage));
$offsetagendamentos = ($page - 1) * $perPage;

$sqlagendamentos = "SELECT id_Agenda, nome_paciente, data, data_agendamento, hora, descricao_servico, status
                    FROM agenda
                    $agendaWhereSql
                    WHERE paciente_id_paciente = '$IdUsuarioPaciente'
                    ORDER BY id_Agenda DESC
                    LIMIT $perPage OFFSET $offsetagendamentos";
$stmt = $pdo->prepare($sqlagendamentos);
$stmt->execute($agendaParams);
$agenda = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendamentos FisioVida</title>

  <style>
    h2.h4 { color: #000; font-weight: 500; }

    .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .card-header {
      background: linear-gradient(90deg, #0099ff, #4cd3a5);
      color: white;
      font-weight: 500;
      font-size: 1rem;
      border-bottom: none;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    table {
      border-collapse: separate;
      border-spacing: 0 6px;
    }

    thead tr {
      background: #cfe9ff;
      color: #003c82;
      text-align: center;
    }

    tbody tr:hover {
      background-color: #e3f7f1;
      transform: scale(1.01);
    }

    .no-results {
      color: #999;
      padding: 20px;
      font-style: italic;
    }
  </style>
</head>

<!-- TÍTULO E BADGE -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0" data-aos="fade-right">Meus Agendamentos - FisioVida</h2>
    <span class="badge text-bg-primary" data-aos="fade-left">Perfil: Paciente</span>
</div>

<div class="mt-4">

  <!-- FILTRO -->
  <form method="get" class="card card-body shadow-sm mb-3" data-aos="zoom-in">
    <div class="row g-2 align-items-end">
      <div class="col-md-5">
        <label class="form-label">🔎 Buscar Agendamento</label>
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

  <!-- TABELA -->
  <div class="card shadow-sm mb-4" data-aos="fade-up">
    <div class="card-header">
      Agendamentos Encontrados (<?= $totalagendamentos ?>)
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0 align-middle">
          <thead data-aos="fade-down">
            <tr>
              <th>#</th>
              <th>Nome do Paciente</th>
              <th>Data da Consulta</th>
              <th>Data do Agendamento</th>
              <th>Hora</th>
              <th>Serviço</th>
              <th>Status</th>
              <th class="text-end">Ações</th>
            </tr>
          </thead>

          <tbody>
            <?php if ($agenda): ?>
              <?php foreach ($agenda as $a): ?>
                <?php
                  $status = htmlspecialchars($a['status']);
                  $badgeClass = match ($status) {
                      'confirmado' => 'success',
                      'recusado' => 'danger',
                      'remarcado' => 'info',
                      'pendente' => 'warning',
                      default => 'secondary'
                  };
                ?>
                <tr data-aos="fade-up" data-aos-delay="100">
                  <td><?= (int)$a['id_Agenda'] ?></td>
                  <td><?= htmlspecialchars($a['nome_paciente']) ?></td>
                  <td><?= htmlspecialchars($a['data']) ?></td>
                  <td><?= htmlspecialchars($a['data_agendamento']) ?></td>
                  <td><?= htmlspecialchars($a['hora']) ?></td>
                  <td><?= htmlspecialchars($a['descricao_servico']) ?></td>
                  <td><span class="badge text-bg-<?= $badgeClass ?>"><?= ucfirst($status) ?></span></td>
                  <td class="text-end">
                    <form action="cancelar_agenda.php" method="post" class="d-inline">
                      <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                    </form>

                    <form action="remarcar_agenda.php" method="post" class="d-inline">
                      <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-warning text-dark">Remarcar</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr data-aos="fade-up">
                <td colspan="8" class="text-center no-results">Nenhum agendamento encontrado.</td>
              </tr>
            <?php endif; ?>
          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
</html>
