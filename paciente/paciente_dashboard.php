<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'paciente') {
    header("Location: ../site/paciente_dashboard.php");
    exit();
}

require_once '../php/db.php'; // Certifique-se que $pdo é PDO

$usuarioId = $_SESSION['usuario_id'];
$nomePaciente = $_SESSION['usuario_nome'];

try {
    // Buscar id_paciente via CPF do usuário
    $stmt = $pdo->prepare("
        SELECT p.id_paciente
        FROM paciente p
        INNER JOIN usuario u ON p.cpf = u.cpf
        WHERE u.id = ?
        LIMIT 1
    ");
    $stmt->execute([$usuarioId]);
    $id_paciente = $stmt->fetchColumn(); // Retorna apenas a primeira coluna (id_paciente)

    if (!$id_paciente) {
        throw new Exception("Paciente não encontrado para o usuário informado.");
    }

} catch (PDOException $e) {
    die("Erro ao buscar ID do paciente: " . $e->getMessage());
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <title>FisioVida - Paciente</title>
  <link rel="icon" href="../img/Icone fisiovida.jfif">
</head>
<body>

<!-- Barra superior -->
<div class="container-fluid bg-light ps-1 pe-0 d-none d-lg-block">
  <div class="row gx-0">
    <div class="col-md-6 text-center text-lg-start mb-0">
      <div class="d-inline-flex align-items-center">
        <small class="py-2"><i class="bi bi-clock container"></i> Aberto de Segunda a Sexta, das 08:00 às 18:00.</small>
      </div>
    </div>
    <div class="col-md-6 text-center text-lg-end">
      <div class="position-relative d-inline-flex align-items-center bg-success text-white top-shape px-4">
        <div class="me-3 pe-3 border-end py-2">
          <p class="m-0"><i class="bi bi-envelope-at me-2"></i>fisiovidarmnf@gmail.com</p>
        </div>
        <div class="py-2">
          <p class="m-0"><i class="bi bi-telephone me-2"></i>+012 345 6789</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-light sticky-xxl-top">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a href="#" class="navbar-brand">
      <img src="../img/Fisiovida logo.png" alt="Fisiovida Logo" width="120" height="90">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menunavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="menunavbar">
      <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
        <li class="nav-item"><a href="#home" class="nav-link">HOME</a></li>
        <li class="nav-item"><a href="#serviços" class="nav-link">SERVIÇOS</a></li>
        <li class="nav-item"><a href="#agendar" class="nav-link">AGENDAR</a></li>
        <li class="nav-item"><a href="#meusagendamentos" class="nav-link">MEUS AGENDAMENTOS</a></li>
        <li class="nav-item"><a href="../php/logout.php" class="nav-link"><button type="button" class="btn btn-outline-danger">Sair</button></a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Seção HOME -->
<section id="home" class="py-5 bg-light text-center">
  <div class="container">
    <h1 class="display-5 fw-bold">Bem-vindo à Fisiovida, <?= htmlspecialchars($nomePaciente); ?>!</h1>
    <p class="lead mt-3">Cuidando de você com excelência, humanidade e tecnologia.</p>
  </div>
</section>

<!-- Seção SERVIÇOS -->
<section id="serviços" class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">Nossos Serviços</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php
      // Consulta usando PDO
      $stmt = $pdo->query("SELECT nome_servico, descricao_servico FROM servico WHERE status = 'Ativo'");
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
      ?>
      <div class="col">
        <div class="card h-100 shadow">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['nome_servico']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($row['descricao_servico']) ?></p>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- Seção AGENDAR -->
<section id="agendar" class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-4">Agendar Consulta</h2>
    <form class="row g-3" action="processar_agendamento.php" method="POST">
      <input type="hidden" name="usuario_id" value="<?= $usuarioId ?>">
      <div class="col-md-6">
        <label class="form-label">Nome</label>
        <input type="text" class="form-control" name="nome_paciente" value="<?= htmlspecialchars($nomePaciente); ?>" readonly>
      </div>
      <div class="col-md-6">
        <label class="form-label">Serviço</label>
        <select class="form-select" name="servico_id" required>
          <option disabled selected>Escolha...</option>

          <?php
          // Consulta usando PDO
          $stmt = $pdo->query("SELECT id_servico, nome_servico FROM servico WHERE status = 'Ativo'");
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo '<option value="' . $row['id_servico'] . '">' . htmlspecialchars($row['nome_servico']) . '</option>';
          }
          ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Data</label>
        <input type="date" class="form-control" name="data" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Horário</label>
        <input type="time" class="form-control" name="hora" required>
      </div>
      <div class="col-12 text-center mt-4">
        <button class="btn btn-success" type="submit">Agendar</button>
      </div>
    </form>
  </div>
</section>


<!-- Seção MEUS AGENDAMENTOS -->
<section id="meusagendamentos" class="py-5">
  <div class="container">
    <h2 class="text-center mb-4">Meus Agendamentos</h2>
    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center">
        <thead class="table-secondary">
          <tr>
            <th>Data</th>
            <th>Hora</th>
            <th>Serviço</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Consulta usando PDO corrigida
          $stmt = $pdo->prepare("
            SELECT a.data, a.hora, s.descricao_servico, a.status
            FROM agenda a
            INNER JOIN servico s ON a.servico_id_servico = s.id_servico
            WHERE a.paciente_id_paciente = ?
            ORDER BY a.data ASC, a.hora ASC
          ");
          $stmt->execute([$id_paciente]);
          $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if (count($agendamentos) > 0):
              foreach ($agendamentos as $row):
          ?>
          <tr>
            <td><?= date('d/m/Y', strtotime($row['data'])) ?></td>
            <td><?= substr($row['hora'], 0, 5) ?></td>
            <td><?= htmlspecialchars($row['descricao_servico']) ?></td>
            <td>
          <?php
            $status = htmlspecialchars($row['status']);
            switch ($status) {
              case 'confirmado':
                echo '<span class="badge bg-success">Confirmado</span>';
                break;
              case 'remarcado':
                echo '<span class="badge bg-info text-dark">Remarcado</span>';
                break;
              case 'recusado':
                echo '<span class="badge bg-danger">Recusado</span>';
                break;
              default:
                echo '<span class="badge bg-warning text-dark">Pendente</span>';
                break;
            }
          ?>
        </td>
          </tr>
          <?php 
              endforeach; 
          else: ?>
          <tr><td colspan="4">Nenhum agendamento encontrado.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>



<!-- Rodapé -->
<footer class="bg-light text-center text-lg-start mt-auto">
  <div class="container p-4">
    <div class="row">
      <div class="col-lg-3 col-md-12 mb-4">
        <h5 class="text-uppercase">Fisiovida</h5>
        <p>
          Endereço: Rua Exemplo, 123 - Cidade, Estado<br>
          Telefone: +55 12 3456-7890<br>
          Email: contato@fisiovida.com.br
        </p>
      </div>
      <div class="col-lg-3 col-md-6 mb-4">
        <h5 class="text-uppercase">Redes sociais</h5>
        <ul class="list-unstyled d-flex justify-content-start gap-3 mt-3">
          <li><a href="#!" class="text-dark"><i class="bi bi-facebook fs-4"></i></a></li>
          <li><a href="#!" class="text-dark"><i class="bi bi-instagram fs-4"></i></a></li>
          <li><a href="#!" class="text-dark"><i class="bi bi-twitter fs-4"></i></a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-12 mb-4">
        <h5 class="text-uppercase">Slogan</h5>
        <p>
          Conectamos você a fisioterapeutas qualificados, oferecendo praticidade no agendamento e cuidado humano em cada sessão.
        </p>
      </div>
    </div>
  </div>
  <div class="text-center p-3 bg-secondary text-white">
    © 2025 Fisiovida. Todos os direitos reservados.
  </div>
</footer>
</body>
</html>
