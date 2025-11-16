<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../php/db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        $stmt = $pdo->prepare("UPDATE agenda SET status = 'recusado' WHERE id_Agenda = ?");
        $stmt->execute([$id]);

        // 🔥 Mensagem vermelha
        $_SESSION['msg'] = "Sessão cancelada!";
        $_SESSION['msg_tipo'] = "erro";

    } catch (PDOException $e) {

        $_SESSION['msg'] = "⚠️ Erro ao recusar: " . $e->getMessage();
        $_SESSION['msg_tipo'] = "erro"; // mantém vermelho também
    }
}

header("Location: agendamentos.php");
exit;
