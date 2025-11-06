<?php
// partials/header.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../site/login.php');
    exit;
}

// Adaptação para o banco "fisiovida"
$userName = $_SESSION['nome'] ?? null;           
$userRole = $_SESSION['tipo_usuario'] ?? null;
$idUsuario = $_SESSION['usuario_id'];
$fotoPerfil = $_SESSION['foto_perfil'] ?? ($usuario['foto'] ?? '../img/imagem_perfil.JPEG');

// Consulta os dados do usuário
$stmt = $pdo->prepare("SELECT nome, email, cpf, data_nasc, telefone, cep, sexo, tipo_usuario
                       FROM usuario WHERE id = ?");
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FisioVida - Fisioterapeuta</title>
  <link rel="icon" href="../img/Icone fisiovida.jfif">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style> 
#contadorNotificacoes {
  font-size: 0.7rem;
  padding: 3px 6px;
}

#listaNotificacoes li {
  list-style: none;
  border-bottom: 1px solid #eee;
  padding: 8px 12px;
}

#listaNotificacoes li:last-child {
  border-bottom: none;
}

#listaNotificacoes .nao-lida {
  background-color: #e9f5ff;
  font-weight: 600;
}

  body {
    background-color: whitesmoke;
    min-height: 100vh;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-size: cover;
  }

  /* Sidebar lateral moderna */
  #sidebar {
    width: 230px;
    background: #0b8ecb; /* azul escuro elegante */
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
    z-index: 1000;
  }

  #sidebar .nav-link {
    width: 85%;
    color: #e6e6e6;
    padding: 12px 15px;
    margin: 6px 0;
    border-radius: 10px;
    font-weight: normal;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
  }

  #sidebar .nav-link i {
    font-size: 1.2rem;
  }

  #sidebar .nav-link:hover {
    background-color: #0078ff;
    color: #fff;
    transform: translateX(4px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
    text-decoration: none;
  }

  #sidebar .nav-link.active {
    background-color: #0078ff;
    color: #fff;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
  }

  /* Conteúdo principal */
  #main-content {
    margin-left: 230px;
    padding: 25px;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
  }

  /* Navbar superior */
  .navbar {
    z-index: 1100;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }

  /* Responsividade */
  @media (max-width: 768px) {
    #sidebar {
      position: relative;
      width: 100%;
      height: auto;
      flex-direction: row;
      justify-content: space-around;
      padding-top: 10px;
    }

    #sidebar .nav-link {
      flex: 1;
      text-align: center;
      margin: 0 5px;
      border-radius: 6px;
    }

    #main-content {
      margin-left: 0;
    }
  }

  #sidebar .nav-link:active,
  #sidebar .nav-link:focus,
  #sidebar .nav-link:visited {
    font-weight: normal;
  }

 /* Modal */
  .profile-modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.45);
  }

  .profile-content {
    background-color: #fff;
    margin: 4% auto;
    padding: 35px 45px;
    border-radius: 18px;
    width: 100vh;
    max-width: 90%;
    box-shadow: 0 5px 25px rgba(0,0,0,0.3);
    animation: fadeIn 0.3s ease;
  }

  .profile-content p {
    margin-bottom: 10px;
    color: #333;
    font-size: 15px;
  }

  .profile-content strong {
    color: #004b87;
  }

  .close-btn {
    float: right;
    font-size: 24px;
    cursor: pointer;
    color: #777;
  }

  .close-btn:hover { color: #000; }

  .profile-photo {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .profile-photo img {
    border-radius: 50%;
    border: 3px solid #0b8ecb;
    width: 120px;
    height: 120px;
    object-fit: cover;
    margin-bottom: 10px;
  }

  label[for="novaFoto"] {
    cursor: pointer;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
</style>

</head>

<body>

<!-- Navbar superior -->
<nav class="navbar navbar-expand-md bg-light sticky-top">
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
          <a class="btn btn-outline-danger btn-sm" href="../php/logout.php">Sair</a>
        </li>

        <!-- Ícone de perfil com a foto do usuário -->
        <li class="nav-item">
          <button class="btn p-0 border-0 bg-transparent" id="profileBtn" title="Perfil">
            <img 
              src="<?php echo htmlspecialchars($_SESSION['foto_perfil'] ?? ($usuario['foto'] ?? '../img/imagem_perfil.JPEG')); ?>" 
              alt="Foto de perfil" 
              class="rounded-circle border border-secondary" 
              width="38" 
              height="38" 
              style="object-fit: cover;"
            >
          </button>
        </li>
        <!-- Ícone de notificações -->
<li class="nav-item dropdown me-3">
  <a class="btn position-relative" href="#" id="notificacoesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-bell fs-5"></i>
    <span id="contadorNotificacoes" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
      0
    </span>
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

<!-- Modal de Perfil -->
<div id="profileModal" class="profile-modal">
  <div class="profile-content">
    <span class="close-btn">&times;</span>
    <h5 class="mb-3 text-center">Perfil do Usuário</h5>

<!-- Foto de perfil -->
<div class="text-center mb-3">
  <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de perfil" class="rounded-circle shadow-sm" width="300" height="300" id="userPhoto">

  <div class="mt-2 d-flex justify-content-center gap-2">
    <!-- Botão Alterar Foto -->
    <form id="formFoto" action="upload_foto.php" method="post" enctype="multipart/form-data">
      <label for="novaFoto" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-camera"></i> Alterar foto
      </label>
      <input type="file" name="novaFoto" id="novaFoto" accept="image/*" style="display:none">
    </form>

    <!-- Botão Remover Foto -->
    <button type="button" id="removerFotoBtn" class="btn btn-sm btn-outline-danger">
      <i class="bi bi-x-circle"></i> Remover foto
    </button>
  </div>
</div>

    <hr>

    <div class="user-info">
      <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
      <p><strong>CPF:</strong> <?= htmlspecialchars($usuario['cpf']) ?></p>
      <p><strong>Data de Nascimento:</strong> <?= date('d/m/Y', strtotime($usuario['data_nasc'])) ?></p>
      <p><strong>Telefone:</strong> <?= htmlspecialchars($usuario['telefone']) ?></p>
      <p><strong>CEP:</strong> <?= htmlspecialchars($usuario['cep']) ?></p>
      <p><strong>Sexo:</strong> <?= htmlspecialchars($usuario['sexo']) ?></p>
      <p><strong>Tipo de Usuário:</strong> 
        <span class="badge bg-primary"><?= ucfirst($usuario['tipo_usuario']) ?></span>
      </p>
    </div>
  </div>
</div>

<!-- Sidebar lateral -->
<div id="sidebar">
  <a href="fisio_dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'fisio_dashboard.php') ? 'active' : ''; ?>"><i class="bi bi-house-door"></i> Início</a>
  <a href="prontuario.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'prontuario.php') ? 'active' : ''; ?>"><i class="bi bi-briefcase"></i> Prontuários</a>
  <a href="agenda.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'agenda.php') ? 'active' : ''; ?>"><i class="bi bi-calendar3"></i> Agendamentos</a>
  <a href="relatorio.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'relatorio.php') ? 'active' : ''; ?>"><i class="bi bi-bar-chart"></i> Relatórios</a>

</div>

<!-- Modal de Pefil -->

<script>
// Função para buscar notificações
function carregarNotificacoes() {
  fetch('../php/buscar_notificacao.php')
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
        li.innerHTML = `
          <div>${n.mensagem}</div>
          <small class="text-muted">${new Date(n.data_envio).toLocaleString('pt-BR')}</small>
        `;
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
  fetch('../php/marcar_lida.php')
    .then(res => res.json())
    .then(() => carregarNotificacoes());
});

// Atualiza notificações a cada 30 segundos
setInterval(carregarNotificacoes, 30000);
carregarNotificacoes();
</script>


<script>
const removerFotoBtn = document.getElementById('removerFotoBtn');
const novaFoto = document.getElementById('novaFoto');
const userPhoto = document.getElementById('userPhoto');
const profileBtnPhoto = document.querySelector('#profileBtn img');
const FOTO_PADRAO = '../img/imagem_perfil.JPEG';

novaFoto.addEventListener('change', (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('novaFoto', file);

  // Mostra preview imediato
  const reader = new FileReader();
  reader.onload = e => {
    userPhoto.src = e.target.result;
    profileBtnPhoto.src = e.target.result;
  };
  reader.readAsDataURL(file);

  // Envia via AJAX
  fetch('upload_foto.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    console.log('Upload concluído:', data);
  })
  .catch(err => {
    console.error('Erro no upload:', err);
    alert('Erro ao enviar a foto.');
  });
});

// Botão Remover Foto
removerFotoBtn.addEventListener('click', () => {
    // Atualiza a imagem no modal e no ícone
    userPhoto.src = FOTO_PADRAO;
    profileBtnPhoto.src = FOTO_PADRAO;

    // Envia requisição para o PHP remover a foto no banco
    fetch('remover_foto.php', {
        method: 'POST'
    })
    .then(response => response.text())
    .then(data => {
        console.log('Foto removida:', data);
        alert('Foto removida com sucesso!');
    })
    .catch(err => {
        console.error('Erro ao remover foto:', err);
        alert('Erro ao remover a foto.');
    });
});

// Abrir e fechar modal
const profileBtn = document.getElementById('profileBtn');
const modal = document.getElementById('profileModal');
const closeBtn = document.querySelector('.close-btn');

profileBtn.addEventListener('click', () => modal.style.display = 'block');
closeBtn.addEventListener('click', () => modal.style.display = 'none');
window.addEventListener('click', (e) => {
    if (e.target === modal) modal.style.display = 'none';
});

</script>

<!-- Conteúdo principal -->
<div id="main-content">
  <!-- Aqui vai todo o seu conteúdo -->
