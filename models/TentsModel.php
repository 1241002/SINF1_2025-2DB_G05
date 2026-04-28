<?php
// models/TentsModel.php

// ==========================================
// AVALIAÇÕES (RATINGS) DAS BARRACAS
// ==========================================
function rateTent($pdo, $user_id, $tent_id, $nota) {
    // Verifica se o utilizador já votou nesta barraca
    $stmt_check = $pdo->prepare("SELECT id FROM Rating WHERE user_id = ? AND tent_id = ?");
    $stmt_check->execute([$user_id, $tent_id]);
    $ja_votou = $stmt_check->fetch();

    if ($ja_votou) {
        $stmt = $pdo->prepare("UPDATE Rating SET value = ? WHERE id = ?");
        return $stmt->execute([$nota, $ja_votou['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO Rating (user_id, tent_id, value) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $tent_id, $nota]);
    }
}

// ==========================================
// OBTER DADOS DAS BARRACAS PARA A VIEW
// ==========================================
function getTentsWithRatings($pdo) {
    $stmt = $pdo->query("
        SELECT 
            Tent.*, 
            Faculty.name AS fac_name,
            Faculty.acronym AS fac_acronym,
            COALESCE(AVG(Rating.value), 0) AS media_rating,
            COUNT(Rating.id) AS total_votos
        FROM Tent 
        LEFT JOIN Faculty ON Tent.faculty_id = Faculty.id 
        LEFT JOIN Rating ON Tent.id = Rating.tent_id
        GROUP BY Tent.id
        ORDER BY Tent.name ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar as avaliações que ESTE utilizador já deu
function getUserTentRatings($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT tent_id, value FROM Rating WHERE user_id = ? AND tent_id IS NOT NULL");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}
?>