<?php
// admin.php (O Controller)

session_start();
require_once 'db.php';
require_once 'models/AdminModel.php'; // Inclui a camada de dados

// 1. PROTEÇÃO (Lógica de Negócio)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: index.php");
    exit();
}

$sucesso = "";
$erro = "";

// 2. PROCESSAR AÇÕES (Lógica de Negócio chamando o Model)
if (isset($_GET['delete_artist'])) {
    if (deleteArtist($pdo, $_GET['delete_artist'])) {
        $sucesso = "Artista apagado!";
    } else { $erro = "Erro ao apagar artista."; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_artist'])) {
    if (empty($_POST['nome']) || empty($_POST['genero']) || empty($_POST['pais']) || empty($_POST['biografia'])) {
        $erro = "Preenche todos os campos do artista.";
    } else {
        if (addArtist($pdo, trim($_POST['nome']), trim($_POST['genero']), trim($_POST['pais']), trim($_POST['biografia']))) {
            $sucesso = "Artista adicionado!";
        }
    }
}

// (Repetes a lógica simples para apagar/adicionar Eventos e Barracas usando as funções deleteEvent, addEvent, etc.)

// 3. IR BUSCAR OS DADOS (Chamando o Model)
$artistas = getArtists($pdo);
$eventos = getEvents($pdo);
$tendas = getTents($pdo);
$faculdades = getFaculties($pdo);
$tendas_detalhadas = getDetailedTents($pdo);

// 4. CARREGAR A VIEW (Passando-lhe os dados)
include 'views/admin_view.php';
?>