<?php

require_once '../php/db.php';

// Processar envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evolucao = trim($_POST['evolucao'] ?? '');
    $assinatura = trim($_POST['assinatura'] ?? '');
    $data = date('Y-m-d'); // Data atual

    if ($evolucao && $assinatura) {
        $stmt = $pdo->prepare("INSERT INTO prontuario (evolucao, data, assinatura) VALUES (?, ?, ?)");
        $stmt->execute([$evolucao, $data, $assinatura]);
        $mensagem = "Prontuário salvo com sucesso!";
    } else {
        $mensagem = "Por favor, preencha todos os campos.";
    }
}

// Buscar registros já salvos
$stmt = $pdo->query("SELECT * FROM prontuario ORDER BY data DESC, id_prontuario DESC");
$prontuarios = $stmt->fetchAll();

include __DIR__ . '/partials/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo - FisioVida</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .container {
      max-width: 1200px;
    }

    /* Cards de formulário */
    .form-card {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .form-card:hover {
      transform: translateY(-5px);
    }

    /* Cards de prontuário */
    .prontuario-card {
      background: #ffffffcc;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .prontuario-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    h2, h5 {
      color: #000000ff;
    }
  </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="form-card mb-5">
        <h2 class="mb-4 text-center">Evolução dos Pacientes</h2>

        <?php if(!empty($mensagem)) : ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php if($prontuarios): ?>
            <?php foreach($prontuarios as $p): ?>
                <div class="col-lg-6 col-md-12">
                    <div class="prontuario-card">
                        <h5 class="mb-2">Data: <?= date('d/m/Y', strtotime($p['data'])) ?></h5>
                        <p class="mb-2"><strong>Evolução:</strong> <?= nl2br(htmlspecialchars($p['evolucao'])) ?></p>
                        <p class="mb-0"><strong>Assinatura:</strong> <?= htmlspecialchars($p['assinatura']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center text-white">Nenhum prontuário registrado ainda.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
