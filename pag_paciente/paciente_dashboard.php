<?php
session_start();
include '../db.php';

// Verifica se o usuário está logado e é paciente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'paciente') {
    header("Location: ../login.php");
    exit();
}

$nome = htmlspecialchars($_SESSION['usuario_nome']);
$id_paciente = $_SESSION['usuario_id'];
$mensagem = "";

// Se o paciente enviar o formulário de agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data']) && isset($_POST['hora'])) {
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $descricao_servico = $_POST['descricao_servico'];

    // Insere o agendamento na tabela agenda
    $stmt = $conn->prepare("
        INSERT INTO agenda (nome_paciente, data, data_agendamento, hora, descricao_servico, paciente_id_paciente)
        VALUES (?, NOW(), ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssi", $nome, $data, $data, $hora, $descricao_servico, $id_paciente);

    if ($stmt->execute()) {
        $mensagem = "✅ Agendamento realizado com sucesso!";
    } else {
        $mensagem = "❌ Erro ao agendar: " . $conn->error;
    }

    $stmt->close();
}

// Busca os agendamentos do paciente logado
$agendamentos = [];
$stmt = $conn->prepare("SELECT id_Agenda, data_agendamento, hora, descricao_servico FROM agenda WHERE paciente_id_paciente = ? ORDER BY data_agendamento DESC");
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $agendamentos[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Paciente - FisioVida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css"> <!-- Usa o CSS que você enviou -->
</head>
<body>

    <!-- Navbar existente -->
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

    <!-- Menu -->
    <div class="collapse navbar-collapse justify-content-end" id="menunavbar">
        <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
            <li class="nav-item"><a href="#bemvindo" class="nav-link active">HOME</a></li>
            <li class="nav-item"><a href="#sobre" class="nav-link">SERVIÇOS</a></li>
            <li class="nav-item"><a href="#agendamento" class="nav-link">AGENDAR</a></li>
            <li class="nav-item"><a href="#meus_agendamentos" class="nav-link">MEUS AGENDAMENTOS</a></li>
        </ul>

    </div>
  </div>
</nav>

        <!-- Seção Bem-vindo -->
        <section id="bemvindo" class="text-center mb-5">
            <h1 class="text-success">Bem-vindo, <?php echo $nome; ?> 👋</h1>
            <p class="lead">Aqui você pode acompanhar suas sessões e agendar novas fisioterapias.</p>
        </section>

        <!-- Seção Sobre Fisioterapia -->
        <section id="sobre" class="p-5 mb-5">
            <h2 class="text-center mb-4">Sobre a Fisioterapia</h2>
            <p class="text-center">
                A Fisioterapia visa restaurar, manter e promover o bem-estar físico e funcional.
                Nossa equipe está pronta para ajudá-lo(a) a alcançar uma recuperação completa,
                com foco na mobilidade, força e qualidade de vida.
            </p>
        </section>

        <!-- Seção Agendamento -->
        <section id="agendamento" class="container my-5">
            <h2 class="text-center mb-4">Agendar Fisioterapia</h2>

            <?php if (!empty($mensagem)) : ?>
                <div class="alert alert-info text-center"><?php echo $mensagem; ?></div>
            <?php endif; ?>

            <form method="POST" class="row g-3 mx-auto">
                <div class="col-md-4">
                    <label for="data" class="form-label">Data</label>
                    <input type="date" class="form-control" id="data" name="data" required>
                </div>
                <div class="col-md-4">
                    <label for="hora" class="form-label">Horário</label>
                    <input type="time" class="form-control" id="hora" name="hora" required>
                </div>
                <div class="col-md-4">
                    <label for="descricao_servico" class="form-label">Serviço</label>
                    <input type="text" class="form-control" id="descricao_servico" name="descricao_servico" placeholder="Ex: Fisioterapia Neurológica" required>
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-success px-5">Agendar</button>
                </div>
            </form>
        </section>

        <!-- Seção Meus Agendamentos -->
        <section id="meus_agendamentos" class="container my-5">
            <h2 class="text-center mb-4">Meus Agendamentos</h2>

            <?php if (empty($agendamentos)) : ?>
                <p class="text-center">Você ainda não possui agendamentos.</p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover text-center align-middle shadow">
                        <thead class="table-success">
                            <tr>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Serviço</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($agendamentos as $ag) : ?>
                                <tr>
                                    <td><?php echo date("d/m/Y", strtotime($ag['data_agendamento'])); ?></td>
                                    <td><?php echo date("H:i", strtotime($ag['hora'])); ?></td>
                                    <td><?php echo htmlspecialchars($ag['descricao_servico']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </main>

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
              <h5 class="text-uppercase">Links úteis</h5>
              <ul class="list-unstyled mb-0">
                <li><a href="#!" class="text-dark">Política de Privacidade</a></li>
                <li><a href="#!" class="text-dark">Termos de Uso</a></li>
                <li><a href="#!" class="text-dark">Contato</a></li>
              </ul>
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

</body>
</html>