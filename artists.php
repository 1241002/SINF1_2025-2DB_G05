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

$artistas = getArtists($pdo, $search);

include 'views/artists_view.php';
