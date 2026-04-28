<?php
// models/DetailsModel.php

function getArtistById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM Artist WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getTentById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT Tent.*, Faculty.name AS fac_name, Faculty.acronym AS fac_acronym 
                           FROM Tent 
                           JOIN Faculty ON Tent.faculty_id = Faculty.id 
                           WHERE Tent.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>