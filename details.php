<?php
// details.php
session_start();
require_once 'db.php';
require_once 'models/DetailsModel.php';
require_once 'models/TentsModel.php';

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;
$data = null;
$artistas = [];
$ratings_summary = [];
$comentarios = [];

if ($type === 'artist') {
    $data = getArtistById($pdo, $id);
    $title = "Artista: " . ($data['name'] ?? 'Não encontrado');
} elseif ($type === 'tent') {
    $data = getTentById($pdo, $id);
    $title = "Barraca: " . ($data['name'] ?? 'Não encontrada');
    $ratings_summary = getTentRatingsSummary($pdo, $id);
    $comentarios = getTentComments($pdo, $id);
} elseif ($type === 'event') {
    $data = getEventById($pdo, $id);
    $title = "Evento: " . ($data['name'] ?? 'Não encontrado');
    if ($data) {
        $artistas = getEventArtists($pdo, $id);
        $ratings_summary = getEventRatingsSummary($pdo, $id);
        $comentarios = getEventComments($pdo, $id);
    }
}

if (!$data) {
    header("Location: index.php");
    exit();
}

include 'views/details_view.php';
