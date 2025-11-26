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
    'recusado' => 0,
    'concluido' => 0
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
    WHERE status = 'concluido'
");
$concluidos = (int)$stmtConcluidos->fetch()['total'];
// 🔥 Ajuste necessário
$dados['concluido'] = $concluidos;

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Administrador</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
.chart-container {
    max-width: 480px;
    margin: 0 auto;
    padding: 20px;
}

#statusChart {
    background: #ffffff;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    transition: transform .3s ease;
}

#statusChart:hover {
    transform: scale(1.02);
}
</style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h4 mb-0" data-aos="fade-right">Painel De Relatórios - FisioVida</h2>
        <span class="badge text-bg-primary" data-aos="fade-left">Perfil: Administrador</span>
    </div>

    <div class="main-content" data-aos="fade-up" data-aos-delay="200">
        <h2>📊 Relatórios de Agendamentos</h2>

        <div class="chart-container" style="max-width:600px; margin: 0 auto;">
            <canvas id="statusChart" width="400" height="400"></canvas>
        </div>

        <div class="summary" style="display:flex; gap:16px; flex-wrap:wrap; margin-top:20px;">
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
            <div class="summary-card">
                <h3>⏳ Pendentes</h3>
                <p><?= $dados['pendente'] ?></p>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusChart').getContext('2d');

    // Cores em gradiente
    const gradientConcluido   = ctx.createLinearGradient(0, 0, 0, 250);
    gradientConcluido.addColorStop(0, "#1abc9c");
    gradientConcluido.addColorStop(1, "#16a085");

    const gradientConfirmado  = ctx.createLinearGradient(0, 0, 0, 250);
    gradientConfirmado.addColorStop(0, "#2ecc71");
    gradientConfirmado.addColorStop(1, "#27ae60");

    const gradientPendente    = ctx.createLinearGradient(0, 0, 0, 250);
    gradientPendente.addColorStop(0, "#f1c40f");
    gradientPendente.addColorStop(1, "#d4ac0d");

    const gradientRemarcado   = ctx.createLinearGradient(0, 0, 0, 250);
    gradientRemarcado.addColorStop(0, "#3498db");
    gradientRemarcado.addColorStop(1, "#2e86c1");

    const gradientRecusado    = ctx.createLinearGradient(0, 0, 0, 250);
    gradientRecusado.addColorStop(0, "#e74c3c");
    gradientRecusado.addColorStop(1, "#cb4335");

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Concluídos', 'Confirmados', 'Pendentes', 'Remarcados', 'Recusados'],
            datasets: [{
                label: 'Agendamentos',
                data: [
                    <?= $concluidos ?>,
                    <?= $dados['confirmado'] ?>,
                    <?= $dados['pendente'] ?>,
                    <?= $dados['remarcado'] ?>,
                    <?= $dados['recusado'] ?>
                ],
                backgroundColor: [
                    gradientConcluido,
                    gradientConfirmado,
                    gradientPendente,
                    gradientRemarcado,
                    gradientRecusado
                ],
                borderColor: "#ffffff",
                borderWidth: 3,
                hoverOffset: 18,
                hoverBorderColor: "#f7f7f7"
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "68%", // donut mais elegante
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1400,
                easing: 'easeOutElastic'
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 14,
                            family: 'Arial'
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Distribuição de Agendamentos por Status',
                    padding: 20,
                    font: {
                        size: 18,
                        weight: '600'
                    }
                }
            }
        }
    });
});
</script>

<?php include('partials/footer.php'); ?>
</body>
</html>
