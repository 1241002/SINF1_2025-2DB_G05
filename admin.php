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

// ==========================================
// PROCESSAR AÇÕES DOS ARTISTAS
// ==========================================

// EXPORTAR ARTISTAS PARA CSV
if (isset($_GET['export_artists'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=artistas_queima_2026.csv');
    $saida = fopen('php://output', 'w');
    fputcsv($saida, ['ID', 'Nome', 'Genero Musical', 'Pais', 'Biografia']);
    $lista_artistas = getArtists($pdo);
    foreach ($lista_artistas as $artista) {
        fputcsv($saida, [
            $artista['id'], 
            $artista['name'], 
            $artista['musical_genre'], 
            $artista['country'], 
            $artista['short_biography']
        ]);
    }
    fclose($saida);
    exit();
}

// IMPORTAR ARTISTAS DE UM CSV
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['import_artists'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $ficheiro = $_FILES['csv_file']['tmp_name'];
        if (($handle = fopen($ficheiro, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ",");
            $artistas_importados = 0;
            while (($linha = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($linha) >= 4) {
                    $nome = trim($linha[0]);
                    $genero = trim($linha[1]);
                    $pais = trim($linha[2]);
                    $biografia = trim($linha[3]);
                    
                    // Como é por CSV, passamos a imagem padrão no final
                    if (addArtist($pdo, $nome, $genero, $pais, $biografia, 'default.jpg')) {
                        $artistas_importados++;
                    }
                }
            }
            fclose($handle);
            $sucesso = "$artistas_importados artistas foram importados com sucesso!";
        } else {
            $erro = "Não foi possível abrir o ficheiro CSV.";
        }
    } else {
        $erro = "Erro ao fazer upload do ficheiro. Verifica se é um .csv válido.";
    }
}

if (isset($_GET['delete_artist'])) {
    if (deleteArtist($pdo, $_GET['delete_artist'])) {
        $sucesso = "Artista apagado!";
    } else { $erro = "Erro ao apagar artista."; }
}

// ADICIONAR ARTISTA (COM UPLOAD)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_artist'])) {
    if (empty($_POST['nome']) || empty($_POST['genero']) || empty($_POST['pais']) || empty($_POST['biografia'])) {
        $erro = "Preenche todos os campos do artista.";
    } else {
        // Processar upload de imagem
        $imagem_artista = 'default.jpg';
        
        if (isset($_FILES['imagem_artista']) && $_FILES['imagem_artista']['error'] == UPLOAD_ERR_OK) {
            $ficheiro_tmp = $_FILES['imagem_artista']['tmp_name'];
            $ficheiro_nome_original = $_FILES['imagem_artista']['name'];
            $extensao = strtolower(pathinfo($ficheiro_nome_original, PATHINFO_EXTENSION));
            
            // Gerar nome único
            $nome_unico = uniqid('artist_') . '.' . $extensao;
            $caminho_destino = 'uploads/' . $nome_unico;
            
            // Mover ficheiro
            if (move_uploaded_file($ficheiro_tmp, $caminho_destino)) {
                $imagem_artista = $nome_unico;
            }
        }
        
        if (addArtist($pdo, trim($_POST['nome']), trim($_POST['genero']), trim($_POST['pais']), trim($_POST['biografia']), $imagem_artista)) {
            $sucesso = "Artista adicionado!";
        }
    }
}

// ==========================================
// PROCESSAR AÇÕES DOS EVENTOS
// ==========================================
if (isset($_GET['delete_event'])) {
    if (deleteEvent($pdo, $_GET['delete_event'])) {
        $sucesso = "Evento apagado!";
    } else { $erro = "Erro ao apagar evento."; }
}

// ADICIONAR EVENTO (COM UPLOAD)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    if (empty($_POST['nome']) || empty($_POST['tipo']) || empty($_POST['data_hora']) || empty($_POST['localizacao'])) {
        $erro = "Preenche os campos obrigatórios do evento.";
    } else {
        // Processar upload de imagem
        $imagem_evento = 'default.jpg';
        
        if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] == UPLOAD_ERR_OK) {
            $ficheiro_tmp = $_FILES['imagem_evento']['tmp_name'];
            $ficheiro_nome_original = $_FILES['imagem_evento']['name'];
            $extensao = strtolower(pathinfo($ficheiro_nome_original, PATHINFO_EXTENSION));
            
            // Gerar nome único
            $nome_unico = uniqid('event_') . '.' . $extensao;
            $caminho_destino = 'uploads/' . $nome_unico;
            
            // Mover ficheiro
            if (move_uploaded_file($ficheiro_tmp, $caminho_destino)) {
                $imagem_evento = $nome_unico;
            }
        }
        
        $tent_id = !empty($_POST['tent_id']) ? $_POST['tent_id'] : NULL;
        $artistas_selecionados = isset($_POST['artistas']) ? $_POST['artistas'] : []; 
        
        if (addEvent($pdo, $tent_id, trim($_POST['nome']), trim($_POST['descricao']), $_POST['data_hora'], trim($_POST['localizacao']), $_POST['tipo'], $artistas_selecionados, $imagem_evento)) {
            $sucesso = "Evento adicionado com sucesso!";
        } else {
            $erro = "Erro ao adicionar evento.";
        }
    }
}

// ==========================================
// PROCESSAR AÇÕES DAS BARRACAS
// ==========================================
if (isset($_GET['delete_tent'])) {
    if (deleteTent($pdo, $_GET['delete_tent'])) {
        $sucesso = "Barraca apagada!";
    } else { $erro = "Erro ao apagar barraca."; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_tent'])) {
    if (empty($_POST['nome']) || empty($_POST['faculty_id']) || empty($_POST['localizacao']) || empty($_POST['abertura']) || empty($_POST['fecho'])) {
        $erro = "Preenche os campos obrigatórios da barraca.";
    } else {
        if (addTent($pdo, $_POST['faculty_id'], trim($_POST['nome']), trim($_POST['localizacao']), $_POST['abertura'], $_POST['fecho'], trim($_POST['descricao']))) {
            $sucesso = "Barraca adicionada!";
        } else {
            $erro = "Erro ao adicionar barraca.";
        }
    }
}

// 3. IR BUSCAR OS DADOS (Chamando o Model)
$artistas = getArtists($pdo);
$eventos = getEvents($pdo);
$tendas = getTents($pdo);
$faculdades = getFaculties($pdo);
$tendas_detalhadas = getDetailedTents($pdo);

// 4. CARREGAR A VIEW (Passando-lhe os dados)
include 'views/admin_view.php';
?>