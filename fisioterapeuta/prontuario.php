<?php
require_once '../php/db.php';
include __DIR__ . '/partials/header.php';

// Verifica login
$cpf = $_SESSION['cpf_fisioterapeuta'] ?? null;

if (!$cpf) {
    header('Location: logar.php');
    exit;
}

// Buscar nome do fisioterapeuta no banco
$cpfFisio = $_SESSION['cpf'] ?? null;

if (!$cpfFisio) {
    die("CPF do usuário não encontrado na sessão.");
}

$stmt = $pdo->prepare("SELECT nome FROM fisioterapeuta WHERE cpf = ?");
$stmt->execute([$cpfFisio]);
$dadosFisio = $stmt->fetch(PDO::FETCH_ASSOC);

$nomeFisioterapeuta = $dadosFisio['nome'] ?? '';

// Processar envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evolucao = trim($_POST['evolucao'] ?? '');
    $data = date('Y-m-d');

    if ($evolucao) {
        $stmt = $pdo->prepare("
            INSERT INTO prontuario (evolucao, data, assinatura)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$evolucao, $data, $nomeFisioterapeuta]);

        $_SESSION['msg'] = "Prontuário salvo com sucesso!";
        $_SESSION['msg_tipo'] = "sucesso";

    } else {
        $mensagem = "Por favor, preencha todos os campos.";
    }
}

// Buscar prontuários
$stmt = $pdo->query("SELECT * FROM prontuario ORDER BY data DESC, id_prontuario DESC");
$prontuarios = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel de Fisioterapeuta - FisioVida</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .container { max-width: 1200px; }
    .form-card { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: transform 0.2s; }
    .form-card:hover { transform: translateY(-5px); }
    .prontuario-card { background: #ffffffcc; border-radius: 12px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; }
    .prontuario-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
    h2, h5 { color: #000000ff; }
    textarea.form-control { resize: none; }
    .alert { border-radius: 10px; }
    @media (max-width: 767px) { .prontuario-card { margin-bottom: 15px; } }
  </style>
</head>
<body>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0" data-aos="fade-right">Painel de Fisioterapeuta - FisioVida</h2>
    <span class="badge text-bg-primary" data-aos="fade-left">Perfil: Fisioterapeuta</span>
  </div>

  <div class="container mt-5 mb-5">
<!-- CARD DO FORMULÁRIO -->
<div class="form-card mb-5" data-aos="zoom-in">
    <h2 class="mb-4 text-center" data-aos="fade-up">Sua Evolução</h2>

    <?php if(!empty($mensagem)) : ?>
        <div class="alert alert-info" data-aos="fade-down"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <form method="post">

        <div class="mb-3">
            <label class="form-label">Evolução</label>
            <textarea name="evolucao" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Assinatura do Fisioterapeuta</label>
            <input type="text" class="form-control" name="assinatura" 
                   value="<?= htmlspecialchars($nomeFisioterapeuta) ?>" readonly>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Salvar Evolução</button>

    </form>
</div>

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
</body>
</html>
