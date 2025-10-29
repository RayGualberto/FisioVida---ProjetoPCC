<?php
require_once '../php/db.php'; // mantém o caminho original conforme sua preferência

// Inserção no banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($_POST['nome'] ?? '');
    $telefone  = trim($_POST['telefone'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $mensagem  = trim($_POST['mensagem'] ?? '');
    $rating    = trim($_POST['rating'] ?? '');

    if ($nome && $email && $rating) {
        $stmt = $pdo->prepare("INSERT INTO avaliacao (nome_paciente, telefone, email, avaliacao) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $telefone, $email, "Nota: $rating ⭐ - $mensagem"]);
    }
}

// Buscar avaliações existentes
$stmt = $pdo->query("SELECT nome_paciente, avaliacao FROM avaliacao ORDER BY id_avaliacao DESC");
$avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Fisiovida</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="icon" href="../img/Icone fisiovida.jfif">
  <link rel="stylesheet" href="../css/style.css">
  
  <!-- Adicionando JQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script src="server.js"></script>
  <style>
    .star-rating {
      display: inline-flex;
      flex-direction: row-reverse;
      justify-content: center;
    }
    .star-rating input {
      display: none;
    }
    .star-rating label {
      font-size: 2rem;
      color: #ccc;
      cursor: pointer;
      transition: color 0.2s;
    }
    .star-rating input:checked ~ label {
      color: #ffc107;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label {
      color: #ffc107;
    }

    /* Avaliações com rolagem lateral */
    .avaliacoes-container {
      display: flex;
      overflow-x: auto;
      gap: 1rem;
      padding: 1rem;
      scroll-behavior: smooth;
    }
    .avaliacoes-container::-webkit-scrollbar {
      height: 10px;
    }
    .avaliacoes-container::-webkit-scrollbar-thumb {
      background-color: #198754;
      border-radius: 5px;
    }
    .avaliacao-card {
      min-width: 250px;
      max-width: 300px;
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 1rem;
      flex-shrink: 0;
    }
    .avaliacao-card h6 {
      font-weight: 600;
    }
  </style>

    <!-- Máscara para telefone -->

      <script>
    $(document).ready(function(){
      $('#telefone').mask('(00) 00000-0000');
    });
</script>
</head>
<body>

 <!-- Barra superior do site. -->
     <div class="container-fluid bg-light ps-1 pe-0 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-md-6 text-center text-lg-start mb-0">
                <div class="d-inline-flex align-items-center">
                    <small class="py-2"><i class="bi bi-clock container"></i>Aberto de Segunda-feira a Sexta-feira das 8 : 00 AM até 18 : 00 PM.</small>
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
  <!--fim da barra superior do site. -->

  <!-- Barra de navegação do site. -->

<nav class="navbar navbar-expand-lg bg-light sticky-xxl-top">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <!-- Logo colado à esquerda -->
    <a href="#" class="navbar-brand">
      <img src="../img/Fisiovida logo.png" alt="imagemfisiovida" width="120" height="90">
    </a>

    <!-- Botão toggle para mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menunavbar" aria-controls="menunavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu e botões alinhados à direita -->
    <div class="collapse navbar-collapse justify-content-end" id="menunavbar">
      <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
        <li class="nav-item"><a href="index.html" class="nav-link">HOME</a></li>
        <li class="nav-item"><a href="servico.html" class="nav-link ">SERVIÇOS</a></li>
        <li class="nav-item"><a href="sobre.html" class="nav-link">SOBRE</a></li>
        <li class="nav-item"><a href="contato.php" class="nav-link active">CONTATO</a></li>
      </ul>

      <div class="d-flex gap-2 ms-3">
        <a href="./login.php"><button type="button" class="btn btn-outline-primary"><i class="bi bi-person-fill"></i> Login</button></a>
      </div>
    </div>
  </div>
</nav>

  

  <!-- Fim da barra de navegação do site. -->

<!-- (Seu conteúdo anterior todo aqui, sem mudanças até o formulário) -->

<div class="container my-5">
  <h2 class="text-center mb-4">Avalie sua experiência</h2>
  <p class="text-center mb-5">
    Preencha o formulário abaixo para avaliar nosso serviço ou enviar dúvidas e sugestões.
  </p>
  
  <form class="row g-3" method="POST">

    <div class="col-md-6">
      <label for="nome" class="form-label">Nome Completo</label>
      <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome" required>
    </div>

    <div class="col-md-6">
      <label for="telefone" class="form-label">Telefone / WhatsApp</label>
      <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(11) 99999-9999">
    </div>

    <div class="col-12">
      <label for="email" class="form-label">E-mail</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="seuemail@exemplo.com" required>
    </div>

    <div class="col-12">
      <label for="mensagem" class="form-label">Mensagem</label>
      <textarea class="form-control" id="mensagem" name="mensagem" rows="5" placeholder="Escreva sua mensagem..."></textarea>
    </div>

    <div class="col-12 text-center">
      <label class="form-label d-block mb-2">Avaliação</label>
      <div class="star-rating">
        <input type="radio" id="star5" name="rating" value="5"><label for="star5">&#9733;</label>
        <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
        <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
        <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
        <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
      </div>
    </div>

    <div class="col-12 text-center">
      <button type="submit" class="btn btn-primary px-5 py-2">
        Enviar Avaliação
      </button>
    </div>
  </form>
</div>

<!-- Avaliações dos pacientes -->
<div class="container my-5">
  <h3 class="text-center mb-4">O que nossos pacientes dizem</h3>
  <div class="avaliacoes-container">
    <?php if ($avaliacoes): ?>
      <?php foreach ($avaliacoes as $a): ?>
        <div class="avaliacao-card">
          <h6><?= htmlspecialchars($a['nome_paciente']) ?></h6>
          <p class="text-muted mb-0"><?= htmlspecialchars($a['avaliacao']) ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted">Ainda não há avaliações registradas.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Apartir daqui começa o Rodapé da pagina. -->

<footer class="bg-light text-center text-lg-start mt-auto">
        <div class="container p-4">
          <div class="row">
            
            <div class="col-lg-3 col-md-12 mb-4 mb-md-0">
              <h5 class="text-uppercase">Fisiovida</h5>
              <p>
                Endereço: Rua Exemplo, 123 - Cidade, Estado<br>
                Telefone: +55 12 3456-7890<br>
                Email: contato@fisiovida.com.br
              </p>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
              <h5 class="text-uppercase mb-0">Redes sociais</h5>
              <ul class="list-unstyled d-flex justify-content-start gap-3 mt-3">
                <li><a href="#!" class="text-dark"><i class="bi bi-facebook fs-4"></i></a></li>
                <li><a href="#!" class="text-dark"><i class="bi bi-instagram fs-4"></i></a></li>
                <li><a href="#!" class="text-dark"><i class="bi bi-twitter fs-4"></i></a></li>
              </ul>
            </div>
            
            <div class="col-lg-3 col-md-12 mb-4 mb-md-0">
              <h5 class="text-uppercase">Slogan</h5>
              <p>
                Conectamos você a fisioterapeutas qualificados, oferecendo praticidade no agendamento e cuidado humano em cada sessão
              </p>
            </div>

          </div>
        </div>
        <div class="text-center p-3 bg-secondary text-white">
          © 2025 Fisiovida. Todos os direitos reservados.
        </div>
      </footer>
<!-- Fim do Rodapé da pagina. -->
</body>
</html>