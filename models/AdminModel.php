<?php
// models/AdminModel.php

// === ARTISTAS ===
function getArtists($pdo) {
    return $pdo->query("SELECT * FROM Artist ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function getArtistById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM Artist WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteArtist($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Artist WHERE id = ?");
    return $stmt->execute([$id]);
}

function addArtist($pdo, $nome, $genero, $pais, $biografia, $imagem = 'default.jpg') {
    $stmt = $pdo->prepare("INSERT INTO Artist (name, musical_genre, country, short_biography, image) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$nome, $genero, $pais, $biografia, $imagem]);
}

function updateArtist($pdo, $id, $nome, $genero, $pais, $biografia, $imagem = null) {
    if ($imagem) {
        $stmt = $pdo->prepare("UPDATE Artist SET name=?, musical_genre=?, country=?, short_biography=?, image=? WHERE id=?");
        return $stmt->execute([$nome, $genero, $pais, $biografia, $imagem, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE Artist SET name=?, musical_genre=?, country=?, short_biography=? WHERE id=?");
        return $stmt->execute([$nome, $genero, $pais, $biografia, $id]);
    }
}

// === EVENTOS ===
function getEvents($pdo) {
    return $pdo->query("SELECT Event.*, Tent.name AS tent_name FROM Event LEFT JOIN Tent ON Event.tent_id = Tent.id ORDER BY date_time ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function getEventByIdFull($pdo, $id) {
    $stmt = $pdo->prepare("SELECT Event.*, Tent.name AS tent_name FROM Event LEFT JOIN Tent ON Event.tent_id = Tent.id WHERE Event.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getEventArtists($pdo, $event_id) {
    $stmt = $pdo->prepare("SELECT Artist.* FROM Artist INNER JOIN Event_Artist ON Artist.id = Event_Artist.artist_id WHERE Event_Artist.event_id = ?");
    $stmt->execute([$event_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteEvent($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Event WHERE id = ?");
    return $stmt->execute([$id]);
}

function addEvent($pdo, $tent_id, $nome, $descricao, $data_hora, $localizacao, $tipo, $artistas_selecionados = [], $imagem = 'default.jpg') {
    $stmt = $pdo->prepare("INSERT INTO Event (tent_id, name, description, date_time, location, type, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sucesso = $stmt->execute([$tent_id, $nome, $descricao, $data_hora, $localizacao, $tipo, $imagem]);

    if ($sucesso && !empty($artistas_selecionados)) {
        $event_id = $pdo->lastInsertId();
        $stmt_artist = $pdo->prepare("INSERT INTO Event_Artist (event_id, artist_id) VALUES (?, ?)");
        foreach ($artistas_selecionados as $artist_id) {
            $stmt_artist->execute([$event_id, $artist_id]);
        }
    }
    return $sucesso;
}

function updateEvent($pdo, $id, $tent_id, $nome, $descricao, $data_hora, $localizacao, $tipo, $artistas_selecionados = [], $imagem = null) {
    if ($imagem) {
        $stmt = $pdo->prepare("UPDATE Event SET tent_id=?, name=?, description=?, date_time=?, location=?, type=?, image=? WHERE id=?");
        $stmt->execute([$tent_id, $nome, $descricao, $data_hora, $localizacao, $tipo, $imagem, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE Event SET tent_id=?, name=?, description=?, date_time=?, location=?, type=? WHERE id=?");
        $stmt->execute([$tent_id, $nome, $descricao, $data_hora, $localizacao, $tipo, $id]);
    }

    // Atualizar artistas
    $pdo->prepare("DELETE FROM Event_Artist WHERE event_id = ?")->execute([$id]);
    if (!empty($artistas_selecionados)) {
        $stmt_artist = $pdo->prepare("INSERT INTO Event_Artist (event_id, artist_id) VALUES (?, ?)");
        foreach ($artistas_selecionados as $artist_id) {
            $stmt_artist->execute([$id, $artist_id]);
        }
    }
    return true;
}

// === BARRACAS ===
function getTents($pdo) {
    return $pdo->query("SELECT * FROM Tent ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function getDetailedTents($pdo) {
    return $pdo->query("SELECT Tent.*, Faculty.acronym AS fac_acronym, Faculty.name AS fac_name FROM Tent LEFT JOIN Faculty ON Tent.faculty_id = Faculty.id ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function getTentById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT Tent.*, Faculty.name AS fac_name, Faculty.acronym AS fac_acronym FROM Tent JOIN Faculty ON Tent.faculty_id = Faculty.id WHERE Tent.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteTent($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Tent WHERE id = ?");
    return $stmt->execute([$id]);
}

function addTent($pdo, $faculty_id, $nome, $localizacao, $abertura, $fecho, $descricao) {
    $stmt = $pdo->prepare("INSERT INTO Tent (faculty_id, name, location, opening_hours, closing_hours, description) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$faculty_id, $nome, $localizacao, $abertura, $fecho, $descricao]);
}

function updateTent($pdo, $id, $faculty_id, $nome, $localizacao, $abertura, $fecho, $descricao) {
    $stmt = $pdo->prepare("UPDATE Tent SET faculty_id=?, name=?, location=?, opening_hours=?, closing_hours=?, description=? WHERE id=?");
    return $stmt->execute([$faculty_id, $nome, $localizacao, $abertura, $fecho, $descricao, $id]);
}

// === FACULDADES ===
function getFaculties($pdo) {
    return $pdo->query("SELECT * FROM Faculty ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function getFacultyById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM Faculty WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addFaculty($pdo, $nome, $acronimo, $descricao, $cor) {
    $stmt = $pdo->prepare("INSERT INTO Faculty (name, acronym, description, colour) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nome, $acronimo, $descricao, $cor]);
}

function updateFaculty($pdo, $id, $nome, $acronimo, $descricao, $cor) {
    $stmt = $pdo->prepare("UPDATE Faculty SET name=?, acronym=?, description=?, colour=? WHERE id=?");
    return $stmt->execute([$nome, $acronimo, $descricao, $cor, $id]);
}

function deleteFaculty($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM Faculty WHERE id = ?");
    return $stmt->execute([$id]);
}

// === ESTATÍSTICAS (DASHBOARD) ===
function getStats($pdo) {
    $stats = [];
    $stats['total_users'] = $pdo->query("SELECT COUNT(*) FROM User")->fetchColumn();
    $stats['total_events'] = $pdo->query("SELECT COUNT(*) FROM Event")->fetchColumn();
    $stats['total_artists'] = $pdo->query("SELECT COUNT(*) FROM Artist")->fetchColumn();
    $stats['total_tents'] = $pdo->query("SELECT COUNT(*) FROM Tent")->fetchColumn();
    $stats['total_agenda'] = $pdo->query("SELECT COUNT(*) FROM PersonalAgenda")->fetchColumn();

    $stats['top_event'] = $pdo->query("
        SELECT Event.name, COALESCE(AVG(Rating.value),0) as media, COUNT(Rating.id) as votos 
        FROM Event LEFT JOIN Rating ON Event.id = Rating.event_id 
        GROUP BY Event.id ORDER BY media DESC, votos DESC LIMIT 1
    ")->fetch(PDO::FETCH_ASSOC);

    $stats['top_tent'] = $pdo->query("
        SELECT Tent.name, COALESCE(AVG(Rating.value),0) as media, COUNT(Rating.id) as votos 
        FROM Tent LEFT JOIN Rating ON Tent.id = Rating.tent_id 
        GROUP BY Tent.id ORDER BY media DESC, votos DESC LIMIT 1
    ")->fetch(PDO::FETCH_ASSOC);

    $stats['events_next_48h'] = $pdo->query("
        SELECT COUNT(*) FROM Event WHERE date_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 48 HOUR)
    ")->fetchColumn();

    return $stats;
}
