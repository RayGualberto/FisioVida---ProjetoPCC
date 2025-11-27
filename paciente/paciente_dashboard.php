<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../php/db.php'; 

include __DIR__ . '../partials/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
    /* Cards fisioterapeutas */
    .fisioterapeuta-card {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 300px;
    }
    .fisioterapeuta-card:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 25px rgba(0,0,0,0.3);
    }
    .fisioterapeuta-card .card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .overlay-text {
        background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));
        text-align: left;
    }
    .overlay-text h5, .overlay-text p {
        margin: 0;
    }
    
    /* Carrossel de serviços */
    .carousel-card {
        max-width: 800px;
        margin: 0 auto;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .carousel-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .carousel-card img.card-img-top {
        height: 400px;
        object-fit: cover;
    }
    .carousel-card .card-body {
        padding: 1rem;
    }
    
    /* Responsividade */
    @media(max-width:768px){
        .fisioterapeuta-card { height: 250px; }
        .carousel-card img.card-img-top { height: 150px; }
    }
    @media(max-width:480px){
        .fisioterapeuta-card { height: 200px; }
        .carousel-card img.card-img-top { height: 120px; }
    }
    </style>
</head>
        <!-- Cabeçalho do painel -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0" data-aos="fade-right">Inicio - FisioVida</h2>
    <span class="badge text-bg-primary" data-aos="fade-left">Perfil: Paciente</span>
</div>

    <!-- Boas-vindas -->
    <div class="mb-5">
        <h1 class="display-5 fw-bold" data-aos="fade-right" data-aos-delay="250">
            Bem-vindo à Fisiovida, <?= htmlspecialchars($nomePaciente); ?>!
        </h1>
        <p class="lead mt-3" data-aos="fade-left" data-aos-delay="350">
            Cuidando de você com excelência, humanidade e tecnologia.
        </p>
    </div>

    <!-- Cards de Fisioterapeutas -->
    <div class="mb-5" data-aos="fade-up" data-aos-delay="500">
    <h5 class="fw-bold mb-4">👨‍⚕️ Nosso Time de Fisioterapeutas</h5>
    <div class="row g-4">
        <?php
        $sqlF = "SELECT id_fisioterapeuta, nome, especialidade FROM fisioterapeuta LIMIT 4";
        $resultF = $pdo->query($sqlF);

        // Array de imagens diferentes (deve ter pelo menos a mesma quantidade de fisioterapeutas)
        $fisioterapeutaImages = [
            "../img/CaraAleatorio1.avif",
            "../img/CaraAleatorio2.avif",
            "../img/CaraAleatorio3.jpeg",
            "../img/CaraAleatorio4.jpeg"
        ];

        $i = 0; // contador para percorrer o array de imagens
        foreach ($resultF as $fis): 
            $img = $fisioterapeutaImages[$i % count($fisioterapeutaImages)]; // pega imagem do array
        ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card fisioterapeuta-card shadow-lg card-hover">
                    <img src="<?= $img ?>" class="card-img" alt="<?= htmlspecialchars($fis['nome']); ?>">
                    <div class="card-img-overlay d-flex flex-column justify-content-end overlay-text p-3">
                        <h5 class="card-title text-white fw-bold"><?= htmlspecialchars($fis['nome']); ?></h5>
                        <p class="card-text text-white"><?= htmlspecialchars($fis['especialidade']); ?></p>
                    </div>
                </div>
            </div>
        <?php 
            $i++;
        endforeach; ?>
    </div>
    </div>


    <!-- Carrossel de Serviços -->
    <section id="servicos" class="contaner-fluid my-5" data-aos="fade-up">
        <h1 class="mb-4 text-center">Nossos Serviços de Fisioterapia</h1>

        <div id="carouselServicos" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <!-- Card 1 -->
            <div class="carousel-item active">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Ortopédica.jpg" class="card-img-top" alt="Fisioterapia Ortopédica">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Ortopédica e Traumatológica</h5>
                        <p class="card-text small mb-0">Tratamento de lesões musculoesqueléticas, como entorses, fraturas, tendinites e recuperação pós-cirúrgica.</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="carousel-item">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Neutologica.jpeg" class="card-img-top" alt="Fisioterapia Neurológica">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Neurológica</h5>
                        <p class="card-text small mb-0">Reabilitação de pacientes com AVC, lesões medulares, Parkinson, esclerose múltipla e outras condições neurológicas.</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="carousel-item">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Respiratória.jpg" class="card-img-top" alt="Fisioterapia Respiratória">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Respiratória</h5>
                        <p class="card-text small mb-0">Tratamento para doenças pulmonares, auxílio em pós-operatório e melhoria da capacidade respiratória.</p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="carousel-item">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Esportiva.jpg" class="card-img-top" alt="Fisioterapia Esportiva">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Esportiva</h5>
                        <p class="card-text small mb-0">Prevenção e tratamento de lesões relacionadas à prática esportiva e exercícios físicos.</p>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="carousel-item">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Pediátrica.png" class="card-img-top" alt="Fisioterapia Pediátrica">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Pediátrica</h5>
                        <p class="card-text small mb-0">Atendimento especializado para crianças com atrasos no desenvolvimento motor e outras condições pediátricas.</p>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="carousel-item">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Geriátrica.jpg" class="card-img-top" alt="Fisioterapia Geriátrica">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Geriátrica</h5>
                        <p class="card-text small mb-0">Melhora da mobilidade, equilíbrio e qualidade de vida para a população idosa.</p>
                    </div>
                </div>
            </div>

            <!-- Card 7 -->
            <div class="carousel-item">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Dermatofuncional.jpeg" class="card-img-top" alt="Fisioterapia Dermatofuncional">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Dermatofuncional</h5>
                        <p class="card-text small mb-0">Tratamentos estéticos e terapêuticos para pele, cicatrizes, celulite e edemas.</p>
                    </div>
                </div>
            </div>

            <!-- Card 8 -->
            <div class="carousel-item">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Uroginecológica.png" class="card-img-top" alt="Fisioterapia Uroginecológica">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Uroginecológica e Obstétrica</h5>
                        <p class="card-text small mb-0">Tratamento de disfunções do assoalho pélvico, incontinência urinária e preparação para o parto.</p>
                    </div>
                </div>
            </div>

            <!-- Card 9 -->
            <div class="carousel-item">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Cardiorrespiratória.jpeg" class="card-img-top" alt="Fisioterapia Cardiorrespiratória">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Cardiorrespiratória</h5>
                        <p class="card-text small mb-0">Reabilitação após infarto, cirurgias cardíacas e doenças do coração.</p>
                    </div>
                </div>
            </div>

            <!-- Card 10 -->
            <div class="carousel-item">
                <div class="card shadow-sm border-0 rounded-3 carousel-card">
                    <img src="../img/Aquática.jpg" class="card-img-top" alt="Fisioterapia Aquática">
                    <div class="card-body p-2">
                        <h5 class="card-title fw-bold">Fisioterapia Aquática (Hidroterapia)</h5>
                        <p class="card-text small mb-0">Uso da água para exercícios terapêuticos e reabilitação com menor impacto.</p>
                    </div>
                </div>
            </div>
            </div>

            <!-- Controles -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselServicos" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselServicos" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
            </button>
        </div>
    </section>
<?php include __DIR__ . '/partials/footer.php'; ?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
