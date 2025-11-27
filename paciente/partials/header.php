<?php
// partials/header.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../site/login.php');
    exit;
}

// obtendo o cpf do usuario logado
$sqlCpfUsuario = "SELECT cpf FROM usuario WHERE id = :usuario_id";
$stmt = $pdo->prepare($sqlCpfUsuario);
// Bind do parâmetro
$stmt->bindParam(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
// Executa a consulta
$stmt->execute();
// Obtém o valor do 'cpf' diretamente
$cpfUsuario = $stmt->fetchColumn();

// obtendo o cpf do usuario logado
$sqlIdUsuarioPaciente = "SELECT id_paciente FROM paciente WHERE cpf = :cpfUsuario";
$stmt = $pdo->prepare($sqlIdUsuarioPaciente);
// Bind do parâmetro
$stmt->bindParam(':cpfUsuario', $cpfUsuario, PDO::PARAM_STR);
// Executa a consulta
$stmt->execute();
// Obtém o valor do 'cpf' diretamente
$IdUsuarioPaciente = $stmt->fetchColumn();

$usuarioId = $_SESSION['usuario_id'];
$nomePaciente = $_SESSION['usuario_nome'];
$idUsuario = $_SESSION['usuario_id'];
$fotoPerfil = $_SESSION['foto_perfil'] ?? ($usuario['foto'] ?? '../img/imagem_perfil.JPEG');

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
  <title>FisioVida - Paciente</title>
  <link rel="icon" href="../img/Icone fisiovida.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
<style> 
           :root {
            --azul-base: #b3e5fc;
            /* pedido */
            --azul-escuro: #0288d1;
            --bg-soft: linear-gradient(135deg, #f5fbff, #eaf8ff);
            --glass: rgba(255, 255, 255, 0.72);
            --muted: #6b7280;
            }
        
            html,
            body {
            height: 100%;
            font-family: "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: var(--bg-soft);
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            }
        /* Top info bar */
        
        .top-info {
            background: #b3e5fc;
            background: var(--bg-soft);
            color: var(--muted);
            font-size: .92rem;
            position: relative;
            z-index: 2000;
        }
        
        .top-info .contact-pill {
            background: var(--azul-base);
            color: #0b3b56;
            border-radius: 8px;
            padding: .35rem .9rem;
            font-weight: 600;
        }
/* Navbar */
.navbar {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(6px);
    box-shadow: 0 6px 18px rgba(9, 30, 63, 0.06);
    position: relative;
    z-index: 2000;
}

.navbar .btn-outline-primary {
    border-color: transparent;
    background: transparent;
    color: var(--azul-escuro);
    border-radius: 30px;
}

.navbar .btn-outline-primary:hover {
    background: var(--azul-base);
    color: #033748;
}

/* Responsividade */
@media (max-width: 576px) {
    .navbar-brand img {
        width: 85px;
        height: auto;
    }

    nav .btn {
        font-size: 0.75rem;
        padding: 4px 10px;
    }

    #profileBtn img {
        width: 32px;
        height: 32px;
    }
}


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


  /* Sidebar lateral moderna */
  #sidebar {
    width: 230px;
    background: #0b8ecb;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
    z-index: 900;
    padding-top: 140px;
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
  @media (max-width: 768px) {

  #sidebar {
    position: relative;
    width: 100%;
    height: auto;
    padding: 10px;
    display: flex;
    flex-wrap: wrap;        /* permite quebrar linha */
    flex-direction: row;    /* botões ficam lado a lado */
    justify-content: center;
    gap: 8px;
  }

  #sidebar .nav-link {
    flex: 1;                /* cada botão ocupa o mesmo espaço */
    min-width: 120px;       /* evita ficar espremido demais */
    text-align: center;
    padding: 10px 5px;
    border-radius: 6px;
    font-size: 0.9rem;
  }

  #main-content {
    margin-left: 0 !important;
    padding-top: 15px;
  }
}


/* Modal */
.profile-modal {
  display: none;
  position: fixed;
  inset: 0;
  background-color: rgba(0,0,0,0.45);
  overflow-y: auto;

  padding-top: 80px;         /* 🔥 empurra o modal para baixo */
  padding-bottom: 40px;

  z-index: 9999 !important;
}

/* Modal de Perfil */
.profile-content {
  position: relative;
  z-index: 10000;
  
  background-color: #fff;
  margin: auto;
  padding: 35px 45px;
  border-radius: 18px;

  width: clamp(320px, 70vw, 900px);
  max-width: 95%;
  max-height: 90vh;
  overflow-y: auto;

  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
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
  @media (max-width: 480px) {
  .profile-content {
    padding: 25px 20px;
  }

  .profile-photo img {
    width: 140px;
    height: 140px;
  }
}
.dropdown-menu {
    z-index: 3000 !important;
}
li {
  list-style: none;
}

.nova-notificacao {
    animation: sinoBalanco 0.7s ease;
}

@keyframes sinoBalanco {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(-15deg); }
    50% { transform: rotate(15deg); }
    75% { transform: rotate(-10deg); }
    100% { transform: rotate(0deg); }
}

.badge-pulso {
    animation: pulso 1s ease-out;
}

@keyframes pulso {
    0% { transform: scale(1); }
    50% { transform: scale(1.4); }
    100% { transform: scale(1); }
}

</style>
</head>
<body>
   <!-- Top info bar -->
   <div class="container-fluid top-info py-2 d-none d-lg-block" data-aos="fade-down">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <small><i class="bi bi-clock me-2"></i>Aberto de Segunda a Sexta das 8:00 às 18:00</small>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <span class="contact-pill me-2"><i class="bi bi-envelope-at me-1"></i> fisiovidarmnf@gmail.com</span>
                    <span class="contact-pill"><i class="bi bi-telephone me-1"></i> +012 345 6789</span>
                </div>
            </div>
    </div>

<!-- Navbar superior -->
<nav class="navbar bg-light sticky-top py-2" data-aos="fade-down" data-aos-delay="150">
  <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center">

    <!-- LOGO -->
    <a href="paciente_dashboard.php" class="navbar-brand me-3">
      <img src="../img/Fisiovida logo.png" alt="Fisiovida" width="110" height="78" style="object-fit:contain;">
    </a>

    <!-- Botões e Ícones -->
    <div class="d-flex flex-wrap align-items-center gap-2">

      <!-- Botão Agendar -->
      <a class="btn btn-outline-primary btn-sm" href="Agendar.php">
        Agendar
      </a>

      <!-- Botão Sair -->
      <a class="btn btn-outline-danger btn-sm" href="../php/logout.php">
        Sair
      </a>

      <!-- Foto de Perfil -->
      <button class="btn p-0 border-0 bg-transparent" id="profileBtn" title="Perfil">
        <img 
          src="<?php echo htmlspecialchars($_SESSION['foto_perfil'] ?? ($usuario['foto'] ?? '../img/imagem_perfil.JPEG')); ?>" 
          alt="Foto de perfil" 
          class="rounded-circle border border-secondary" 
          width="38" height="38" 
          style="object-fit: cover;">
      </button>

<!-- Ícone de notificações -->
<li class="nav-item dropdown me-3">
  <a class="btn position-relative" href="#" id="notificacoesDropdown" role="button" 
     data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-bell fs-5"></i>
    <span id="contadorNotificacoes" 
          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">0</span>
  </a>

  <ul class="dropdown-menu dropdown-menu-end shadow"
      aria-labelledby="notificacoesDropdown"
      style="width: 320px; max-height: 400px; overflow-y: auto;">

    <li class="dropdown-header text-center fw-bold">Notificações</li>

    <!-- Lista correta -->
    <li>
      <ul id="listaNotificacoes" class="list-group list-group-flush">
        <li class="list-group-item text-center text-muted">Carregando...</li>
      </ul>
    </li>

    <li><hr class="dropdown-divider"></li>

    <li>
      <button id="marcarLidas" class="dropdown-item text-center text-primary">
        Marcar todas como lidas
      </button>
    </li>

  </ul>
</li>

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

<div id="userInfo">

  <div class="mb-2">
    <strong>Nome:</strong>
    <span class="view-mode"><?= htmlspecialchars($usuario['nome']) ?></span>
    <input class="form-control edit-mode d-none" id="editNome" value="<?= htmlspecialchars($usuario['nome']) ?>">
  </div>

  <div class="mb-2">
    <strong>Email:</strong>
    <span class="view-mode"><?= htmlspecialchars($usuario['email']) ?></span>
    <input class="form-control edit-mode d-none" id="editEmail" value="<?= htmlspecialchars($usuario['email']) ?>">
  </div>

  <div class="mb-2">
    <strong>Telefone:</strong>
    <span class="view-mode"><?= htmlspecialchars($usuario['telefone']) ?></span>
    <input class="form-control edit-mode d-none" id="editTelefone" value="<?= htmlspecialchars($usuario['telefone']) ?>">
  </div>

  <div class="mb-2">
    <strong>CEP:</strong>
    <span class="view-mode"><?= htmlspecialchars($usuario['cep']) ?></span>
    <input class="form-control edit-mode d-none" id="editCep" value="<?= htmlspecialchars($usuario['cep']) ?>">
  </div>

  <!-- Estes dados não podem ser editados -->
  <p><strong>CPF:</strong> <?= htmlspecialchars($usuario['cpf']) ?></p>
  <p><strong>Data de Nascimento:</strong> <?= date('d/m/Y', strtotime($usuario['data_nasc'])) ?></p>
  <p><strong>Sexo:</strong> <?= htmlspecialchars($usuario['sexo']) ?></p>
</div>

<hr>

<!-- Botões -->
<div class="text-center">
  <button class="btn btn-outline-primary" id="btnEditarPerfil">Editar Perfil</button>
  <button class="btn btn-outline-success d-none" id="btnSalvarPerfil">Salvar Alterações</button>
  <button class="btn btn-outline-secondary d-none" id="btnCancelarEdicao">Cancelar</button>
</div>

  </div>
</div>

<!-- Sidebar lateral -->
<div id="sidebar" data-aos="fade-right" data-aos-delay="200">

  <a href="paciente_dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'paciente_dashboard.php') ? 'active' : ''; ?>">
  <i class="bi bi-house-door"></i> Início</a>

  <a href="servicos.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'servicos.php') ? 'active' : ''; ?>">
  <i class="bi bi-briefcase"></i> Serviços</a>

  <a href="agendamentos.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'agendamentos.php') ? 'active' : ''; ?>">
  <i class="bi bi-calendar-check"></i> Agendamentos</a>

  <a href="prontuario.php"class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'prontuario.php') ? 'active' : ''; ?>">
  <i class="bi bi-clipboard"></i> Prontuário</a>

</div>


<!-- Modal de Pefil -->

<script>
// Função para buscar notificações do paciente
let notificacoesAnterioresPaciente = 0;

function carregarNotificacoes() {
    fetch('../php/buscar_notificacao_paciente.php')
        .then(res => res.json())
        .then(data => {

            const lista = document.getElementById('listaNotificacoes');
            const contador = document.getElementById('contadorNotificacoes');
            const sino = document.querySelector('#notificacoesDropdown i');

            lista.innerHTML = '';

            let totalAtual = Number(data.total_nao_lidas) || 0;

            // 🔔 Detectar nova notificação
            if (totalAtual > notificacoesAnterioresPaciente) {
                sino.classList.add('nova-notificacao');
                contador.classList.add('badge-pulso');

                setTimeout(() => {
                    sino.classList.remove('nova-notificacao');
                    contador.classList.remove('badge-pulso');
                }, 1500);
            }

            notificacoesAnterioresPaciente = totalAtual;

            // 🔵 Atualizar badge
            if (totalAtual > 0) {
                contador.textContent = totalAtual;
                contador.classList.remove('d-none');
            } else {
                contador.classList.add('d-none');
            }

            // 📝 Renderizar lista
            if (!data.notificacoes || data.notificacoes.length === 0) {
                lista.innerHTML = '<li class="text-center text-muted small py-2">Nenhuma notificação</li>';
                return;
            }

            data.notificacoes.forEach(n => {
                const li = document.createElement('li');
                li.classList.add('dropdown-item', 'small', n.lida == 0 ? 'nao-lida' : '');
                li.innerHTML = `
                    <div>${n.mensagem}</div>
                    <small class="text-muted">
                        Enviado por: ${n.remetente_nome} |
                        ${new Date(n.data_envio).toLocaleString('pt-BR')}
                    </small>
                `;
                lista.appendChild(li);
            });

        })
        .catch(err => console.error('Erro ao buscar notificações:', err));
}

// Marcar todas como lidas
document.getElementById('marcarLidas').addEventListener('click', () => {
  fetch('../php/marcar_lida_paciente.php', { method: 'POST' })
    .then(res => res.json())
    .then(data => {
      console.log('marcar_lida_paciente response:', data);
      if (data.success) {
        // Força recarregar notificações para refletir o novo estado
        carregarNotificacoes();
      } else {
        alert('Não foi possível marcar as notificações: ' + (data.error || 'erro desconhecido'));
      }
    })
    .catch(err => {
      console.error('Fetch error marcar_lida_paciente:', err);
      alert('Erro na requisição. Veja console para mais detalhes.');
    });
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
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
</script>

<script>
// Botões
const btnEditar = document.getElementById('btnEditarPerfil');
const btnSalvar = document.getElementById('btnSalvarPerfil');
const btnCancelar = document.getElementById('btnCancelarEdicao');

// Alternar modos
btnEditar.addEventListener('click', () => {
    document.querySelectorAll('.view-mode').forEach(el => el.classList.add('d-none'));
    document.querySelectorAll('.edit-mode').forEach(el => el.classList.remove('d-none'));

    btnEditar.classList.add('d-none');
    btnSalvar.classList.remove('d-none');
    btnCancelar.classList.remove('d-none');
});

btnCancelar.addEventListener('click', () => {
    document.querySelectorAll('.view-mode').forEach(el => el.classList.remove('d-none'));
    document.querySelectorAll('.edit-mode').forEach(el => el.classList.add('d-none'));

    btnEditar.classList.remove('d-none');
    btnSalvar.classList.add('d-none');
    btnCancelar.classList.add('d-none');
});

// Salvar alterações via AJAX
btnSalvar.addEventListener('click', () => {

    const dados = {
        nome: document.getElementById('editNome').value,
        email: document.getElementById('editEmail').value,
        telefone: document.getElementById('editTelefone').value,
        cep: document.getElementById('editCep').value
    };

    fetch('editar_perfil.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'ok') {
            alert('Perfil atualizado com sucesso!');

            // Atualiza os textos nos spans
            document.querySelectorAll('.view-mode')[0].textContent = dados.nome;
            document.querySelectorAll('.view-mode')[1].textContent = dados.email;
            document.querySelectorAll('.view-mode')[2].textContent = dados.telefone;
            document.querySelectorAll('.view-mode')[3].textContent = dados.cep;

            btnCancelar.click(); // Volta para o modo de visualização
        } else {
            alert('Erro ao atualizar: ' + data.mensagem);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Erro inesperado.');
    });
});
</script>

<!-- Conteúdo principal -->
<div id="main-content">
  <!-- Aqui vai todo o seu conteúdo -->
