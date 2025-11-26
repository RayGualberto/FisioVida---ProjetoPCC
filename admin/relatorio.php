<?php

require_once('../php/db.php');
include('partials/header.php');



// Contagem dos status dos agendamentos
$stmt = $pdo->query("
    SELECT 
        status, 
        COUNT(*) AS total
    FROM agenda
    GROUP BY status
");

$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicializando contadores
$dados = [
    'confirmado' => 0,
    'pendente' => 0,
    'remarcado' => 0,
    'recusado' => 0
];

foreach ($resultados as $row) {
    $status = strtolower($row['status']);
    if (isset($dados[$status])) {
        $dados[$status] = (int)$row['total'];
    }
}

// Pacientes concluídos (todos confirmados com data passada)
$stmtConcluidos = $pdo->query("
    SELECT COUNT(*) AS total 
    FROM agenda 
    WHERE status = 'confirmado' AND data < CURDATE()
");
$concluidos = $stmtConcluidos->fetch()['total'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Adiministrador</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/fisioterapeuta.css">
</head>
    <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0" data-aos="fade-right">Painel De Relatórios - FisioVida</h2>
    <span class="badge text-bg-primary" data-aos="fade-left">Perfil: Adiministrador</span>
  </div>
<div class="main-content">
    <h2>📊 Relatórios de Agendamentos</h2>

    <div class="chart-container">
        <canvas id="statusChart"></canvas>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h3>✅ Concluídos</h3>
            <p><?= $concluidos ?></p>
        </div>
        <div class="summary-card">
            <h3>📅 Confirmados</h3>
            <p><?= $dados['confirmado'] ?></p>
        </div>
        <div class="summary-card">
            <h3>🔁 Remarcados</h3>
            <p><?= $dados['remarcado'] ?></p>
        </div>
        <div class="summary-card">
            <h3>❌ Recusados</h3>
            <p><?= $dados['recusado'] ?></p>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('statusChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Confirmados', 'Pendentes', 'Remarcados', 'Recusados'],
        datasets: [{
            label: 'Agendamentos',
            data: [
                <?= $dados['confirmado'] ?>,
                <?= $dados['pendente'] ?>,
                <?= $dados['remarcado'] ?>,
                <?= $dados['recusado'] ?>
            ],
            backgroundColor: [
                '#2ecc71', // verde
                '#f1c40f', // amarelo
                '#3498db', // azul
                '#e74c3c'  // vermelho
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Distribuição de Agendamentos por Status'
            }
        }
    }
});
</script>

<?php include('partials/footer.php'); ?>
</html>
