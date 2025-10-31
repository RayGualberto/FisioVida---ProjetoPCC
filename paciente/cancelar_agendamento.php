<?php
require_once '../php/db.php';
session_start();

$idAgenda = $_POST['id_agenda'] ?? null;
if(!$idAgenda){
    echo "Erro: agendamento inválido";
    exit;
}

// Opcional: verificar se o agendamento pertence ao paciente logado
$stmt = $pdo->prepare("DELETE FROM agenda WHERE id_Agenda=?");
$stmt->execute([$idAgenda]);

echo "Agendamento cancelado com sucesso!";
