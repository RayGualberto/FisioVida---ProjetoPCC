<?php
require_once 'db.php';

/**
 * Envia uma notificação para um usuário (paciente ou fisioterapeuta)
 * 
 * @param int $remetente_id - ID do usuário que envia
 * @param int $destinatario_id - ID do usuário que recebe
 * @param string $tipo_remetente - 'paciente' ou 'fisioterapeuta'
 * @param string $tipo_destinatario - 'paciente' ou 'fisioterapeuta'
 * @param string $mensagem - Texto da notificação
 * @param int|null $agenda_id - ID da agenda associada (opcional)
 */
function enviarNotificacao($remetente_id, $destinatario_id, $tipo_remetente, $tipo_destinatario, $mensagem, $agenda_id = null) {
    global $conn;

    $sql = "INSERT INTO notificacao 
            (remetente_id, destinatario_id, tipo_remetente, tipo_destinatario, mensagem, agenda_id)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssi", $remetente_id, $destinatario_id, $tipo_remetente, $tipo_destinatario, $mensagem, $agenda_id);
    $stmt->execute();
}
?>
