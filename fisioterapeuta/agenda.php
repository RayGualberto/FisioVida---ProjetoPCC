<?php
require_once '../php/db.php';
session_start();
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

// ==== Buscar atendimentos concluídos ====
// Pegando somente atendimentos aos quais o agendamento foi concluído
$sqlAtendimentos = "
    SELECT 
        a.id_atendimento,
        a.data,
        ag.nome_paciente,
        ag.descricao_servico,
        ag.data AS data_consulta,
        ag.hora
    FROM atendimento a
    INNER JOIN agenda ag ON a.agenda_id = ag.id_Agenda
    WHERE ag.status = 'concluido'
    ORDER BY a.data DESC
";
$stmt = $pdo->prepare($sqlAtendimentos);
$stmt->execute();
$atendimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/partials/header.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel Fisioterapeuta - FisioVida</title>
  <link rel="icon" href="../img/Icone fisiovida.jfif">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
 /* Cabeçalho */
  h2.h4 {
    color: #000; /* TEXTO PRETO */
    font-weight: 500;
  }

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

    th {
      font-weight: 600;
      padding: 12px;
      border: none;
    }

    tbody tr {
      background: #ffffff;
      transition: all 0.25s ease-in-out;
      border-radius: 12px;
    }

    tbody tr:hover {
      background-color: #e3f7f1;
      transform: scale(1.01);
    }

    td {
      vertical-align: middle;
      text-align: center;
      padding: 10px;
      color: #333;
    }

    .btn {

      transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
      background-color: #6ddccf;
      color: #fff;
      border-color: #6ddccf;
    }

    .btn-outline-danger:hover {
      background-color: #ff6b6b;
      color: #fff;
      border-color: #ff6b6b;
    }

    /* Filtro */
    form.card {
      background-color: #ffffffd9;
      border-radius: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    form label {
      font-weight: 500;
      color: #004b87;
    }

    form input {
      border-radius: 8px;
      border: 1px solid #a9d3ff;
      transition: 0.3s;
    }

    form input:focus {
      border-color: #6ddccf;
      box-shadow: 0 0 4px #6ddccf;
    }

    /* Mensagem de vazio */
    .no-results {
      color: #999;
      padding: 20px;
      font-style: italic;
    }
</style>

</head>


  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0" data-aos="fade-right">Painel de Fisioterapeuta - FisioVida</h2>
    <span class="badge text-bg-primary" data-aos="fade-left">Perfil: Fisioterapeuta</span>
  </div>
<div class=" mt-4">
  <!-- Filtro de Agenda -->
  <form method="get" class="card card-body shadow-sm mb-3" data-aos="zoom-in">
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
        <a class="btn btn-outline-secondary mt-3" href="agenda.php">Limpar</a>
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
          <thead data-aos="fade-down">
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
                    'concluido' => 'primary',
                    'confirmado' => 'success',
                    'recusado' => 'danger',
                    'remarcado' => 'info',
                    'pendente' => 'warning',
                    default => 'secondary'
                };
              ?>
              <tr data-aos="fade-up">
                <td><?= (int)$a['id_Agenda'] ?></td>
                <td><?= htmlspecialchars($a['nome_paciente']) ?></td>
                <td><?= htmlspecialchars($a['data']) ?></td>
                <td><?= htmlspecialchars($a['data_agendamento']) ?></td>
                <td><?= htmlspecialchars($a['hora']) ?></td>
                <td><?= htmlspecialchars($a['descricao_servico']) ?></td>
                <td><span class="badge text-bg-<?= $badgeClass ?>"><?= ucfirst($status) ?></span></td>
                <td class="text-end">

    
  <!-- Botão CONCLUIR (Azul) -->
  <?php if ($status === 'concluido'): ?>

<span class="badge text-bg-primary">Sessão concluída</span>
  <?php elseif ($status === 'confirmado'): ?>
  <form action="concluir_agenda.php" method="post" class="d-inline">
    <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
    <button type="submit" class="btn btn-sm btn-outline-primary">Concluir</button>
  </form>
<?php else: ?>
  
  <!-- Botões padrão (Confirmar / Recusar / Remarcar) -->
  <form action="confirmar_agenda.php" method="post" class="d-inline">
    <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
    <button type="submit" class="btn btn-sm btn-outline-success">Confirmar</button>
  </form>

  <form action="recusar_agenda.php" method="post" class="d-inline">
    <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger">Recusar</button>
  </form>

  <form action="remarcar_agenda.php" method="post" class="d-inline">
    <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
    <button type="submit" class="btn btn-sm btn-outline-warning text-dark">Remarcar</button>
  </form>

<?php endif; ?>

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
</div>
<!-- Lista de Atendimentos Concluídos -->
<div class="card shadow-sm mb-4" data-aos="fade-up">
    <div class="card-header">
        Atendimentos Concluídos (<?= count($atendimentos) ?>)
    </div>

    <div class="card-body">
        <?php if (count($atendimentos) === 0): ?>
            <p class="text-muted mb-0">Nenhum atendimento concluído ainda.</p>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($atendimentos as $at): ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100" data-aos="zoom-in">
                            <div class="card-body">
                                <h5 class="card-title text-primary">
                                    <?= htmlspecialchars($at['nome_paciente']) ?>
                                </h5>

                                <p class="mb-1">
                                    <strong>Serviço:</strong><br>
                                    <?= htmlspecialchars($at['descricao_servico']) ?>
                                </p>

                                <p class="mb-1">
                                    <strong>Data da Consulta:</strong><br>
                                    <?= htmlspecialchars($at['data_consulta']) ?> às <?= htmlspecialchars($at['hora']) ?>
                                </p>

                                <p class="mb-1">
                                    <strong>Data do Atendimento:</strong><br>
                                    <?= htmlspecialchars($at['data']) ?>
                                </p>

                                <span class="badge text-bg-primary mt-2">Concluído</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</html>
