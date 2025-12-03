<?php
require_once '../php/db.php'; // mantém o caminho original conforme sua preferência
session_start();
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
    // 🔥 AQUI: mensagem para aparecer na página de login
    $_SESSION['msg'] = "Agradeçemos sua avaliação!!";
    $_SESSION['msg_tipo'] = "sucesso";
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
  <link rel="icon" href="../img/Icone fisiovida.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- AOS -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Link css -->
  <link rel="stylesheet" href="../css/avaliacao.css">
</head>
<body>
    <!-- Top info bar -->
    <div class="container-fluid top-info py-2 d-none d-lg-block">
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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top py-3">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <img src="../img/Fisiovida logo.png" alt="Fisiovida" width="150" height="100" style="object-fit:contain;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menunavbar" aria-controls="menunavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

            <div class="collapse navbar-collapse" id="menunavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center text-center">
                    <li class="nav-item"><a class="nav-link" href="index.html">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="servico.html">SERVIÇOS</a></li>
                    <li class="nav-item"><a class="nav-link" href="sobre.html">SOBRE</a></li>
                    <li class="nav-item"><a class="nav-link" href="avaliacao.php">AVALIAÇÃO</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="./login.php"><button class="btn btn-outline-primary"><i class="bi bi-person-fill me-2"></i>Login</button></a>
                    </li>
                </ul>
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
 <footer class="bg-light text-center text-lg-start mt-auto">
    <div class="container p-4">
      <div class="row">
        <div class="col-lg-3 col-md-12 mb-4 mb-md-0" data-aos="fade-right">
          <h5 class="text-uppercase">Fisiovida</h5>
          <p>
            Endereço: Rua Exemplo, 123 - Cidade, Estado<br>
            Telefone: +55 12 3456-7890<br>
            Email: fisiovidarmnf@gmail.com
          </p>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
          <h5 class="text-uppercase mb-0">Redes sociais</h5>
          <ul class="list-unstyled d-flex justify-content-start gap-3 mt-3">
            <li><a href="#!" class="text-dark"><i class="bi bi-facebook fs-4"></i></a></li>
            <li><a href="#!" class="text-dark"><i class="bi bi-instagram fs-4"></i></a></li>
            <li><a href="#!" class="text-dark"><i class="bi bi-twitter fs-4"></i></a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-12 mb-4 mb-md-0" data-aos="fade-left" data-aos-delay="400">
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

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
  </script>
     <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="../js/notificacoes.js"></script>

  <?php if (!empty($_SESSION['msg'])): ?>
  <script>
  mostrarMensagem("<?= $_SESSION['msg'] ?>", "<?= $_SESSION['msg_tipo'] ?>" === "sucesso");
  </script>
  <?php 
  unset($_SESSION['msg']);
  unset($_SESSION['msg_tipo']);
  endif;
  ?>
</body>
</html>