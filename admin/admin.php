<?php
require_once '../php/db.php';

// Parâmetros de filtro e paginação

$qUsuario     = trim($_GET['q_usuario'] ?? '');
$tipoFiltro   = $_GET['tipo'] ?? '';
$tipoServico  = $_GET['tiposervico'] ?? ''; 
$qServico     = trim($_GET['q_servico'] ?? '');
$page         = max(1, (int)($_GET['page'] ?? 1));
$perPage      = 8;
$qAgenda      = trim($_GET['q_agenda'] ?? '');

// ==== Filtro Usuarios ====

$userClauses = [];
$userParams  = [];

if ($qUsuario !== '') {
    $userClauses[] = "(nome LIKE ? OR email LIKE ? OR cpf LIKE ?)";
    $like = "%$qUsuario%";
    $userParams[] = $like;
    $userParams[] = $like;
    $userParams[] = $like;
}

if (in_array($tipoFiltro, ['paciente', 'fisioterapeuta', 'admin'])) {
    $userClauses[] = "tipo_usuario = ?";
    $userParams[]  = $tipoFiltro;
}

$userWhereSql = $userClauses ? ('WHERE ' . implode(' AND ', $userClauses)) : '';

// ===== Paginação de usuários =====

$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario $userWhereSql");
$stmt->execute($userParams);
$totalUsuarios = (int)$stmt->fetchColumn();
$pagesUsuarios = max(1, (int)ceil($totalUsuarios / $perPage));
$offsetUsuarios = ($page - 1) * $perPage;

// ===== Consulta de usuários =====

$sqlUsuarios = "SELECT id, nome, email, tipo_usuario, data_nasc
                FROM usuario
                $userWhereSql
                ORDER BY id DESC
                LIMIT $perPage OFFSET $offsetUsuarios";
$stmt = $pdo->prepare($sqlUsuarios);
$stmt->execute($userParams);
$usuarios = $stmt->fetchAll();

// ==== Filtro Serviços ====

$servClauses = [];
$servParams  = [];

if ($qServico !== '') {
    $servClauses[] = "(nome_servico LIKE ? OR descricao_servico LIKE ?)";
    $like = "%$qServico%";
    $servParams[] = $like;
    $servParams[] = $like;
}

if (in_array($tipoServico, ['Ativo', 'Inativo'])) {
    $servClauses[] = "status = ?";
    $servParams[]  = $tipoServico;
}

$servWhereSql = $servClauses ? ('WHERE ' . implode(' AND ', $servClauses)) : '';

// ===== Paginação de serviços =====

$stmt = $pdo->prepare("SELECT COUNT(*) FROM servico $servWhereSql");
$stmt->execute($servParams);
$totalServicos = (int)$stmt->fetchColumn();
$pagesServicos = max(1, (int)ceil($totalServicos / $perPage));
$offsetServicos = ($page - 1) * $perPage;

// ===== Consulta de serviços =====

$sqlServicos = "SELECT id_servico, nome_servico, descricao_servico, status
                FROM servico
                $servWhereSql
                ORDER BY id_servico DESC
                LIMIT $perPage OFFSET $offsetServicos";
$stmt = $pdo->prepare($sqlServicos);
$stmt->execute($servParams);
$servicos = $stmt->fetchAll();

// ===== Paginação de agenda =====

$agendaClauses = [];
$agendaParams  = [];

// Filtro da agenda

if ($qAgenda !== '') {
    $agendaClauses[] = "(nome_paciente LIKE ? OR data LIKE ? OR descricao_servico LIKE ? )";
    $like = "%$qAgenda%";
    $agendaParams[] = $like;
    $agendaParams[] = $like;
    $agendaParams[] = $like;
}

$agendaWhereSql = $agendaClauses ? ('WHERE ' . implode(' AND ', $agendaClauses)) : '';

// Contagem de agendamentos
$stmt = $pdo->prepare("SELECT COUNT(*) FROM agenda $agendaWhereSql");
$stmt->execute($agendaParams);
$totalagendamentos = (int)$stmt->fetchColumn();
$pagesagendamentos = max(1, (int)ceil($totalagendamentos / $perPage));
$offsetagendamentos = ($page - 1) * $perPage;

// Consulta de agendamentos
$sqlagendamentos = "SELECT id_Agenda, nome_paciente, data, data_agendamento, hora, descricao_servico
                    FROM agenda
                    $agendaWhereSql
                    ORDER BY id_Agenda DESC
                    LIMIT $perPage OFFSET $offsetagendamentos";
$stmt = $pdo->prepare($sqlagendamentos);
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
    body {
      margin: 0; padding: 0;
      width: 100%; height: 100vh;
      font-family: roboto;
      background: linear-gradient(135deg, #ffffff 0%, #9df7c2 50%, #acb7f7 100%);
      background-attachment: fixed;
      background-size: cover;
    }
  </style>
</head>
<body>
  
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0">Painel Administrativo - FisioVida</h2>
    <span class="badge text-bg-primary">Perfil: Admin</span>
  </div>

  <!-- Filtro de serviços -->

  <form method="get" class="card card-body shadow-sm mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label">Buscar Serviço</label>
        <input type="text" name="q_servico" class="form-control" value="<?= htmlspecialchars($qServico) ?>" placeholder="Nome ou descrição do serviço">
      </div>
      <div class="col-md-2">
        <label class="form-label">Tipo</label>
        <select name="tiposervico" class="form-select">
        <option value="">Todos</option>
        <option value="Ativo" <?= $tipoServico==='Ativo' ? 'selected' : '' ?>>Ativo</option>
        <option value="Inativo" <?= $tipoServico==='Inativo' ? 'selected' : '' ?>>Inativo</option>
        </select>
      </div>
      <div class="col-md-4 text-end">
        <a class="btn btn-outline-secondary" href="admin.php">Limpar</a>
        <button class="btn btn-primary">Filtrar</button>
        <a class="btn btn-success" href="servico_create.php">+ Novo Serviço</a>
      </div>
    </div>
  </form>

  <div class="card shadow-sm mb-4">
    <div class="card-header">Serviços encontrados (<?= $totalServicos ?>)</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0 align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
              <th>Descrição</th>
              <th>Status</th>
              <th class="text-end">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($servicos as $s): ?>
              <tr>
                <td><?= (int)$s['id_servico'] ?></td>
                <td><?= htmlspecialchars($s['nome_servico']) ?></td>
                <td><?= htmlspecialchars($s['descricao_servico']) ?></td>
                <td>
                  <span class="badge text-bg-<?= $s['status']==='Inativo' ? 'danger' : ($s['status']==='Ativo' ? 'info' : 'secondary') ?>">
                    <?= htmlspecialchars(ucfirst($s['status'])) ?>
                  </span>
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-secondary" href="servico_edit.php?id=<?= (int)$s['id_servico'] ?>">Editar</a>
                  <form action="servico_delete.php" method="post" class="d-inline" onsubmit="return confirm('Deseja excluir este serviço?');">
                    <input type="hidden" name="id" value="<?= (int)$s['id_servico'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$servicos): ?>
              <tr><td colspan="5" class="text-center text-muted py-4">Nenhum serviço encontrado.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Filtro de Agenda -->

    <form method="get" class="card card-body shadow-sm mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label">Buscar Agendamento</label>
        <input type="text" name="q_agenda" class="form-control" value="<?= htmlspecialchars($qAgenda) ?>" placeholder="Nome do paciente, data ou tipo de serviço">
      </div>
        <div class="col-md-6 text-end">
        <a class="btn btn-outline-secondary" href="admin.php">Limpar</a>
        <button class="btn btn-primary">Filtrar</button>
      </div>
    </div>
  </form>

  <div class="card shadow-sm mb-4">
    <div class="card-header">agendamentos encontrados (<?= $totalagendamentos ?>)</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0 align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome do Paciente</th>
              <th>Data da Consulta</th>
              <th>Data do agendamento</th>
              <th>Hora da Consulta</th>
              <th>Tipo do Serviço</th>
              <th class="text-end">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($agenda as $a): ?>
              <tr>
                <td><?= (int)$a['id_Agenda'] ?></td>
                <td><?= htmlspecialchars($a['nome_paciente']) ?></td>
                <td><?= htmlspecialchars($a['data']) ?></td>
                <td><?= htmlspecialchars($a['data_agendamento']) ?></td>
                <td><?= htmlspecialchars($a['hora']) ?></td>
                <td><?= htmlspecialchars($a['descricao_servico']) ?></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-secondary" href="agenda_edit.php?id=<?= (int)$a['id_Agenda'] ?>">Editar</a>
                  <form action="agenda_delete.php" method="post" class="d-inline" onsubmit="return confirm('Deseja excluir este serviço?');">
                    <input type="hidden" name="id" value="<?= (int)$a['id_Agenda'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$agenda): ?>
              <tr><td colspan="5" class="text-center text-muted py-4">Nenhum serviço encontrado.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Filtro de usuários -->

  <form method="get" class="card card-body shadow-sm mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label">Buscar Usuário</label>
        <input type="text" name="q_usuario" class="form-control" value="<?= htmlspecialchars($qUsuario) ?>" placeholder="Nome, email ou CPF">
      </div>
      <div class="col-md-3">
        <label class="form-label">Tipo</label>
        <select name="tipo" class="form-select">
          <option value="">Todos</option>
          <option value="paciente" <?= $tipoFiltro==='paciente' ? 'selected' : '' ?>>Paciente</option>
          <option value="fisioterapeuta" <?= $tipoFiltro==='fisioterapeuta' ? 'selected' : '' ?>>Fisioterapeuta</option>
          <option value="admin" <?= $tipoFiltro==='admin' ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>
      <div class="col-md-3 text-end">
        <a class="btn btn-outline-secondary" href="admin.php">Limpar</a>
        <button class="btn btn-primary">Filtrar</button>
        <a class="btn btn-success" href="usuario_create.php">+ Novo Usuário</a>
      </div>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="card-header">Usuários cadastrados (<?= $totalUsuarios ?>)</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0 align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Tipo</th>
              <th>Data de Nascimento</th>
              <th class="text-end">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $u): ?>
              <tr>
                <td><?= (int)$u['id'] ?></td>
                <td><?= htmlspecialchars($u['nome']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                  <span class="badge text-bg-<?= $u['tipo_usuario']==='admin' ? 'danger' : ($u['tipo_usuario']==='fisioterapeuta' ? 'info' : 'secondary') ?>">
                    <?= htmlspecialchars(ucfirst($u['tipo_usuario'])) ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($u['data_nasc']) ?></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-secondary" href="usuario_edit.php?id=<?= (int)$u['id'] ?>">Editar</a>
                  <form action="usuario_delete.php" method="post" class="d-inline" onsubmit="return confirm('Deseja excluir este usuário?');">
                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$usuarios): ?>
              <tr><td colspan="6" class="text-center text-muted py-4">Nenhum usuário encontrado.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
