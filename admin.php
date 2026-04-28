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
// ==========================================
// PROCESSAR AÇÕES DOS ARTISTAS
// ==========================================

// EXPORTAR ARTISTAS PARA CSV
if (isset($_GET['export_artists'])) {
    // 1. Diz ao browser que isto é um download de um ficheiro CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=artistas_queima_2026.csv');
    
    // 2. Abre a "porta" de saída de dados
    $saida = fopen('php://output', 'w');
    
    // 3. Escreve a primeira linha (os cabeçalhos das colunas)
    fputcsv($saida, ['ID', 'Nome', 'Genero Musical', 'Pais', 'Biografia']);
    
    // 4. Vai buscar os artistas ao Model e escreve linha a linha
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
    
    // 5. Fecha o ficheiro e PÁRA o script para não carregar o HTML da página para dentro do CSV
    fclose($saida);
    exit();
}

// IMPORTAR ARTISTAS DE UM CSV
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['import_artists'])) {
    // Verifica se o ficheiro foi enviado e se não tem erros
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        
        $ficheiro = $_FILES['csv_file']['tmp_name'];
        
        // Abre o ficheiro em modo de leitura ("r")
        if (($handle = fopen($ficheiro, "r")) !== FALSE) {
            
            // Lê a primeira linha e descarta-a (porque costuma ser o cabeçalho: "Nome,Genero,Pais,Bio")
            fgetcsv($handle, 1000, ",");
            
            $artistas_importados = 0;
            
            // Lê o resto do ficheiro linha a linha
            while (($linha = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Previne erros caso a linha do CSV esteja vazia ou incompleta
                if (count($linha) >= 4) {
                    $nome = trim($linha[0]);
                    $genero = trim($linha[1]);
                    $pais = trim($linha[2]);
                    $biografia = trim($linha[3]);
                    
                    // Reutilizamos a tua função do Model para inserir na base de dados!
                    if (addArtist($pdo, $nome, $genero, $pais, $biografia)) {
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_artist'])) {
    if (empty($_POST['nome']) || empty($_POST['genero']) || empty($_POST['pais']) || empty($_POST['biografia'])) {
        $erro = "Preenche todos os campos do artista.";
    } else {
        if (addArtist($pdo, trim($_POST['nome']), trim($_POST['genero']), trim($_POST['pais']), trim($_POST['biografia']))) {
            $sucesso = "Artista adicionado!";
        }
    }
}

// ==========================================
// PROCESSAR AÇÕES DOS EVENTOS (Já com Artistas!)
// ==========================================
if (isset($_GET['delete_event'])) {
    if (deleteEvent($pdo, $_GET['delete_event'])) {
        $sucesso = "Evento apagado!";
    } else { $erro = "Erro ao apagar evento."; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    if (empty($_POST['nome']) || empty($_POST['tipo']) || empty($_POST['data_hora']) || empty($_POST['localizacao'])) {
        $erro = "Preenche os campos obrigatórios do evento.";
    } else {
        $tent_id = !empty($_POST['tent_id']) ? $_POST['tent_id'] : NULL;
        
        // NOVIDADE: Captura o array de artistas (se nenhum for escolhido, fica um array vazio)
        $artistas_selecionados = isset($_POST['artistas']) ? $_POST['artistas'] : []; 
        
        if (addEvent($pdo, $tent_id, trim($_POST['nome']), trim($_POST['descricao']), $_POST['data_hora'], trim($_POST['localizacao']), $_POST['tipo'], $artistas_selecionados)) {
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