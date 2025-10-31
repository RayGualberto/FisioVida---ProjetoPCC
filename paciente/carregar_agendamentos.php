<?php
require_once '../php/db.php';
session_start();
$idUsuario = $_SESSION['usuario_id'] ?? null;
if(!$idUsuario) exit(json_encode([]));

// Buscar paciente
$stmt = $pdo->prepare("SELECT id_paciente FROM paciente WHERE cpf = (SELECT cpf FROM usuario WHERE id=?)");
$stmt->execute([$idUsuario]);
$idPaciente = $stmt->fetchColumn();

// Buscar agendamentos
$stmt = $pdo->prepare("SELECT id_Agenda,hora,descricao_servico FROM agenda WHERE paciente_id_paciente=?");
$stmt->execute([$idPaciente]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$result = [];
foreach($rows as $r){
    $d = $r['data'] ?? null;
    if($d){
        if(!isset($result[$d])) $result[$d]=[];
        $result[$d][] = [
            "id_agenda"=>$r['id_Agenda'],
            "hora"=>$r['hora'],
            "servico"=>$r['descricao_servico']
        ];
    }
}

echo json_encode($result);
