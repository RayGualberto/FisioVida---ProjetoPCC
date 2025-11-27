<?php
require_once('../php/db.php');
include('partials/header.php');

// Contagem dos status dos agendamentos
$stmt = $pdo->query("
    SELECT status, COUNT(*) AS total
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

$stmtConcluidos = $pdo->query("SELECT COUNT(*) AS total FROM agenda WHERE status = 'concluido'");
$concluidos = (int)$stmtConcluidos->fetch()['total'];
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
/* ======= ESTILO GLOBAL ======= */
body {
    background: #f4f7fb;
}

/* TITULO */
.page-title {
    font-size: 1.6rem;
    font-weight: 600;
    color: #1a1a1a;
}

/* CARD DO GRÁFICO */
.chart-wrapper {
    background: #ffffff;
    border-radius: 20px;
    padding: 30px;
    max-width: 650px;
    margin: 0 auto;
    box-shadow: 0 6px 25px rgba(0,0,0,0.09);
}

/* CONTÊINER DO GRÁFICO */
.chart-container {
    position: relative;
    height: 380px;
}

/* CARDS DO RESUMO */
.summary {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 18px;
    margin-top: 25px;
}

.summary-card {
    background: #ffffff;
    padding: 18px 25px;
    width: 180px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    transition: 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-4px);
}

.summary-card h3 {
    font-size: 1.1rem;
    margin-bottom: 8px;
    color: #333;
}

.summary-card p {
    font-size: 1.6rem;
    font-weight: 700;
    margin: 0;
    color: #0099ff;
}

/* ========================= */
/*   RESPONSIVIDADE MOBILE   */
/* ========================= */

@media (max-width: 768px) {

    .page-title {
        font-size: 1.3rem;
    }

    .chart-wrapper {
        padding: 18px;
        margin: 0 12px;
    }

    .chart-container {
        height: 300px;
    }

    .summary-card {
        width: 45%;
        padding: 15px 10px;
    }

    .summary-card h3 {
        font-size: 1rem;
    }

    .summary-card p {
        font-size: 1.4rem;
    }
}

@media (max-width: 480px) {

    .page-title {
        font-size: 1.15rem;
        text-align: center;
    }

    .chart-wrapper {
        padding: 15px;
        margin: 0 8px;
    }

    .chart-container {
        height: 260px;
    }

    .summary {
        gap: 12px;
    }

    .summary-card {
        width: 100%;
    }

    .summary-card p {
        font-size: 1.3rem;
    }
}

.chart-wrapper {
    display: flex;
    justify-content: center;
    width: 100%;
}

.chart-container {
    width: 100%;
    max-width: 400px; /* para manter o gráfico com tamanho ideal */
}



</style>
</head>

<body>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="page-title"> Relatórios de Agendamentos - FisioVida</h2>
    <span class="badge text-bg-primary">Perfil: Administrador</span>
</div>

<div class="main-content" data-aos="fade-up" data-aos-delay="200">

    <!-- CARD DO GRÁFICO -->
<h3 class="text-center mb-4" style="font-weight:600;">Distribuição de Agendamentos</h3>

<div class="chart-wrapper">
    <div class="chart-container">
        <canvas id="statusChart"></canvas>
    </div>
</div>


    <!-- CARDS RESUMO -->
    <div class="summary">
        <div class="summary-card">
            <h3>Concluídos</h3>
            <p><?= $concluidos ?></p>
        </div>

        <div class="summary-card">
            <h3>Confirmados</h3>
            <p><?= $dados['confirmado'] ?></p>
        </div>

        <div class="summary-card">
            <h3>Pendentes</h3>
            <p><?= $dados['pendente'] ?></p>
        </div>

        <div class="summary-card">
            <h3>Remarcados</h3>
            <p><?= $dados['remarcado'] ?></p>
        </div>

        <div class="summary-card">
            <h3>Recusados</h3>
            <p><?= $dados['recusado'] ?></p>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('statusChart').getContext('2d');

    // Gradientes
    const createGradient = (c1, c2) => {
        let grad = ctx.createLinearGradient(0, 0, 0, 300);
        grad.addColorStop(0, c1);
        grad.addColorStop(1, c2);
        return grad;
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Concluídos', 'Confirmados', 'Pendentes', 'Remarcados', 'Recusados'],
            datasets: [{
                data: [
                    <?= $concluidos ?>,
                    <?= $dados['confirmado'] ?>,
                    <?= $dados['pendente'] ?>,
                    <?= $dados['remarcado'] ?>,
                    <?= $dados['recusado'] ?>
                ],
                backgroundColor: [
                    createGradient('#16a085', '#1abc9c'),
                    createGradient('#27ae60', '#2ecc71'),
                    createGradient('#f1c40f', '#f39c12'),
                    createGradient('#3498db', '#2980b9'),
                    createGradient('#e74c3c', '#c0392b')
                ],
                borderWidth: 3,
                borderColor: "#fff",
                hoverOffset: 18
            }]
        },
        options: {
            responsive: true,
            cutout: "65%",
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        font: { size: 14 }
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
