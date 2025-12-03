<?php
require_once '../php/db.php';

// Parâmetros de filtro e paginação
$qUsuario     = trim($_GET['q_usuario'] ?? '');
$tipoFiltro   = $_GET['tipo'] ?? '';
$page         = max(1, (int)($_GET['page'] ?? 1));
$perPage      = 8;

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

include __DIR__ . '/partials/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuários FisioVida</title>
  <style>
    
    .container-filtro {
      background: #fff;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    .btn-filtro {
      border: none;
      color: #fff;
      padding: 10px 20px;
      margin: 5px;
      border-radius: 25px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-filtro:hover {
      transform: scale(1.05);
      opacity: 0.9;
    }

    .btn-todos { background-color: #6c757d; }
    .btn-admin { background-color: #dc3545; }
    .btn-paciente { background-color: #198754; }
    .btn-fisio { background-color: #0dcaf0; }
    .btn-novo { background-color: #0d6efd; }
    .btn-novofisio { background-color: #08306dff; }

    .table thead {
      background-color: #f8f9fa;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      overflow: hidden;
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
    .btn-filtro,
.btn-filtro:hover,
.btn-filtro:focus,
.btn-filtro:active {
  text-decoration: none; /* remove o sublinhado em todos os estados */
}
a {
  text-decoration: none;
}

/* Container dos botões vira flex e quebra linha */
.container-filtro div {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px; /* espaço entre botões */
}

/* Botões ajustam o tamanho automaticamente */
.btn-filtro {
  flex: 1 1 auto;
  min-width: 140px; /* largura mínima para não ficarem pequenos demais */
  text-align: center;
}

/* Em telas muito pequenas */
@media (max-width: 480px) {
  .btn-filtro {
    width: 100%;        /* cada botão ocupa toda a linha */
    min-width: unset;   /* remove largura mínima */
  }
}

  </style>
</head>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0" data-aos="fade-right">Painel de Úsuarios - FisioVida</h2>
    <span class="badge text-bg-primary" data-aos="fade-left">Perfil: Adiministrador</span>
  </div>
<div class="mt-5">

  <!-- Filtro com botões -->
  <div class="container-filtro text-center" data-aos="zoom-in">
    <h4 class="mb-3">Filtrar Usuários</h4>
    <div >
      <a href="?tipo=" class="btn-filtro btn-todos" data-aos="fade" data-aos-delay="500" <?= $tipoFiltro==='' ? 'shadow' : '' ?>">Todos</a>
      <a href="?tipo=admin" class="btn-filtro btn-admin" data-aos="fade" data-aos-delay="600" <?= $tipoFiltro==='admin' ? 'shadow' : '' ?>">Administradores</a>
      <a href="?tipo=paciente" class="btn-filtro btn-paciente" data-aos="fade" data-aos-delay="700" <?= $tipoFiltro==='paciente' ? 'shadow' : '' ?>">Pacientes</a>
      <a href="?tipo=fisioterapeuta" class="btn-filtro btn-fisio" data-aos="fade" data-aos-delay="800" <?= $tipoFiltro==='fisioterapeuta' ? 'shadow' : '' ?>">Fisioterapeutas</a>
      <a href="usuario_create.php" class="btn-filtro btn-novo" data-aos="fade" data-aos-delay="900">+ Novo Usuário</a>
      <a href="fisio_create.php" class="btn-filtro btn-novofisio" data-aos="fade" data-aos-delay="1000">+ Novo Fisioterapeuta</a>
    </div>
  </div>

  <!-- Campo de busca -->
  <form method="get" class="mb-3 text-center" data-aos="zoom-in">
    <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipoFiltro) ?>">
    <input type="text" name="q_usuario" class="form-control d-inline-block w-50" 
           placeholder="Buscar por nome, e-mail ou CPF"
           value="<?= htmlspecialchars($qUsuario) ?>">
    <button class="btn btn-primary ms-2">Buscar</button>
  </form>

  <!-- Tabela de usuários -->
  <div class="card">
    <div class="card-header">
      Usuários cadastrados (<?= $totalUsuarios ?>)
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0 align-middle">
          <thead data-aos="fade-down">
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
              <tr data-aos="fade-up" data-aos-delay="100">
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

</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
