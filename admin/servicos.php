<?php
require_once '../php/db.php';
include __DIR__ . '../partials/header.php';

// Parâmetros de filtro e paginação
$tipoFiltro   = $_GET['tipo'] ?? '';
$tipoServico  = $_GET['tiposervico'] ?? ''; 
$qServico     = trim($_GET['q_servico'] ?? '');
$page         = max(1, (int)($_GET['page'] ?? 1));
$perPage      = 8;

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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Serviços FisioVida</title>
  <style>
    .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(8px);
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

    .table {
    margin-bottom: 0;
    max-height: 500px;
    overflow-y: auto;
    }

    .table thead {
      background: #cde9ff;
    }

    .table thead th {
      border-bottom: 2px solid #b4dcff;
      color: #333;
      text-align: center;
    }

    .table tbody tr {
      transition: all 0.3s ease;
    }

    .table tbody tr:hover {
      background-color: #e9f7f5;
      transform: scale(1.01);
    }
    .text-bg-info {
      background-color: #4cd3a5 !important;
      color: #fff !important;
    }

    .text-bg-danger {
      background-color: #ff6b6b !important;
      color: #fff !important;
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
      color: white;
    }

    form.card {
      border-radius: 15px;
      background: rgba(255, 255, 255, 0.9);
    }

    form label {
      font-weight: 500;
      color: #004b87;
    }

    input.form-control, select.form-select {
      border-radius: 10px;
      border: 1px solid #cce5ff;
      transition: 0.3s;
    }

    input.form-control:focus, select.form-select:focus {
      box-shadow: 0 0 5px rgba(0, 153, 255, 0.5);
      border-color: #0099ff;
    }

  </style>
</head>

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0">Painel de Serviços - FisioVida</h2>

    <span class="badge text-bg-primary">Perfil: Adiministrador</span>
  </div>
<div class=" mt-4">
  <!-- Filtro de serviços -->
<form method="get" class="card card-body shadow-sm mb-3">
  <div class="row g-2 align-items-end">
    <div class="col-md-5">
      <label class="form-label">🔎 Buscar Serviço</label>
      <input type="text" name="q_servico" class="form-control" value="<?= htmlspecialchars($qServico) ?>" placeholder="Nome ou descrição do serviço">
    </div>
    <div class="col-md-2">
      <label class="form-label">Status</label>
      <select name="tiposervico" class="form-select">
        <option value="">Todos</option>
        <option value="Ativo" <?= $tipoServico==='Ativo' ? 'selected' : '' ?>>Ativo</option>
        <option value="Inativo" <?= $tipoServico==='Inativo' ? 'selected' : '' ?>>Inativo</option>
      </select>
    </div>
    <div class="col-md-5 text-end">
      <a class="btn btn-success me-2" href="servico_create.php">+ Novo Serviço</a>
      <a class="btn btn-outline-secondary me-2" href="servicos.php">Limpar</a>
      <button class="btn btn-primary">Filtrar</button>
    </div>
  </div>
</form>


  <!-- Tabela de serviços -->
  <div class="card shadow-sm mb-4">
    <div class="card-header">Serviços encontrados (<?= $totalServicos ?>)</div>
    <div class="card-body p-0">
      <!-- Div com rolagem vertical -->
      <div class="table-responsive" style="max-height: 500px; overflow-y: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="sticky-top" style="background: #cde9ff; z-index: 10;">
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
                <td class="text-center"><?= (int)$s['id_servico'] ?></td>
                <td><?= htmlspecialchars($s['nome_servico']) ?></td>
                <td><?= htmlspecialchars($s['descricao_servico']) ?></td>
                <td class="text-center">
                  <span class="badge text-bg-<?= $s['status']==='Inativo' ? 'danger' : 'info' ?>">
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
</div>
  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
