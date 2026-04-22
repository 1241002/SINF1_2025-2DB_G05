<?php
// agenda.php (O Controller)

session_start();
require_once 'db.php';
require_once 'models/AgendaModel.php'; // Incluímos a nova camada de dados

// 1. PROTEÇÃO
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$is_admin = ($_SESSION['user_role'] == 1);

// 2. PROCESSAR AÇÕES (Lógica de Negócio)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_agenda'])) {
    $evento_id = $_POST['evento_id'];
    
    // Chama a função que está no Model
    removerEventoDaAgenda($pdo, $user_id, $evento_id);
    
    header("Location: agenda.php");
    exit();
}

// 3. IR BUSCAR OS DADOS (Chamando o Model)
$meus_eventos = getEventosDaAgenda($pdo, $user_id);

// 4. CARREGAR A VIEW (O HTML)
include 'views/agenda_view.php';
?>