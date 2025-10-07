<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sistema de Agendamento</title>
  <link rel="stylesheet" href="assets/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  
  <!-- Header -->
  <header class="header">
    <div class="header-container">
      <a href="#" class="logo"><i class="fa-solid fa-car-side"></i> FR Lavação</a>
      
      <nav class="nav" id="nav-menu">
        <a href="#agendar" class="nav-link">Agendar</a>
        <a href="#servicos" class="nav-link">Serviços</a>
        <a href="#diferenciais" class="nav-link">Diferenciais</a>
        <a href="#contato" class="nav-link">Contato</a>
      </nav>
      <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menu">
        <i class="fa fa-bars"></i>
      </button>
    </div>
  </header>
  <!-- Header -->
  <!-- Tela Principal -->
  <main class="tela-central">
    <h1>Bem-vindo à <span class="texto-animado">FISIO VIDA </span>!</h1>
    <p>Sua plataforma prática para agendar suas consultas  com rapidez e conforto.</p>
    <div class="botoes-home">
      <a href="#agendar" class="btn-agendar">
        <i class="fa-regular fa-calendar"></i> Agendar Agora
      </a>
      <a href="#servicos" class="btn-servicos">
        Conheça os Serviços <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
    <button class="btn-seta-scroll" aria-label="Ir para agendamento">
      <span class="seta-animada"><i class="fa-solid fa-angle-down"></i></span>
    </button>
  </main>
  <!-- Tela Principal -->

  <!-- Seção de Serviços -->
  <section id="servicos" class="servicos">
    <h2 class="titulo-servicos">Nossos Serviços</h2>
    <div class="cards-servicos">

      <div class="card-servico">
        <h3>Fisioterapia Ortopédica e Traumatológica</h3>
        <p>Tratamento de lesões musculoesqueléticas, como entorses, fraturas, tendinites e recuperação pós-cirúrgica.</p>
        <button class="preco" disabled>R$ 30,00</button>
      </div>

      <div class="card-servico">
        <h3>Fisioterapia Neurológica</h3>
        <p>Reabilitação de pacientes com AVC, lesões medulares, Parkinson, esclerose múltipla e outras condições neurológicas.</p>
        <button class="preco" disabled>R$ 50,00</button>
      </div>

      <div class="card-servico">
        <h3>Fisioterapia Respiratória</h3>
        <p>Tratamento para doenças pulmonares, auxílio em pós-operatório e melhoria da capacidade respiratória.</p>
        <button class="preco" disabled>R$ 80,00</button>
      </div>

      <div class="card-servico">
        <h3>Fisioterapia Esportiva</h3>
        <p>Prevenção e tratamento de lesões relacionadas à prática esportiva e exercícios físicos.</p>
        <button class="preco" disabled>R$ 25,00</button>
      </div>

      <div class="card-servico">
        <h3>Fisioterapia Pediátrica</h3>
        <p>Atendimento especializado para crianças com atrasos no desenvolvimento motor e outras condições pediátricas.</p>
        <button class="preco" disabled>R$ 60,00</button>
      </div>

      <div class="card-servico">
        <h3>Fisioterapia Geriátrica</h3>
        <p>Melhora da mobilidade, equilíbrio e qualidade de vida para a população idosa.</p>
        <button class="preco" disabled>R$ 40,00</button>
      </div>

      <div class="card-servico">
        <h3>Fisioterapia Dermatofuncional</h3>
        <p>Tratamentos estéticos e terapêuticos para pele, cicatrizes, celulite e edemas.</p>
        <button class="preco" disabled>R$ 40,00</button>
      </div>

      <div class="card-servico">
        <h3>Fisioterapia Uroginecológica e Obstétrica </h3>
        <p>Tratamento de disfunções do assoalho pélvico, incontinência urinária e preparação para o parto.</p>             
        <button class="preco" disabled>R$ 40,00</button>
      </div>

      <div class="card-servico">
        <h3>Fisioterapia Cardiorrespiratória</h3>
        <p>Reabilitação após infarto, cirurgias cardíacas e doenças do coração.</p>             
        <button class="preco" disabled>R$ 40,00</button>
      </div>

      <div class="card-servico">
        <h3>Fisioterapia Aquática (Hidroterapia)</h3>
        <p>Uso da água para exercícios terapêuticos e reabilitação com menor impacto.</p>             
        <button class="preco" disabled>R$ 40,00</button>
      </div>

    </div>
  </section>
    <!-- Seção de Serviços -->

  <!-- Seção Por Que Escolher -->
<section class="porque-escolher" id="diferenciais">

  <h2>Nossos Diferenciais</h2>

  <div class="diferenciais">
    <div class="diferencial-item">
      <div class="icon-circle">
        <i class='bx bx-calendar-check'></i>
      </div>

      <strong>Agendamento Fácil</strong>

      <p>Sistema online prático e intuitivo para marcar seu horário</p>
    </div>

    <div class="diferencial-item">
      <div class="icon-circle">
        <i class='bx bx-leaf'></i>
      </div>

      <strong>Produtos Ecológicos</strong>

      <p>Temos profissionais dedicados e qualificadosf</p>

    </div>

    <div class="diferencial-item">
      <div class="icon-circle">
        <i class='bx bx-user-circle'></i>
      </div>

      <strong>Atendimento Personalizado</strong>
      <p>Cada cliente recebe atenção especial e cuidado individual</p>
    </div>
  </div>
</section>
  <!-- Seção Por Que Escolher -->

  <!-- Tela de Agendamento -->
<section id="agendar" class="agendamento container">
  <h2>Agendar Serviço</h2>
  <p class="subtitulo">Complete o agendamento em poucos passos</p>

  <!-- Barra de Progresso -->
  <div class="progresso">
    <div class="passo passo-ativo" data-step="1">
      <div class="circulo">1</div>
      <span>Serviços</span>
    </div>
    <div class="linha"></div>
    <div class="passo" data-step="2">
      <div class="circulo">2</div>
      <span>Dados</span>
    </div>
    <div class="linha"></div>
    <div class="passo" data-step="3">
      <div class="circulo">3</div>
      <span>Agendamento</span>
    </div>
  </div>

  <!-- Formulário de Agendamento -->
  <form class="form-agendamento" action="cliente/agendar.php" method="POST">
    <!-- Etapa 1 - Serviços -->
    <div class="etapa etapa-ativa" data-step="1">
      <h3 class="titulo-servicos">Escolha os Serviços</h3>

      <div class="servicos-lista">
        <!-- Linha 1 -->
        <div class="servicos-linha">
          <!-- Lavagem Simples -->
          <label class="servico-card">
            <input type="checkbox" name="servicos[]" value="1" data-duracao="30" data-preco="30.00">
            <div class="servico-conteudo">
              <h4>Fisioterapia Ortopédica e Traumatológica</h4>
              <p>Tratamento de lesões musculoesqueléticas, como entorses, fraturas, tendinites e recuperação pós-cirúrgica.</p>
              <div class="servico-info">
                <span class="duracao">30 min</span>
                <span class="preco">R$ 30</span>
              </div>
            </div>
          </label>

          <!-- Lavagem Completa -->
          <label class="servico-card">
            <input type="checkbox" name="servicos[]" value="2" data-duracao="60" data-preco="50.00">
            <div class="servico-conteudo">
              <h4>Fisioterapia Neurológica</h4>
              <p>Reabilitação de pacientes com AVC, lesões medulares, Parkinson, esclerose múltipla e outras condições neurológicas.</p>
              <div class="servico-info">
                <span class="duracao">60 min</span>
                <span class="preco">R$ 50</span>
              </div>
            </div>
          </label>
        </div>

        <!-- Linha 2 -->
        <div class="servicos-linha">
          <!-- Higienização Interna -->
          <label class="servico-card">
            <input type="checkbox" name="servicos[]" value="3" data-duracao="90" data-preco="80.00">
            <div class="servico-conteudo">
              <h4>Fisioterapia Respiratória</h4>
              <p>Tratamento para doenças pulmonares, auxílio em pós-operatório e melhoria da capacidade respiratória.</p>
              <div class="servico-info">
                <span class="duracao">90 min</span>
                <span class="preco">R$ 80</span>
              </div>
            </div>
          </label>

          <!-- Limpeza de Rodas -->
          <label class="servico-card">
            <input type="checkbox" name="servicos[]" value="4" data-duracao="20" data-preco="25.00">
            <div class="servico-conteudo">
              <h4>Fisioterapia Esportiva</h4>
              <p>Prevenção e tratamento de lesões relacionadas à prática esportiva e exercícios físicos.</p>
              <div class="servico-info">
                <span class="duracao">20 min</span>
                <span class="preco">R$ 25</span>
              </div>
            </div>
          </label>
        </div>

        <!-- Linha 3 -->
        <div class="servicos-linha">
          <!-- Enceramento -->
          <label class="servico-card">
            <input type="checkbox" name="servicos[]" value="5" data-duracao="45" data-preco="60.00">
            <div class="servico-conteudo">
              <h4>Enceramento</h4>
              <p>Proteção e brilho duradouro</p>
              <div class="servico-info">
                <span class="duracao">45 min</span>
                <span class="preco">R$ 60</span>
              </div>
            </div>
          </label>

          <!-- Lavagem de Motor -->
          <label class="servico-card">
            <input type="checkbox" name="servicos[]" value="6" data-duracao="30" data-preco="40.00">
            <div class="servico-conteudo">
              <h4>Lavagem de Motor</h4>
              <p>Limpeza do compartimento do motor</p>
              <div class="servico-info">
                <span class="duracao">30 min</span>
                <span class="preco">R$ 40</span>
              </div>
            </div>
          </label>
        </div>
      </div>

      <!-- Resumo dos Serviços Selecionados -->
      <div class="resumo-simples">
        <h4>Resumo do Agendamento</h4>
        <div class="resumo-linha">
          <span>Serviços:</span>
          <strong id="resumo-parcial-servicos">Nenhum selecionado</strong>
        </div>
        <div class="resumo-linha">
          <span>Tempo estimado:</span>
          <strong id="resumo-parcial-duracao">0 min</strong>
        </div>
        <div class="resumo-linha">
          <span>Valor total:</span>
          <strong id="resumo-parcial-total">R$ 0,00</strong>
        </div>
      </div>

      <div class="botoes-navegacao">
        <button type="button" class="btn-voltar" disabled>Anterior</button>
        <button type="button" class="btn-avancar">Próximo</button>
      </div>
    </div>

    <!-- Etapa 2 - Dados -->
    <div class="etapa" data-step="2">
      <h3>Seus Dados</h3>

      <div class="dados-grid">
        <label>
          Nome Completo *
          <input type="text" name="nome_cliente" placeholder="Seu nome completo" required />
        </label>

        <label>
          Telefone *
          <input type="tel" name="telefone" placeholder="(11) 99999-9999" required />
        </label>

        <label>
          Email *
          <input type="email" name="email" placeholder="seu@email.com" required />
        </label>

        <label>
          Modelo do Carro *
          <input type="text" name="modelo" placeholder="Ex: Honda Civic 2020" required />
        </label>

        <label>
          Placa do Veículo
          <input type="text" name="placa" placeholder="ABC-1234" />
        </label>
      </div>

      <div class="botoes-navegacao">
        <button type="button" class="btn-voltar" disabled>Anterior</button>
        <button type="button" class="btn-avancar" id="avancarParaDados">Próximo</button>
      </div>
    </div>

    <!-- Etapa 3 - Agendamento -->
    <!-- Etapa 3 - Data/Horário e Finalização -->
    <div class="etapa etapa-agendamento" data-step="3">
      <h3>Data e Horário</h3>
      
      <div class="agendamento-grid">
        <div class="agendamento-group">
          <label>
            <strong>Data *</strong>
            <input type="date" name="data" required class="input-data" />
          </label>
        </div>
        
        <div class="agendamento-group">
          <label>
            <strong>Horário *</strong>
            <select name="horario" required class="input-horario">
              <option value="" disabled selected>Selecione um horário</option>
              <option value="08:00">08:00</option>
              <option value="09:00">09:00</option>
              <option value="10:00">10:00</option>
              <option value="11:00">11:00</option>
              <option value="13:00">13:00</option>
              <option value="14:00">14:00</option>
              <option value="15:00">15:00</option>
              <option value="16:00">16:00</option>
              <option value="17:00">17:00</option>
              <option value="18:00">18:00</option>
              <!-- Mais horários podem ser adicionados aqui -->
            </select>
          </label>
        </div>
      </div>
      
      <div class="agendamento-group endereco-group">
        <label>
          <strong>Endereço para Atendimento *</strong>
          <input 
            type="text" 
            name="endereco" 
            placeholder="Rua, número, bairro, cidade" 
            required 
            class="input-endereco" 
          />
        </label>
      </div>
      
      <div class="agendamento-group">
        <label>
          <strong>Observações:</strong>
          <textarea 
            name="observacoes" 
            placeholder="Informações adicionais sobre o serviço..." 
            class="input-observacoes"
          ></textarea>
        </label>
      </div>
      
      <div class="resumo-final">
        <h4>Resumo do Agendamento</h4>
        <div class="resumo-linha">
          <span>Serviços:</span>
          <span id="resumo-servicos">1 selecionado(s)</span>
        </div>

        <div class="resumo-linha">
          <span>Duração estimada:</span>
          <span id="resumo-duracao">30 minutos</span>
        </div>

        <div class="resumo-linha total">
          <span>Total:</span>
          <strong id="resumo-total">R$ 30,00</strong>
        </div>
      </div>
      
      <div class="botoes-finalizar">
        <button type="button" class="btn-voltar">Anterior</button>
        <button type="submit" class="btn-finalizar">Finalizar Agendamento</button>
      </div>

    </div>
  </form>
</section>
  <!-- Tela de Agendamento -->  

  <div class="menu">
    <a href="cliente/agendar.php">📅 Agendar Consulta</a>
    <a href="admin/listar_agendamentos.php">📋 Listar Agendamentos</a>
    <a href="admin/cadastrar_servico.php">➕ Cadastrar Serviço</a>
    <a href="admin/listar_servicos.php">🛠️ Gerenciar Serviços</a>
  </div>


    <!-- Footer-->  
    <footer class="footer">
  <div class="footer-container">
    <div class="footer-col">
      <h3><i class="fa-solid fa-car-side"></i> Fisio Vida</h3>
      <p>Conectamos você a fisioterapeutas qualificados, oferecendo praticidade no agendamento e cuidado humano em cada sessão.</p>
    </div>
    <div class="footer-col" id="contato">
      <h4>Contato</h4>
      <p><i class="fa-solid fa-phone"></i> (11) 99999-9999</p>
      <p><i class="fa-solid fa-envelope"></i> contato@frlavacao.com</p>
      <p><i class="fa-solid fa-location-dot"></i> Rua da Lavação, 123</p>
    </div>
    <div class="footer-col">
      <h4>Serviços</h4>
      <p></p>
      <p>Fisioterapia Ortopédica e Traumatológica</p>
      <p>Fisioterapia Neurológica</p>
      <p>HigienFisioterapia Respiratória</p>
      <p>Fisioterapia Esportiva</p>
      <p>Fisioterapia Pediátrica</p>
      <p>Fisioterapia Geriátrica</p>
      <p>Fisioterapia Dermatofuncional</p>
      <p>Fisioterapia Uroginecológica e Obstétrica</p>
      <p>Fisioterapia Cardiorrespiratória
      </p>Fisioterapia Aquática (Hidroterapia)</p>

    </div>
    <div class="footer-col">
      <h4>Horários</h4>
      <p>Segunda - Sexta: 8h às 18h</p>
      <p>Sábado: 8h às 16h</p>
      <p>Domingo: Fechado</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 Fisio Vida. Todos os direitos reservados.</p>
  </div>
</footer>
      <!-- Footer-->  

  <script src="script.js"></script>
  <script>
    // Menu mobile toggle
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');
    menuToggle.addEventListener('click', () => {
      navMenu.classList.toggle('nav-active');
      menuToggle.classList.toggle('active');
    });
    // Fechar menu ao clicar em link
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('nav-active');
        menuToggle.classList.remove('active');
      });
    });
    // Scroll suave para âncoras
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if(target) {
          e.preventDefault();
          window.scrollTo({
            top: target.offsetTop - 80,
            behavior: 'smooth'
          });
        }
      });
    });
  </script>
</body>
</html>
<!-- Comentário de teste para novo commit -->
