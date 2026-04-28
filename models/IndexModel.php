<?php
// models/IndexModel.php

// ==========================================
// AGENDA PESSOAL
// ==========================================
function toggleAgenda($pdo, $user_id, $evento_id, $acao) {
    if ($acao == 'add') {
        $stmt = $pdo->prepare("INSERT IGNORE INTO PersonalAgenda (user_id, event_id) VALUES (?, ?)");
        return $stmt->execute([$user_id, $evento_id]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM PersonalAgenda WHERE user_id = ? AND event_id = ?");
        return $stmt->execute([$user_id, $evento_id]);
    }
}

// ==========================================
// AVALIAÇÕES (RATINGS)
// ==========================================
function rateEvent($pdo, $user_id, $evento_id, $nota) {
    // Verifica se o utilizador já votou neste evento
    $stmt_check = $pdo->prepare("SELECT id FROM Rating WHERE user_id = ? AND event_id = ?");
    $stmt_check->execute([$user_id, $evento_id]);
    $ja_votou = $stmt_check->fetch();

    if ($ja_votou) {
        $stmt = $pdo->prepare("UPDATE Rating SET value = ? WHERE id = ?");
        return $stmt->execute([$nota, $ja_votou['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO Rating (user_id, event_id, value) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $evento_id, $nota]);
    }
}

// ==========================================
// OBTER DADOS PARA A VIEW
// ==========================================
function getEventsWithRatings($pdo) {
    $stmt = $pdo->query("
        SELECT 
            Event.*, 
            Tent.name AS tent_name,
            COALESCE(AVG(Rating.value), 0) AS media_rating,
            COUNT(Rating.id) AS total_votos
        FROM Event 
        LEFT JOIN Tent ON Event.tent_id = Tent.id 
        LEFT JOIN Rating ON Event.id = Rating.event_id
        GROUP BY Event.id
        ORDER BY Event.date_time ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserAgendaIds($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT event_id FROM PersonalAgenda WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getUserRatings($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT event_id, value FROM Rating WHERE user_id = ? AND event_id IS NOT NULL");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}
?>