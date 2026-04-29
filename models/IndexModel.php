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
// AVALIAÇÕES (RATINGS) COM COMENTÁRIOS
// ==========================================
function rateEvent($pdo, $user_id, $evento_id, $nota, $comentario = null) {
    $stmt_check = $pdo->prepare("SELECT id FROM Rating WHERE user_id = ? AND event_id = ?");
    $stmt_check->execute([$user_id, $evento_id]);
    $ja_votou = $stmt_check->fetch();

    if ($ja_votou) {
        if ($comentario !== null) {
            $stmt = $pdo->prepare("UPDATE Rating SET value = ?, comment = ? WHERE id = ?");
            return $stmt->execute([$nota, $comentario, $ja_votou['id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE Rating SET value = ? WHERE id = ?");
            return $stmt->execute([$nota, $ja_votou['id']]);
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO Rating (user_id, event_id, value, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $evento_id, $nota, $comentario]);
    }
}

function getEventComments($pdo, $evento_id) {
    $stmt = $pdo->prepare("
        SELECT Rating.*, User.name as user_name 
        FROM Rating 
        JOIN User ON Rating.user_id = User.id 
        WHERE Rating.event_id = ? AND Rating.comment IS NOT NULL AND Rating.comment != ''
        ORDER BY Rating.id DESC
    ");
    $stmt->execute([$evento_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ==========================================
// OBTER DADOS PARA A VIEW COM PESQUISA E FILTROS
// ==========================================
function getEventsWithRatings($pdo, $ordenacao = 'data', $search = '', $tipo = '', $faculty_id = '') {
    $orderBy = "Event.date_time ASC"; 
    if ($ordenacao == 'rating') {
        $orderBy = "media_rating DESC, Event.date_time ASC";
    } elseif ($ordenacao == 'popularidade') {
        $orderBy = "total_votos DESC, Event.date_time ASC";
    }

    $where = [];
    $params = [];

    if (!empty($search)) {
        $where[] = "(Event.name LIKE ? OR Event.location LIKE ? OR Event.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if (!empty($tipo)) {
        $where[] = "Event.type = ?";
        $params[] = $tipo;
    }

    if (!empty($faculty_id)) {
        $where[] = "Tent.faculty_id = ?";
        $params[] = $faculty_id;
    }

    $whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $sql = "SELECT Event.*, Tent.name AS tent_name, Tent.faculty_id,
                   COALESCE(AVG(Rating.value), 0) AS media_rating,
                   COUNT(Rating.id) AS total_votos
            FROM Event
            LEFT JOIN Tent ON Event.tent_id = Tent.id
            LEFT JOIN Rating ON Event.id = Rating.event_id
            $whereSql
            GROUP BY Event.id
            ORDER BY $orderBy";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEventArtistsNames($pdo, $event_id) {
    $stmt = $pdo->prepare("
        SELECT Artist.name FROM Artist 
        INNER JOIN Event_Artist ON Artist.id = Event_Artist.artist_id 
        WHERE Event_Artist.event_id = ?
    ");
    $stmt->execute([$event_id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
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

function getUserRatingComments($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT event_id, comment FROM Rating WHERE user_id = ? AND event_id IS NOT NULL AND comment IS NOT NULL");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

function getEventTypes($pdo) {
    return $pdo->query("SELECT DISTINCT type FROM Event ORDER BY type ASC")->fetchAll(PDO::FETCH_COLUMN);
}
