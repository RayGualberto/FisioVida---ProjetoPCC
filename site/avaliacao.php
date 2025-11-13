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
  <title>Fisiovida - Avaliações</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- AOS -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Estilo Global do Sistema -->
  <style>
    /* === ESTILO PRINCIPAL REUTILIZADO === */
    :root {
      --azul-base: #b3e5fc;
      --azul-escuro: #0288d1;
      --bg-soft: linear-gradient(135deg, #f5fbff, #eaf8ff);
      --glass: rgba(255, 255, 255, 0.72);
      --muted: #6b7280;
    }

    html, body {
      height: 100%;
      font-family: "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: var(--bg-soft);
      color: #0f172a;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    .navbar {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(6px);
      box-shadow: 0 6px 18px rgba(9, 30, 63, 0.06);
    }

    .navbar .nav-link {
      color: #0b3b56;
      font-weight: 600;
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

    h2, h3, h5 {
      color: var(--azul-escuro);
      font-weight: 700;
    }

    .form-control, textarea {
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(2, 24, 56, 0.04);
      border: 1px solid rgba(3, 24, 56, 0.1);
    }

    .btn-primary {
      background: linear-gradient(90deg, var(--azul-escuro), #016699);
      border: none;
      border-radius: 999px;
      box-shadow: 0 8px 20px rgba(3, 24, 56, 0.12);
    }

    .btn-primary:hover {
      background: var(--azul-base);
      color: #033748;
    }

    /* Estilo das estrelas */
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

    /* Cards de avaliações */
    .avaliacoes-container {
      max-width: 720px;
      margin: 0 auto;
    }

    .avaliacao-card {
      background: #fff;
      border-radius: 14px;
      padding: 18px 22px;
      margin-bottom: 20px;
      box-shadow: 0 10px 30px rgba(2, 24, 56, 0.06);
      transition: transform .3s ease, box-shadow .3s ease;
    }

    .avaliacao-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 40px rgba(2, 24, 56, 0.09);
    }

    footer {
      background: #ffffff;
      color: #243b4a;
    }

    footer .bg-bottom {
      background: var(--azul-escuro);
      color: #fff;
    }

    .fade-zoom-up {
      transform-origin: 50% 100%;
      transition: transform .5s cubic-bezier(.2, .9, .3, 1), opacity .5s;
    }

    .fade-zoom-up:hover {
      transform: translateY(-6px) scale(1.01);
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="#">
        <img src="../img/Fisiovida logo.png" alt="Fisiovida" width="120" height="90">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="menuNav">
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item"><a href="index.html" class="nav-link">HOME</a></li>
          <li class="nav-item"><a href="servico.html" class="nav-link">SERVIÇOS</a></li>
          <li class="nav-item"><a href="sobre.html" class="nav-link">SOBRE</a></li>
          <li class="nav-item"><a href="contato.php" class="nav-link active">CONTATO</a></li>
        </ul>
        <a href="./login.php" class="btn btn-outline-primary ms-3"><i class="bi bi-person-fill"></i> Login</a>
      </div>
    </div>
  </nav>

  <!-- Formulário -->
  <section class="container my-5" data-aos="fade-up">
    <h2 class="text-center mb-4">Avalie sua experiência</h2>
    <p class="text-center text-muted mb-5">Deixe sua opinião ou sugestão para melhorarmos nossos serviços.</p>

    <form method="POST" class="row g-3">
      <div class="col-md-6">
        <label for="nome" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" name="nome" id="nome" required>
      </div>

      <div class="col-md-6">
        <label for="telefone" class="form-label">Telefone / WhatsApp</label>
        <input type="tel" class="form-control" name="telefone" id="telefone">
      </div>

      <div class="col-12">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" name="email" id="email" required>
      </div>

      <div class="col-12">
        <label for="mensagem" class="form-label">Mensagem</label>
        <textarea class="form-control" name="mensagem" id="mensagem" rows="5"></textarea>
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
        <button type="submit" class="btn btn-primary px-5 py-2">Enviar Avaliação</button>
      </div>
    </form>
  </section>

  <!-- Avaliações -->
  <section class="container my-5" data-aos="fade-up">
    <h3 class="text-center mb-4">O que nossos pacientes dizem</h3>
    <div class="avaliacoes-container">
      <?php if ($avaliacoes): ?>
        <?php foreach ($avaliacoes as $index => $a): ?>
          <div class="avaliacao-card fade-zoom-up" data-aos="fade-up" data-aos-delay="<?= 200 + ($index * 100) ?>">
            <h6 class="fw-bold"><?= htmlspecialchars($a['nome_paciente']) ?></h6>
            <p class="text-muted mb-0"><?= htmlspecialchars($a['avaliacao']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center text-muted">Ainda não há avaliações registradas.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Rodapé -->
  <footer class="text-center text-lg-start mt-auto">
    <div class="container p-4">
      <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
          <h5>Fisiovida</h5>
          <p>Rua Exemplo, 123 - Cidade, Estado<br>Telefone: (12) 3456-7890<br>Email: contato@fisiovida.com.br</p>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <h5>Redes Sociais</h5>
          <div class="d-flex justify-content-center gap-3 fs-4 mt-3">
            <a href="#" class="text-dark"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-dark"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-dark"><i class="bi bi-twitter"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-md-12">
          <h5>Slogan</h5>
          <p>Conectamos você a fisioterapeutas qualificados, oferecendo praticidade no agendamento e cuidado humano em cada sessão.</p>
        </div>
      </div>
    </div>
    <div class="bg-bottom text-center p-3">© 2025 Fisiovida. Todos os direitos reservados.</div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
  </script>
</body>
</html>