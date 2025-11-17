    <?php
    require_once __DIR__ . '/../php/db.php';
    session_start();

    $idUsuario = $_SESSION['usuario_id'];

    // Buscar nome do paciente logado
    $stmtPaciente = $pdo->prepare("SELECT nome FROM paciente WHERE id_paciente = ?");
    $stmtPaciente->execute([$idUsuario]);
    $paciente = $stmtPaciente->fetch(PDO::FETCH_ASSOC);

    // Evitar warning caso paciente não seja encontrado
    $nomePaciente = $paciente['nome'] ?? 'Paciente não encontrado';

    // Buscar serviços ativos
    $stmtServicos = $pdo->query("SELECT id_servico, nome_servico FROM servico WHERE status = 'Ativo'");
    $servicos = $stmtServicos->fetchAll(PDO::FETCH_ASSOC);

    // Consulta dos agendamentos
    $stmt = $pdo->prepare("
        SELECT 
            id_agenda AS id,
            descricao_servico AS title,
            data AS start,
            hora,
            status
        FROM agenda
    ");
    $stmt->execute();
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php include __DIR__ . '../partials/header.php' ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Fisioterapeuta - FisioVida</title>
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        .main-content { padding: 2rem;}
        #calendar {
            transition: all 0.5s ease;
            width: 100%;
            float: left;
        }
        #calendar.reduzido {
            width: 65%;
        }
        #formAgendamento {
            display: none;
            width: 500px;
            height: 500px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border-radius: 10px;
            position: absolute;
            top: 200px;
            right: 60px;
            z-index: 999;
            transition: all 0.5s ease;
        }
        #formAgendamento button.fechar {
            float: right;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }
         .btn-primary {
         background-color: #0b8ecb;
        }
    </style>
    </head>

    <div class="main-content">
        <h2><i class="fas fa-calendar-alt"></i> Agenda de Atendimentos</h2>
        <div id="calendar" data-aos="fade-up"></div>

        <div id="formAgendamento">
            <button class="fechar" onclick="fecharFormulario()">×</button>
            <h5>Agendar Consulta</h5>

            <label>Paciente:</label>
            <input type="text" class="form-control mb-2" value="<?= htmlspecialchars($nomePaciente) ?>" disabled>

            <label>Data Selecionada:</label>
            <input type="text" id="dataSelecionada" class="form-control mb-2" readonly>

            <label>Serviço:</label>
            <select id="servico" class="form-control mb-2">
                <option value="">Selecione...</option>
                <?php foreach ($servicos as $s): ?>
                    <option value="<?= $s['id_servico'] ?>"><?= htmlspecialchars($s['nome_servico']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Horário:</label>
            <select id="horario" class="form-control mb-2">
                <?php for ($h = 8; $h <= 18; $h++): ?>
                    <option value="<?= $h ?>:00"><?= $h ?>:00</option>
                <?php endfor; ?>
            </select>

            <button class="btn btn-primary w-100" onclick="salvarAgendamento(); fecharFormulario();">Agendar</button>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
    let calendar; // Instância global do calendário

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const form = document.getElementById('formAgendamento');
        const dataInput = document.getElementById('dataSelecionada');

        calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'pt-br',
            initialView: 'dayGridMonth',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            buttonText: {
                today: 'Hoje'
            },
            
            events: <?php echo json_encode($agendamentos); ?>,
            eventColor: '#007bff',
            eventDisplay: 'block',
            eventContent: function(arg) {
                let statusColor = {
                    'pendente': 'orange',
                    'confirmado': 'green',
                    'remarcado': 'blue',
                    'recusado': 'red'
                }[arg.event.extendedProps.status] || 'gray';

                return {
                    html: `
                        <div style="display:flex;align-items:center;gap:5px;">
                            <span style="width:8px;height:8px;border-radius:50%;background:${statusColor};"></span>
                            <b>${arg.event.title}</b>
                        </div>
                    `
                };
            },
            
            eventClick: function(info) {
                const evento = info.event;
                alert(
                    `📅 Agendamento:\n\n` +
                    `Paciente: ${evento.title}\n` +
                    `Data: ${new Date(evento.start).toLocaleDateString()}\n` +
                    `Hora: ${evento.extendedProps.hora || 'Não informada'}\n` +
                    `Status: ${evento.extendedProps.status}`
                );
            },

            dateClick: function(info) {
                // Mostrar formulário e preencher data
                form.style.display = 'block';
                dataInput.value = info.dateStr;

                // Reduzir calendário para abrir espaço
                calendarEl.classList.add('reduzido');
            }
        });

        calendar.render();
    });

    // Fechar formulário e restaurar calendário
    function fecharFormulario() {
        const calendarEl = document.getElementById('calendar');
        const form = document.getElementById('formAgendamento');
        form.style.display = 'none';
        calendarEl.classList.remove('reduzido');
    }

    // Salvar agendamento
    function salvarAgendamento() {
        const data = document.getElementById('dataSelecionada').value;
        const servico = document.getElementById('servico').value;
        const horario = document.getElementById('horario').value;
        

        if (!data || !servico || !horario) {
            alert('Por favor, preencha todos os campos.');
            return;
        }

        fetch('salvar_agendamento.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `data=${encodeURIComponent(data)}&hora=${encodeURIComponent(horario)}&servico=${encodeURIComponent(servico)}`
        })
        .then(res => res.json())
        .then(result => {
            Toastify({
            text: result.msg,
            duration: 3000,           // Duração da notificação
            close: true,               // Botão de fechar
            gravity: "top",            // top ou bottom
            position: "right",         // left, center, right
            backgroundColor: result.success ? "linear-gradient(to right, #0b8ecb)" : "linear-gradient(to right, #ff5f6d)",
            }).showToast();

            if (result.success) {
                fecharFormulario();

                // Adiciona evento no calendário usando instância global
                calendar.addEvent({
                    id: result.id,
                    title: result.descricao_servico,
                    start: `${result.data}T${result.hora}`, // ISO 8601 completo
                    extendedProps: { status: 'pendente' },
                    allDay: false
                });

                // Opcional: mudar para a data do novo evento
                calendar.gotoDate(result.data);
            }
        })
        .catch(err => {
            console.error(err);
            Toastify({
                text: 'Ocorreu um erro ao salvar o agendamento.',
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"
            }).showToast();
        });
    }
    </script>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <script>
    // ===== ANIMAÇÃO DE ABERTURA DO FORMULÁRIO =====
    const form = document.getElementById("formAgendamento");
    const calendarEl = document.getElementById("calendar");

    // Sobrescreve o abrir formulário do FullCalendar
    document.addEventListener("click", function(e) {
        if (e.target.closest(".fc-daygrid-day")) {
            abrirFormulario();
        }
    });

    function abrirFormulario() {
        form.style.display = "block";

        gsap.fromTo(form,
            { opacity: 0, scale: 0.85, y: 30 },
            {
                opacity: 1,
                scale: 1,
                y: 0,
                duration: 0.35,
                ease: "power2.out"
            }
        );

        // Animar inputs do formulário
        gsap.from("#formAgendamento .form-control, #formAgendamento select, #formAgendamento button", {
            opacity: 0,
            y: 20,
            stagger: 0.08,
            duration: 0.3,
            delay: 0.15,
            ease: "power2.out"
        });
    }


    // ===== ANIMAÇÃO AO FECHAR O FORMULÁRIO =====
    function fecharFormulario() {
        gsap.to(form, {
            opacity: 0,
            scale: 0.8,
            y: 20,
            duration: 0.25,
            ease: "power1.inOut",
            onComplete: () => {
                form.style.display = "none";
            }
        });

        // restaurar calendário
        calendarEl.classList.remove("reduzido");
    }
    AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
    </script>
    </html>
