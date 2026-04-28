<?php
// details.php
session_start();
require_once 'db.php';
require_once 'models/DetailsModel.php';

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;
$data = null;

if ($type === 'artist') {
    $data = getArtistById($pdo, $id);
    $title = "Artista: " . ($data['name'] ?? 'Não encontrado');
} elseif ($type === 'tent') {
    $data = getTentById($pdo, $id);
    $title = "Barraca: " . ($data['name'] ?? 'Não encontrada');
}

if (!$data) {
    header("Location: index.php");
    exit();
}

include 'views/details_view.php';
?>