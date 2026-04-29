<?php
// models/TentsModel.php

function rateTent($pdo, $user_id, $tent_id, $nota, $comentario = null) {
    $stmt_check = $pdo->prepare("SELECT id FROM Rating WHERE user_id = ? AND tent_id = ?");
    $stmt_check->execute([$user_id, $tent_id]);
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
        $stmt = $pdo->prepare("INSERT INTO Rating (user_id, tent_id, value, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $tent_id, $nota, $comentario]);
    }
}

function getTentsWithRatings($pdo, $search = '', $faculty_id = '') {
    $where = [];
    $params = [];

    if (!empty($search)) {
        $where[] = "(Tent.name LIKE ? OR Tent.location LIKE ? OR Tent.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if (!empty($faculty_id)) {
        $where[] = "Tent.faculty_id = ?";
        $params[] = $faculty_id;
    }

    $whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $pdo->prepare("
        SELECT Tent.*, Faculty.name AS fac_name, Faculty.acronym AS fac_acronym, Faculty.colour,
               COALESCE(AVG(Rating.value), 0) AS media_rating,
               COUNT(Rating.id) AS total_votos
        FROM Tent 
        LEFT JOIN Faculty ON Tent.faculty_id = Faculty.id 
        LEFT JOIN Rating ON Tent.id = Rating.tent_id
        $whereSql
        GROUP BY Tent.id
        ORDER BY Tent.name ASC
    ");
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserTentRatings($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT tent_id, value FROM Rating WHERE user_id = ? AND tent_id IS NOT NULL");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

function getUserTentComments($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT tent_id, comment FROM Rating WHERE user_id = ? AND tent_id IS NOT NULL AND comment IS NOT NULL");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

function getTentComments($pdo, $tent_id) {
    $stmt = $pdo->prepare("
        SELECT Rating.*, User.name as user_name 
        FROM Rating 
        JOIN User ON Rating.user_id = User.id 
        WHERE Rating.tent_id = ? AND Rating.comment IS NOT NULL AND Rating.comment != ''
        ORDER BY Rating.id DESC
    ");
    $stmt->execute([$tent_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
