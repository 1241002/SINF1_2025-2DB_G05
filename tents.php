<?php
// tents.php (O Controller)

session_start();
require_once 'db.php';
require_once 'models/TentsModel.php'; // Incluímos a camada de dados

// 1. PROTEÇÃO
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$is_admin = ($_SESSION['user_role'] == 1);

// 2. LÓGICA DE AVALIAÇÃO (RATING DAS BARRACAS)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rate_tent'])) {
    rateTent($pdo, $user_id, $_POST['tent_id'], (int)$_POST['rating_value']);
    header("Location: tents.php");
    exit();
}

// 3. IR BUSCAR DADOS
$tendas = getTentsWithRatings($pdo);
$meus_ratings = getUserTentRatings($pdo, $user_id);

// 4. CARREGAR A VIEW
include 'views/tents_view.php';
?>