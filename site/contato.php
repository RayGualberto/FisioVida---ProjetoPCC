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

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- AOS (Animate On Scroll) -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f8fafc;
    }

    .star-rating {
      display: flex;
      justify-content: center;
      flex-direction: row-reverse;
      gap: 5px;
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
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
      color: #f8c10a;
    }

    /* Estilo dos cards de avaliação */
    .avaliacoes-container {
      max-width: 700px;
      margin: 0 auto;
    }

    .avaliacao-card {
      background: #fff;
      border-radius: 12px;
      padding: 15px 20px;
      margin-bottom: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
      transition: transform 0.3s;
    }

    .avaliacao-card:hover {
      transform: translateY(-5px);
    }

    footer {
      margin-top: 60px;
    }
  </style>
</head>

<body>

  <!-- Barra superior -->
  <div class="container-fluid bg-light ps-1 pe-0 d-none d-lg-block">
    <div class="row gx-0">
      <div class="col-md-6 text-center text-lg-start mb-0">
        <div class="d-inline-flex align-items-center">
          <small class="py-2">
            <i class="bi bi-clock container"></i>
            Aberto de Segunda a Sexta das 8:00 às 18:00
          </small>
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
        <img src="../img/Fisiovida logo.png" alt="imagemfisiovida" width="120" height="90">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menunavbar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="menunavbar">
        <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
          <li class="nav-item"><a href="index.html" class="nav-link">HOME</a></li>
          <li class="nav-item"><a href="servico.html" class="nav-link">SERVIÇOS</a></li>
          <li class="nav-item"><a href="sobre.html" class="nav-link">SOBRE</a></li>
          <li class="nav-item"><a href="contato.php" class="nav-link active">CONTATO</a></li>
        </ul>

        <div class="d-flex gap-2 ms-3">
          <a href="./login.php"><button type="button" class="btn btn-outline-primary"><i class="bi bi-person-fill"></i> Login</button></a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Formulário de avaliação -->
  <div class="container my-5" data-aos="fade-up" data-aos-delay="100">
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

  <!-- Avaliações -->
  <div class="container my-5" data-aos="fade-up" data-aos-delay="300">
    <h3 class="text-center mb-4">O que nossos pacientes dizem</h3>
    <div class="avaliacoes-container">
      <?php if ($avaliacoes): ?>
        <?php foreach ($avaliacoes as $index => $a): ?>
          <div class="avaliacao-card" data-aos="fade-up" data-aos-delay="<?= 200 + ($index * 100) ?>">
            <h6><?= htmlspecialchars($a['nome_paciente']) ?></h6>
            <p class="text-muted mb-0"><?= htmlspecialchars($a['avaliacao']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center text-muted">Ainda não há avaliações registradas.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Rodapé -->
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
          <p>Conectamos você a fisioterapeutas qualificados, oferecendo praticidade no agendamento e cuidado humano em cada sessão</p>
        </div>
      </div>
    </div>
    <div class="text-center p-3 bg-secondary text-white">
      © 2025 Fisiovida. Todos os direitos reservados.
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>
</body>
</html>
