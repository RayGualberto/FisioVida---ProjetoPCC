<?php
// partials/header.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../site/login.php');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$fotoPerfil = $_SESSION['foto_perfil'] ?? '../img/imagem_perfil.JPEG';

try {
    // Buscar id do fisioterapeuta via CPF do usuário
    $stmt = $pdo->prepare("
        SELECT p.id_fisioterapeuta
        FROM fisioterapeuta p
        INNER JOIN usuario u ON p.cpf = u.cpf
        WHERE u.id = ?
        LIMIT 1
    ");
    $stmt->execute([$usuarioId]);
    $id_fisioterapeuta = $stmt->fetchColumn();

    if (!$id_fisioterapeuta) {
        throw new Exception("Fisioterapeuta não encontrado para o usuário informado.");
    }

    // Buscar dados do usuário
    $stmt = $pdo->prepare("
        SELECT nome, email, cpf, data_nasc, telefone, cep, sexo, tipo_usuario
        FROM usuario 
        WHERE id = ?
    ");
    $stmt->execute([$usuarioId]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro no banco de dados: " . $e->getMessage());
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FisioVida - Fisioterapeuta</title>
<link rel="icon" href="../img/Icone fisiovida.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
<link rel="stylesheet" href="../css/header.css">
</head>
<body>

<!-- Navbar superior -->
<nav class="navbar navbar-expand-md bg-light sticky-top py-3" data-aos="fade-down" data-aos-delay="150">
  <div class="container-fluid d-flex justify-content-between align-items-center" style="height: 55px;">
    <a href="fisio_dashboard.php" class="navbar-brand">
      <img src="../img/Fisiovida logo.png" alt="imagemfisiovida" width="120" height="78">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menunavbar" aria-controls="menunavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="menunavbar">
      <ul class="navbar-nav ms-auto d-flex align-items-center">
        <li class="nav-item me-2">
          <a class="btn btn-outline-danger btn-sm" href="../php/logout.php" data-aos="fade-left" data-aos-delay="300">Sair</a>
        </li>

        <!-- Ícone de perfil -->
        <li class="nav-item">
          <button class="btn p-0 border-0 bg-transparent" id="profileBtn" title="Perfil" data-aos="zoom-in" data-aos-delay="350">
            <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de perfil" class="rounded-circle border border-secondary" width="38" height="38" style="object-fit: cover;">
          </button>
        </li>

        <!-- Ícone de notificações -->
        <li class="nav-item dropdown me-3">
          <a class="btn position-relative" href="#" id="notificacoesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-aos="zoom-in" data-aos-delay="400">
            <i class="bi bi-bell fs-5"></i>
            <span id="contadorNotificacoes" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">0</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificacoesDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
            <li class="dropdown-header text-center fw-bold">Notificações</li>
            <div id="listaNotificacoes">
              <li class="text-center text-muted small py-2">Carregando...</li>
            </div>
            <li><hr class="dropdown-divider"></li>
            <li><button id="marcarLidas" class="dropdown-item text-center text-primary">Marcar todas como lidas</button></li>
          </ul>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- Sidebar lateral -->
<div id="sidebar" data-aos="fade-right" data-aos-delay="200">
  <a href="fisio_dashboard.php" class="nav-link" <?php echo (basename($_SERVER['PHP_SELF']) == 'fisio_dashboard.php') ? 'active' : ''; ?>><i class="bi bi-house-door"></i> Início</a>
  <a href="prontuario.php" class="nav-link" <?php echo (basename($_SERVER['PHP_SELF']) == 'prontuario.php') ? 'active' : ''; ?>><i class="bi bi-briefcase"></i> Prontuários</a>
  <a href="agenda.php" class="nav-link" <?php echo (basename($_SERVER['PHP_SELF']) == 'agenda.php') ? 'active' : ''; ?>><i class="bi bi-calendar3"></i> Agendamentos</a>
  <a href="relatorio.php" class="nav-link" <?php echo (basename($_SERVER['PHP_SELF']) == 'relatorio.php') ? 'active' : ''; ?>><i class="bi bi-bar-chart"></i> Relatórios</a>
</div>

<!-- Script notificações -->
<script>
// Função para buscar notificações
function carregarNotificacoes() {
  fetch('../php/buscar_notificacao_fisioterapeuta.php')
    .then(res => res.json())
    .then(data => {
      const lista = document.getElementById('listaNotificacoes');
      const contador = document.getElementById('contadorNotificacoes');
      lista.innerHTML = '';

      if (!data.notificacoes || data.notificacoes.length === 0) {
        lista.innerHTML = '<li class="text-center text-muted small py-2">Nenhuma notificação</li>';
        contador.classList.add('d-none');
        return;
      }

      data.notificacoes.forEach(n => {
        const li = document.createElement('li');
        li.classList.add('dropdown-item', 'small', n.lida == 0 ? 'nao-lida' : '');
        li.innerHTML = `<div>${n.mensagem}</div><small class="text-muted">${new Date(n.data_envio).toLocaleString('pt-BR')}</small>`;
        lista.appendChild(li);
      });

      if (data.total_nao_lidas > 0) {
        contador.textContent = data.total_nao_lidas;
        contador.classList.remove('d-none');
      } else {
        contador.classList.add('d-none');
      }
    })
    .catch(err => console.error('Erro ao buscar notificações:', err));
}

// Marcar todas como lidas
document.getElementById('marcarLidas').addEventListener('click', () => {
  fetch('../php/marcar_lida_fisioterapeuta.php')
    .then(res => res.json())
    .then(() => carregarNotificacoes());
});

// Atualiza notificações a cada 30 segundos
setInterval(carregarNotificacoes, 30000);
carregarNotificacoes();
</script>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
</script>

<!-- Conteúdo principal -->
<div id="main-content">
<!-- Aqui vai todo o conteúdo da página -->
