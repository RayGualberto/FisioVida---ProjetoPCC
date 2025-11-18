<?php
require_once __DIR__ . '/../php/db.php';

// Consulta dos agendamentos
$stmt = $pdo->prepare("
    SELECT 
        id_agenda AS id,
        nome_paciente AS title,
        data AS start,
        hora,
        status
    FROM agenda
");
$stmt->execute();
$agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'partials/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Fisioterapeuta - FisioVida</title>
    <style>
        /* Conteúdo principal */
        .main-content {
        padding: 2rem;
        transition: margin 0.3s ease;
        }
    </style>
</head>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0" data-aos="fade-right">Painel de Fisioterapeuta - FisioVida</h2>
    <span class="badge text-bg-primary" data-aos="fade-left">Perfil: Fisioterapeuta</span>
  </div>
<div class="main-content">
    <?php if (isset($_SESSION['msg'])): ?>
  <div class="alert alert-info">
    <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
  </div>
<?php endif; ?>

    <h2 data-aos="fade-down"><i class="fas fa-calendar-alt"></i> Agenda de Atendimentos</h2>

    <div id='calendar' data-aos="fade-up"></div>
</div>
</html>

<?php include 'partials/footer.php'; ?>

<!-- Scripts do FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        buttonText: {
            today:    'Hoje',
            month:    'Mês',
            week:     'Semana',
            day:      'Dia',
            list:     'Lista'
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
        }
    });

    calendar.render();
});

</script>
