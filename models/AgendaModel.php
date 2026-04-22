<?php
// models/AgendaModel.php

// Apaga um evento da agenda pessoal do utilizador
function removerEventoDaAgenda($pdo, $user_id, $evento_id) {
    $stmt = $pdo->prepare("DELETE FROM PersonalAgenda WHERE user_id = ? AND event_id = ?");
    return $stmt->execute([$user_id, $evento_id]);
}

// Vai buscar todos os eventos que estão na agenda de um utilizador específico
function getEventosDaAgenda($pdo, $user_id) {
    $stmt = $pdo->prepare("
        SELECT Event.*, Tent.name AS tent_name 
        FROM Event 
        INNER JOIN PersonalAgenda ON Event.id = PersonalAgenda.event_id 
        LEFT JOIN Tent ON Event.tent_id = Tent.id 
        WHERE PersonalAgenda.user_id = ?
        ORDER BY Event.date_time ASC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>