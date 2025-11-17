<?php
require_once '../php/db.php';
include __DIR__ . '/partials/header.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Estilo para serviços inativos */
        .card.inativo {
            opacity: 0.4;
            filter: grayscale(100%);
        }
        /* Remove estilo padrão do link e deixa o card clicável */
        .card-link {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0" data-aos="fade-right">Serviços - FisioVida</h2>

    <span class="badge text-bg-primary" data-aos="fade-left">Perfil: paciente</span>
</div>

<div class="container">
    <h2 class="text-center mb-5" data-aos="zoom-in">Nossos Serviços</h2>

    <div class="row row-cols-1 row-cols-md-3 g-4">

      <?php
      $stmt = $pdo->query("SELECT id_servico, nome_servico, descricao_servico, status FROM servico");
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
      ?>

      <div class="col" data-aos="fade-up" data-aos-delay="100">

        <?php if ($row['status'] === 'Ativo'): ?>
            <!-- SERVIÇO ATIVO → CLICÁVEL -->
            <a href="agendar.php?id=<?= $row['id_servico'] ?>" class="card-link">
              <div class="card h-100 shadow">
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($row['nome_servico']) ?></h5>
                  <p class="card-text"><?= htmlspecialchars($row['descricao_servico']) ?></p>
                </div>
              </div>
            </a>

        <?php else: ?>
            <!-- SERVIÇO INATIVO → NÃO CLICÁVEL -->
            <div class="card h-100 shadow inativo">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['nome_servico']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($row['descricao_servico']) ?></p>
                <span class="badge bg-secondary">Indisponível</span>
              </div>
            </div>
        <?php endif; ?>

      </div>

      <?php endwhile; ?>

    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

</html>
