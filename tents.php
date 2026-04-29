<?php
// tents.php (Controller)

session_start();
require_once 'db.php';
require_once 'models/TentsModel.php';
require_once 'models/AdminModel.php'; // Para getFaculties

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$is_admin = ($_SESSION['user_role'] == 1);

// AVALIAÇÃO COM COMENTÁRIO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rate_tent'])) {
    $comentario = !empty($_POST['rating_comment']) ? trim($_POST['rating_comment']) : null;
    rateTent($pdo, $user_id, $_POST['tent_id'], (int)$_POST['rating_value'], $comentario);
    header("Location: tents.php?" . $_SERVER['QUERY_STRING']);
    exit();
}

// PESQUISA
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$faculty_filtro = isset($_GET['faculty']) ? $_GET['faculty'] : '';

$tendas = getTentsWithRatings($pdo, $search, $faculty_filtro);
$faculdades = getFaculties($pdo);
$meus_ratings = getUserTentRatings($pdo, $user_id);
$meus_comentarios = getUserTentComments($pdo, $user_id);

include 'views/tents_view.php';
