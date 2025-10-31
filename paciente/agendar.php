<?php 
require_once '../php/db.php';
session_start();

// PEGAR ID DO USUÁRIO LOGADO
$idUsuario = $_SESSION['usuario_id'] ?? null;
$nomeUsuario = $_SESSION['nome'] ?? 'Paciente';

// BUSCAR ID DO PACIENTE
$stmt = $pdo->prepare("SELECT id_paciente, nome FROM paciente WHERE cpf = (SELECT cpf FROM usuario WHERE id = ?)");
$stmt->execute([$idUsuario]);
$paciente = $stmt->fetch();

$idPaciente = $paciente["id_paciente"] ?? null;

// BUSCAR SERVIÇOS ATIVOS
$servicos = $pdo->query("SELECT id_servico, nome_servico, descricao_servico FROM servico WHERE status = 'Ativo'")->fetchAll();

include __DIR__ . '/partials/header.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Consulta</title>

    <style>
.content-wrapper {
    margin-top: 120px;
    display: flex;
    gap: 30px;
    padding: 20px;
}

/* CALENDÁRIO */
.calendar {
    width: 70%;
}

#calendarTable {
    width: 100%;
    border-collapse: separate;
    border-spacing: 5px;
    font-size: 20px;
    background: #ffffff; /* fundo branco */
    color: #000; /* texto preto */
}

#calendarTable th {
    padding: 15px;
    font-size: 18px;
    background: linear-gradient(45deg,#4caf50,#1de9b6);
    border-radius: 10px;
    color: #fff;
}

#calendarTable td {
    padding: 30px 0;
    text-align: center;
    cursor: pointer;
    background: #f9f9f9; /* células claras */
    border-radius: 12px;
    position: relative;
    transition: transform 0.15s, background 0.15s;
    color: #000; /* texto preto */
}

#calendarTable td:hover {
    transform: scale(1.05);
    background: #e0ffe0; /* hover suave */
}

/* DIA ATUAL — marcador circular */
.today::after {
    content: '';
    width: 20px;
    height: 20px;
    border: 2px solid #000000ff;
    border-radius: 50%;
    position: absolute;
    top: 5px;
    right: 5px;
}

/* DIA SELECIONADO */
.selected {
    background: #00e676 !important;
    color: #000;
}

/* DIA COM AGENDAMENTO */
.has-agenda::before {
    content: '';
    width: 12px;
    height: 12px;
    background: #ff3d00;
    border-radius: 50%;
    position: absolute;
    bottom: 5px;
    left: 50%;
    transform: translateX(-50%);
}

/* CONTROLES DO MÊS */
.month-selector {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    align-items: center;
}

.month-selector select {
    font-size: 16px;
    padding: 6px;
}

/* FORM BOX */
.form-box {
    width: 30%;
    padding: 20px;
    background: #ffffff; /* fundo branco */
    border-radius: 15px;
    display: none;
    box-shadow: 0 0 15px rgba(0,255,128,0.3);
}

.form-box input,
.form-box select {
    width: 100%;
    padding: 10px;
    margin-bottom: 12px;
    background: #f2f2f2; /* campos claros */
    border: none;
    color: #000;
    border-radius: 8px;
}

.form-box input:readonly {
    opacity: 0.7;
}
.month-controls {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.month-controls button {
    padding: 8px 16px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    background: #4caf50;
    color: #fff;
    transition: background 0.2s;
}

.month-controls button:hover {
    background: #45a045;
}
/* BOTÕES DE MÊS */
.month-controls { display:flex; justify-content:space-between; margin-bottom:20px; }
.month-controls button { padding:8px 16px; font-size:16px; border:none; border-radius:8px; cursor:pointer; background:#4caf50; color:#fff; transition:background 0.2s; }
.month-controls button:hover { background:#45a045; }

/* FORM BOX */
.form-box { width:30%; padding:20px; background:#ffffff; border-radius:15px; display:none; box-shadow:0 0 15px rgba(0,255,128,0.3); }
.form-box input, .form-box select { width:100%; padding:10px; margin-bottom:12px; background:#f2f2f2; border:none; color:#000; border-radius:8px; }
.form-box input:readonly { opacity:0.7; }
</style>
</head>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0">Agendar Consulta - FisioVida</h2>
    <span class="badge text-bg-primary">Perfil: paciente</span>
</div>

<div class="content-wrapper">

    <!-- CALENDÁRIO -->
    <div class="calendar">
        <h2>Escolha o dia</h2>
        <div class="month-controls">
            <button onclick="mesAnterior()">&#8592; Mês Anterior</button>
            <span id="mesAnoLabel" style="font-weight:bold; font-size:18px;"></span>
            <button onclick="mesSeguinte()">Mês Seguinte &#8594;</button>
        </div>
        <table id="calendarTable"></table>
    </div>

    <!-- FORMULÁRIO LATERAL -->
    <div class="form-box" id="formBox">
        <h3>Agendar Consulta</h3>
        <label>Paciente:</label>
        <input type="text" value="<?= $nomeUsuario ?>" readonly>
        <label>Data escolhida:</label>
        <input type="text" id="selectedDate" readonly>
        <label>Serviço:</label>
        <select id="servico">
            <option value="">Selecione um serviço</option>
            <?php foreach ($servicos as $s): ?>
            <option value="<?= $s['id_servico'] ?>" data-descricao="<?= htmlspecialchars($s['descricao_servico']) ?>"><?= $s['nome_servico'] ?></option>
            <?php endforeach; ?>
        </select>
        <label>Horário:</label>
        <select id="horario">
            <option value="">Selecione</option>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
            <option value="14:00">14:00</option>
            <option value="15:00">15:00</option>
            <option value="16:00">16:00</option>
            <option value="17:00">17:00</option>
            <option value="18:00">18:00</option>
        </select>
        <button class="btn btn-outline-primary" onclick="salvarAgendamento()">Agendar</button>
        <button class="btn btn-outline-danger" onclick="document.getElementById('formBox').style.display='none'">Cancelar</button>
        <div id="agendaList" style="margin-top:15px;"></div>
    </div>

</div>

<script>
let selectedCell = null;
let selectedDate = null;
let agendamentos = {};
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

function atualizarLabelMesAno() {
    const label = document.getElementById("mesAnoLabel");
    const nomeMes = new Date(currentYear, currentMonth).toLocaleString('pt-BR', { month:'long' });
    label.textContent = `${nomeMes.charAt(0).toUpperCase() + nomeMes.slice(1)} ${currentYear}`;
}

function mesAnterior() {
    currentMonth--;
    if(currentMonth<0){ currentMonth=11; currentYear--; }
    gerarCalendario();
}

function mesSeguinte() {
    currentMonth++;
    if(currentMonth>11){ currentMonth=0; currentYear++; }
    gerarCalendario();
}

function carregarAgendamentos() {
    fetch('carregar_agendamentos.php')
    .then(res=>res.json())
    .then(data=>{
        agendamentos=data;
        gerarCalendario();
    });
}

function gerarCalendario() {
    atualizarLabelMesAno();
    const table = document.getElementById("calendarTable");
    table.innerHTML="";
    const primeiroDia=new Date(currentYear,currentMonth,1);
    const ultimoDia=new Date(currentYear,currentMonth+1,0);
    const hoje=new Date();
    const diaHoje=hoje.getDate();
    const mesHoje=hoje.getMonth();
    const anoHoje=hoje.getFullYear();
    const diasSemana=["Dom","Seg","Ter","Qua","Qui","Sex","Sab"];
    let header="<tr>";
    for(let d of diasSemana) header+=`<th>${d}</th>`;
    header+="</tr>";
    table.innerHTML+=header;
    let linha="<tr>";
    for(let i=0;i<primeiroDia.getDay();i++) linha+="<td></td>";
    for(let dia=1;dia<=ultimoDia.getDate();dia++){
        if((primeiroDia.getDay()+dia-1)%7===0) linha+="</tr><tr>";
        const dataFormatada=`${currentYear}-${currentMonth+1}-${dia}`;
        let cellClass="";
        if(dia===diaHoje && currentMonth===mesHoje && currentYear===anoHoje) cellClass+="today ";
        if(selectedDate===dataFormatada) cellClass+="selected ";
        if(agendamentos[dataFormatada]) cellClass+="has-agenda";
        linha+=`<td class="${cellClass}" onclick="selecionarDia(this,'${dataFormatada}')">${dia}</td>`;
    }
    linha+="</tr>";
    table.innerHTML+=linha;
}

function selecionarDia(celula,data){
    selectedDate=data;
    if(selectedCell) selectedCell.classList.remove("selected");
    celula.classList.add("selected");
    selectedCell=celula;
    document.getElementById("selectedDate").value=data;
    document.getElementById("formBox").style.display="block";
    const listDiv=document.getElementById("agendaList");
    listDiv.innerHTML="";
    if(agendamentos[data]){
        agendamentos[data].forEach(a=>{
            const btn=document.createElement("button");
            btn.className="btn btn-outline-danger btn-sm mb-1";
            btn.textContent=`Cancelar ${a.hora} - ${a.servico}`;
            btn.onclick=()=>cancelarAgendamento(a.id_agenda);
            listDiv.appendChild(btn);
            listDiv.appendChild(document.createElement("br"));
        });
    }
}

function salvarAgendamento(){
    const data=document.getElementById("selectedDate").value;
    const servico=document.getElementById("servico").value;
    const horario=document.getElementById("horario").value;
    const servicoSelect=document.getElementById("servico");
    const descricaoServico=servicoSelect.selectedOptions[0].dataset.descricao;
    if(!data || !servico || !horario){ alert("Preencha todos os campos!"); return; }
    const formData=new FormData();
    formData.append("data",data);
    formData.append("servico",servico);
    formData.append("horario",horario);
    formData.append("descricao_servico",descricaoServico);
    fetch("salvar_agendamento.php",{method:"POST",body:formData})
    .then(r=>r.text())
    .then(r=>{ alert(r); carregarAgendamentos(); });
}

function cancelarAgendamento(id_agenda){
    if(!confirm("Deseja realmente cancelar este agendamento?")) return;
    const formData=new FormData();
    formData.append("id_agenda",id_agenda);
    fetch("cancelar_agendamento.php",{method:"POST",body:formData})
    .then(r=>r.text())
    .then(r=>{ alert(r); carregarAgendamentos(); });
}

// Inicialização
carregarAgendamentos();
</script>
</script>
</html>
