<?php
// models/AdminModel.php

// === ARTISTAS ===
function getArtists($pdo) {
    return $pdo->query("SELECT * FROM Artist ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function deleteArtist($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Artist WHERE id = ?");
    return $stmt->execute([$id]);
}

function addArtist($pdo, $nome, $genero, $pais, $biografia) {
    $stmt = $pdo->prepare("INSERT INTO Artist (name, musical_genre, country, short_biography) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nome, $genero, $pais, $biografia]);
}

// === EVENTOS ===
function getEvents($pdo) {
    return $pdo->query("SELECT Event.*, Tent.name AS tent_name FROM Event LEFT JOIN Tent ON Event.tent_id = Tent.id ORDER BY date_time ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function deleteEvent($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Event WHERE id = ?");
    return $stmt->execute([$id]);
}

function addEvent($pdo, $tent_id, $nome, $descricao, $data_hora, $localizacao, $tipo) {
    $stmt = $pdo->prepare("INSERT INTO Event (tent_id, name, description, date_time, location, type) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$tent_id, $nome, $descricao, $data_hora, $localizacao, $tipo]);
}

// === BARRACAS & FACULDADES ===
function getTents($pdo) {
    return $pdo->query("SELECT * FROM Tent ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function getDetailedTents($pdo) {
    return $pdo->query("SELECT Tent.*, Faculty.acronym AS fac_acronym FROM Tent LEFT JOIN Faculty ON Tent.faculty_id = Faculty.id ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function getFaculties($pdo) {
    return $pdo->query("SELECT * FROM Faculty ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function deleteTent($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Tent WHERE id = ?");
    return $stmt->execute([$id]);
}

function addTent($pdo, $faculty_id, $nome, $localizacao, $abertura, $fecho, $descricao) {
    $stmt = $pdo->prepare("INSERT INTO Tent (faculty_id, name, location, opening_hours, closing_hours, description) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$faculty_id, $nome, $localizacao, $abertura, $fecho, $descricao]);
}
?>