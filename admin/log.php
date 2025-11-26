<?php
session_start();
require_once '../php/db.php'; // mantém o $pdo (caminho: admin/log.php -> ../php/db.php)

// --- filtros via GET ---
$destinatario = $_GET['destinatario'] ?? '';
$tipo         = $_GET['tipo'] ?? '';
$lida         = $_GET['lida'] ?? '';

// --- construção dinâmica do WHERE ---
$where = [];
$params = [];

if ($destinatario !== '') {
    $where[] = "(d.nome LIKE :destinatario OR r.nome LIKE :destinatario)";
    $params[':destinatario'] = "%$destinatario%";
}

if ($tipo !== '') {
    $where[] = "n.tipo = :tipo";
    $params[':tipo'] = $tipo;
}

if ($lida !== '') {
    $where[] = "n.lida = :lida";
    $params[':lida'] = $lida;
}

$where_sql = '';
if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

// --- consulta notificações ---
try {
    $stmt = $pdo->prepare("
        SELECT n.*, 
               r.nome AS remetente_nome, 
               d.nome AS destinatario_nome
        FROM notificacoes n
        LEFT JOIN usuario r ON r.cpf = n.remetente_cpf
        LEFT JOIN usuario d ON d.cpf = n.destinatario_cpf
        $where_sql
        ORDER BY n.data_envio DESC
    ");
    $stmt->execute($params);
    $notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tipos = $pdo->query("SELECT DISTINCT tipo FROM notificacoes")->fetchAll(PDO::FETCH_COLUMN) ?: [];

} catch (PDOException $e) {
    die("Erro ao buscar notificações: " . $e->getMessage());
}

// --- helpers para badges ---
function badgeTipo(string $tipo): string {
    $t = strtolower($tipo);
    switch ($t) {
        case 'remarcado':
        case 'remarcar':
            return '<span class="badge badge-remarcado">Remarcado</span>';
        case 'recusado':
        case 'recusar':
            return '<span class="badge badge-recusado">Recusado</span>';
        case 'confirmado':
        case 'aceito':
            return '<span class="badge badge-confirmado">Confirmado</span>';
        case 'agendamento':
        case 'agendado':
            return '<span class="badge badge-agendamento">Agendamento</span>';
        default:
            return '<span class="badge badge-secondary">'.htmlspecialchars(ucfirst($tipo)).'</span>';
    }
}

function badgeLida($lida): string {
    // aceita 1, '1', true como lida
    if ($lida == 1) {
        return '<span class="badge badge-lida">Lida</span>';
    }
    return '<span class="badge badge-nao-lida">Não</span>';
}
?>

<?php include 'partials/header.php'; ?>

<!-- Estilos específicos desta página (pode mover pro CSS global depois) -->
<style>
  /* Geral */
  .container-fluid.py-4 { padding-top: 24px; padding-bottom: 24px; }

  h2.h4 { color: #000; font-weight: 500; }

  .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  }

  .card-header {
      background: linear-gradient(90deg, #0099ff, #4cd3a5);
      color: white;
      font-weight: 600;
      font-size: 0.95rem;
      border-bottom: none;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 12px 18px;
  }

  /* Tabela e espaçamento */
  .table {
      border-collapse: separate;
      border-spacing: 0 8px;
      margin-bottom: 0;
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
      vertical-align: middle;
  }

  tbody tr {
      background: #ffffff;
      transition: all 0.22s ease-in-out;
      border-radius: 12px;
  }

  tbody tr:hover {
      background-color: #e3f7f1;
      transform: scale(1.005);
  }

  td {
      vertical-align: middle;
      text-align: center;
      padding: 10px;
      color: #333;
      border: none;
  }

  /* Badges tipo */
  .badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 12px;
      font-size: 0.85rem;
      font-weight: 700;
      line-height: 1;
  }

  .badge-remarcado {
      background-color: #00cfff;
      color: #000;
  }

  .badge-recusado {
      background-color: #d9534f;
      color: #fff;
  }

  .badge-confirmado {
      background-color: #28a745;
      color: #fff;
  }

  .badge-agendamento {
      background-color: #ffc107;
      color: #000;
  }

  .badge-secondary {
      background-color: #6c757d;
      color: #fff;
  }

  /* Badge Lida */
  .badge-lida {
      background-color: #198754;
      color: #fff;
  }
  .badge-nao-lida {
      background-color: #dc3545;
      color: #fff;
  }

  /* Filtro (form) */
  form.card {
      background-color: #ffffffd9;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.04);
      margin-bottom: 18px;
  }

  form .form-control, form .form-select {
      border-radius: 8px;
      border: 1px solid #a9d3ff;
      transition: 0.25s;
  }

  form .form-control:focus, form .form-select:focus {
      border-color: #6ddccf;
      box-shadow: 0 0 6px #6ddccf33;
  }

  .no-results {
      color: #777;
      padding: 18px;
      font-style: italic;
      text-align: center;
  }

  /* Responsividade */
  .table-responsive {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
  }

  @media (max-width: 991px) {
      th, td { font-size: 0.85rem; padding: 8px; }
      .card-header { font-size: 0.9rem; }
      .badge { padding: 5px 10px; font-size: 0.78rem; }
  }

  @media (max-width: 575px) {
      form .row > [class*="col-"] { margin-bottom: 10px; }
      .card { border-radius: 10px; }
      thead tr { display: table-row; }
  }
</style>

<div class="container-fluid py-4">
    <h2 class="h4 mb-3">Log de Notificações - FisioVida</h2>

    <!-- FILTROS -->
    <form method="get" class="card p-3 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5 col-12">
                <label class="form-label">Buscar por nome</label>
                <input type="text" name="destinatario" class="form-control"
                       placeholder="Nome do remetente ou destinatário"
                       value="<?= htmlspecialchars($destinatario, ENT_QUOTES) ?>">
            </div>

            <div class="col-md-3 col-6">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach($tipos as $t): ?>
                        <option value="<?= htmlspecialchars($t, ENT_QUOTES) ?>" <?= ($t == $tipo) ? 'selected' : '' ?>>
                            <?= htmlspecialchars(ucfirst($t)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 col-6">
                <label class="form-label">Lida</label>
                <select name="lida" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" <?= $lida === '1' ? 'selected' : '' ?>>Lida</option>
                    <option value="0" <?= $lida === '0' ? 'selected' : '' ?>>Não</option>
                </select>
            </div>

            <div class="col-md-2 col-12 d-grid">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- TABELA -->
    <div class="card">
        <div class="card-header">Notificações Registradas</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless m-0">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Destinatário</th>
                            <th>Remetente</th>
                            <th>Mensagem</th>
                            <th style="width:150px">Tipo</th>
                            <th style="width:140px">Data</th>
                            <th style="width:110px">Lida</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($notificacoes)): ?>
                            <?php foreach ($notificacoes as $n): ?>
                                <tr>
                                    <td><?= (int)$n['id'] ?></td>
                                    <td><?= htmlspecialchars($n['destinatario_nome'] ?: 'Todos', ENT_QUOTES) ?></td>
                                    <td><?= htmlspecialchars($n['remetente_nome'] ?? '', ENT_QUOTES) ?></td>
                                    <td style="text-align:left; max-width:380px; white-space:normal; word-break:break-word;">
                                        <?= nl2br(htmlspecialchars($n['mensagem'] ?? '', ENT_QUOTES)) ?>
                                    </td>
                                    <td><?= badgeTipo($n['tipo'] ?? '') ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($n['data_envio'] ?? '')), ENT_QUOTES) ?></td>
                                    <td><?= badgeLida($n['lida'] ?? 0) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="no-results">Nenhuma notificação encontrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div> <!-- .table-responsive -->
        </div> <!-- .card-body -->
    </div> <!-- .card -->

</div> <!-- .container-fluid -->

<?php include 'partials/footer.php'; ?>
