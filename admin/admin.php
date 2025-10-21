<?php
// admin.php – adaptado para o banco "fisiovida"

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


require_once '../php/db.php'; // conexão PDO do fisiovida

// Parâmetros de busca/filtro
$q            = trim($_GET['q'] ?? '');
$tipoFiltro   = $_GET['tipo'] ?? '';
$page         = max(1, (int)($_GET['page'] ?? 1));
$perPage      = 8;

// WHERE dinâmico
$clauses = [];
$params  = [];

if ($q !== '') {
    $clauses[] = "(nome LIKE ? OR email LIKE ? OR cpf LIKE ?)";
    $like = "%$q%";
    $params[] = $like; 
    $params[] = $like; 
    $params[] = $like;
}

if (in_array($tipoFiltro, ['paciente', 'fisioterapeuta', 'admin'])) {
    $clauses[] = "tipo_usuario = ?";
    $params[]  = $tipoFiltro;
}

$whereSql = $clauses ? ('WHERE ' . implode(' AND ', $clauses)) : '';

// Total para paginação
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario $whereSql");
$stmt->execute($params);
$total  = (int)$stmt->fetchColumn();
$pages  = max(1, (int)ceil($total / $perPage));
$offset = ($page - 1) * $perPage;

// Busca usuários
$sql = "SELECT id, nome, email, tipo_usuario, data_nasc
        FROM usuario
        $whereSql
        ORDER BY id DESC
        LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll();

include __DIR__ . '/partials/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100vh;
    background-color: rgb(255, 255, 255);
    height: 100vh;
    font-family: roboto;

    background: linear-gradient(135deg, 
    #ffffff 0%,    
    #9df7c2 50%,    
    #acb7f7 100%   
  );
  min-height: 100vh;
  margin: 0;

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
  <form method="get" class="card card-body shadow-sm mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label">Buscar</label>
        <input type="text" name="q" class="form-control" value="<?= htmlspecialchars($q) ?>" placeholder="Nome, e-mail ou CPF">
      </div>
      <div class="col-md-3">
        <label class="form-label">Tipo de Usuário</label>
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
    <div class="card-header">Usuários cadastrados (<?= $total ?>)</div>
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
                  <form action="usuario_delete.php" method="post" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?');">
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
      
      <?php if ($pages > 1): ?>
        <nav class="mt-3">
          <ul class="pagination justify-content-center">
            <?php
      $baseQuery = $_GET;
      for ($i = 1; $i <= $pages; $i++):
        $baseQuery['page'] = $i;
        $href = 'admin.php?' . http_build_query($baseQuery);
        ?>
      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
        <a class="page-link" href="<?= htmlspecialchars($href) ?>"><?= $i ?></a>
      </li>
      <?php endfor; ?>
    </ul>
  </nav>
  <?php endif; ?>
  
  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
  