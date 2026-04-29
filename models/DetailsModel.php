<?php
// models/DetailsModel.php

function getArtistById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM Artist WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getTentById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT Tent.*, Faculty.name AS fac_name, Faculty.acronym AS fac_acronym, Faculty.colour 
                           FROM Tent 
                           JOIN Faculty ON Tent.faculty_id = Faculty.id 
                           WHERE Tent.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getEventById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT Event.*, Tent.name AS tent_name, Tent.id AS tent_id_real 
                           FROM Event 
                           LEFT JOIN Tent ON Event.tent_id = Tent.id 
                           WHERE Event.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getEventArtists($pdo, $event_id) {
    $stmt = $pdo->prepare("SELECT Artist.* FROM Artist INNER JOIN Event_Artist ON Artist.id = Event_Artist.artist_id WHERE Event_Artist.event_id = ?");
    $stmt->execute([$event_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEventRatingsSummary($pdo, $event_id) {
    $stmt = $pdo->prepare("
        SELECT COALESCE(AVG(value),0) as media, COUNT(*) as total FROM Rating WHERE event_id = ?
    ");
    $stmt->execute([$event_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getEventComments($pdo, $event_id) {
    $stmt = $pdo->prepare("
        SELECT Rating.*, User.name as user_name 
        FROM Rating 
        JOIN User ON Rating.user_id = User.id 
        WHERE Rating.event_id = ? AND Rating.comment IS NOT NULL AND Rating.comment != ''
        ORDER BY Rating.id DESC
    ");
    $stmt->execute([$event_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
