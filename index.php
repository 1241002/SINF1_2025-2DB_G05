<?php
// index.php (Controller)

session_start();
require_once 'db.php';
require_once 'models/IndexModel.php';
require_once 'models/AdminModel.php'; // <-- A LINHA QUE FALTAVA!

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$is_admin = ($_SESSION['user_role'] == 1);

// LÓGICA DA AGENDA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_agenda'])) {
    toggleAgenda($pdo, $user_id, $_POST['evento_id'], $_POST['acao']);
    header("Location: index.php?" . $_SERVER['QUERY_STRING']);
    exit();
}

// LÓGICA DE AVALIAÇÃO COM COMENTÁRIO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rate_event'])) {
    $comentario = !empty($_POST['rating_comment']) ? trim($_POST['rating_comment']) : null;
    rateEvent($pdo, $user_id, $_POST['evento_id'], (int)$_POST['rating_value'], $comentario);
    header("Location: index.php?" . $_SERVER['QUERY_STRING']);
    exit();
}

// PARÂMETROS DE PESQUISA E FILTRO
$ordenacao = isset($_GET['sort']) ? $_GET['sort'] : 'data';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$tipo_filtro = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$faculty_filtro = isset($_GET['faculty']) ? $_GET['faculty'] : '';

// BUSCAR DADOS
$eventos = getEventsWithRatings($pdo, $ordenacao, $search, $tipo_filtro, $faculty_filtro);
$tipos_evento = getEventTypes($pdo);
$faculdades = getFaculties($pdo);
$minha_agenda = getUserAgendaIds($pdo, $user_id);
$meus_ratings = getUserRatings($pdo, $user_id);
$meus_comentarios = getUserRatingComments($pdo, $user_id);
$nomes_artistas_por_evento = [];
foreach ($eventos as $evento) {
    $nomes_artistas_por_evento[$evento['id']] = getEventArtistsNames($pdo, $evento['id']);
}

include 'views/index_view.php';