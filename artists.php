<?php
// artists.php (Controller)

session_start();
require_once 'db.php';
require_once 'models/AdminModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$is_admin = ($_SESSION['user_role'] == 1);

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$artistas = getArtists($pdo);

if ($search !== '') {
    $artistas = array_filter($artistas, function($a) use ($search) {
        return stripos($a['name'], $search) !== false
            || stripos($a['musical_genre'], $search) !== false
            || stripos($a['country'], $search) !== false;
    });
    $artistas = array_values($artistas);
}

include 'views/artists_view.php';
