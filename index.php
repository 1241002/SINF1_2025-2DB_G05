<?php
// index.php (O Controller)

session_start();
require_once 'db.php';
require_once 'models/IndexModel.php'; // Incluímos a camada de dados

// 1. PROTEÇÃO
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$is_admin = ($_SESSION['user_role'] == 1);

// 2. LÓGICA DA AGENDA PESSOAL
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_agenda'])) {
    toggleAgenda($pdo, $user_id, $_POST['evento_id'], $_POST['acao']);
    header("Location: index.php");
    exit();
}

// 3. LÓGICA DE AVALIAÇÃO (RATING)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rate_event'])) {
    rateEvent($pdo, $user_id, $_POST['evento_id'], (int)$_POST['rating_value']);
    header("Location: index.php");
    exit();
}

// 4. IR BUSCAR DADOS
// Vê que ordenação o utilizador escolheu (se não houver nenhuma, usa 'data' por defeito)
$ordenacao = isset($_GET['sort']) ? $_GET['sort'] : 'data';

// Vai buscar os eventos ao Model JÁ ORDENADOS!
$eventos = getEventsWithRatings($pdo, $ordenacao);
$minha_agenda = getUserAgendaIds($pdo, $user_id);
$meus_ratings = getUserRatings($pdo, $user_id);

// 5. CARREGAR A VIEW
include 'views/index_view.php';
?>