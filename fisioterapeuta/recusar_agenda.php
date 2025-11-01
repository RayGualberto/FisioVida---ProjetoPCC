<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../php/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $pdo->prepare("UPDATE agenda SET status = 'recusado' WHERE id_Agenda = ?");
        $stmt->execute([$id]);
        $_SESSION['msg'] = "❌ Agendamento recusado com sucesso.";
    } catch (PDOException $e) {
        $_SESSION['msg'] = "⚠️ Erro ao recusar: " . $e->getMessage();
    }
}

header("Location: agenda.php");
exit;
