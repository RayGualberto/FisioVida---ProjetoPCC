<?php
session_start();
require_once '../php/db.php';

// Buscar registros já salvos
$stmt = $pdo->query("SELECT * FROM prontuario ORDER BY data DESC, id_prontuario DESC");
$prontuarios = $stmt->fetchAll();

include __DIR__ . '../partials/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo - FisioVida</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    .container { max-width: 1200px; }

    .form-card {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .form-card:hover { transform: translateY(-5px); }

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

    h2, h5 { color: #000000ff; }
  </style>
</head>

<!-- CABEÇALHO -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0" data-aos="fade-right">Painel de Prontuários - FisioVida</h2>
    <span class="badge text-bg-primary" data-aos="fade-left">Perfil: Paciente</span>
</div>

<div class="container mt-5 mb-5">

    <div class="row g-4" data-aos="fade-up">
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
                <p class="text-center text-dark">Nenhum prontuário registrado ainda.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
</html>
