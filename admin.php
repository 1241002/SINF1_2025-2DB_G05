<?php
// admin.php (Controller)

session_start();
require_once 'db.php';
require_once 'models/AdminModel.php';

// PROTEÇÃO
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: index.php");
    exit();
}

$sucesso = "";
$erro = "";
$aba_ativa = $_POST['tab'] ?? $_GET['tab'] ?? 'dashboard';

// ==========================================
// EXPORTAR ARTISTAS PARA CSV
// ==========================================
if (isset($_GET['export_artists'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=artistas_queima_2026.csv');
    $saida = fopen('php://output', 'w');
    fputcsv($saida, ['ID', 'Nome', 'Genero Musical', 'Pais', 'Biografia']);
    $lista_artistas = getArtists($pdo);
    foreach ($lista_artistas as $artista) {
        fputcsv($saida, [$artista['id'], $artista['name'], $artista['musical_genre'], $artista['country'], $artista['short_biography']]);
    }
    fclose($saida);
    exit();
}

// EXPORTAR EVENTOS PARA CSV
if (isset($_GET['export_events'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=eventos_queima_2026.csv');
    $saida = fopen('php://output', 'w');
    fputcsv($saida, ['ID', 'Nome', 'Tipo', 'Data', 'Local', 'Barraca']);
    $lista_eventos = getEvents($pdo);
    foreach ($lista_eventos as $evento) {
        fputcsv($saida, [$evento['id'], $evento['name'], $evento['type'], $evento['date_time'], $evento['location'], $evento['tent_name']]);
    }
    fclose($saida);
    exit();
}

// IMPORTAR ARTISTAS DE CSV
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
                    if (addArtist($pdo, $nome, $genero, $pais, $biografia, 'default.jpg')) {
                        $artistas_importados++;
                    }
                }
            }
            fclose($handle);
            $sucesso = "$artistas_importados artistas importados com sucesso!";
        } else {
            $erro = "Não foi possível abrir o ficheiro CSV.";
        }
    } else {
        $erro = "Erro no upload do ficheiro.";
    }
}

// ==========================================
// ARTISTAS - DELETE
// ==========================================
if (isset($_GET['delete_artist'])) {
    if (deleteArtist($pdo, $_GET['delete_artist'])) {
        $sucesso = "Artista apagado!";
    } else { $erro = "Erro ao apagar artista."; }
}

// ARTISTAS - ADD
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_artist'])) {
    if (empty($_POST['nome']) || empty($_POST['genero']) || empty($_POST['pais']) || empty($_POST['biografia'])) {
        $erro = "Preenche todos os campos do artista.";
    } else {
        $imagem_artista = 'default.jpg';
        if (isset($_FILES['imagem_artista']) && $_FILES['imagem_artista']['error'] == UPLOAD_ERR_OK) {
            $ficheiro_tmp = $_FILES['imagem_artista']['tmp_name'];
            $extensao = strtolower(pathinfo($_FILES['imagem_artista']['name'], PATHINFO_EXTENSION));
            $nome_unico = uniqid('artist_') . '.' . $extensao;
            $caminho_destino = 'uploads/' . $nome_unico;
            if (move_uploaded_file($ficheiro_tmp, $caminho_destino)) {
                $imagem_artista = $nome_unico;
            }
        }
        if (addArtist($pdo, trim($_POST['nome']), trim($_POST['genero']), trim($_POST['pais']), trim($_POST['biografia']), $imagem_artista)) {
            $sucesso = "Artista adicionado!";
        }
    }
}

// ARTISTAS - EDIT
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_artist'])) {
    if (empty($_POST['nome']) || empty($_POST['genero']) || empty($_POST['pais']) || empty($_POST['biografia']) || empty($_POST['artist_id'])) {
        $erro = "Dados incompletos para edição de artista.";
    } else {
        $artista_atual = getArtistById($pdo, $_POST['artist_id']);
        $imagem_artista = $artista_atual['image'];
        
        if (isset($_FILES['imagem_artista']) && $_FILES['imagem_artista']['error'] == UPLOAD_ERR_OK) {
            $ficheiro_tmp = $_FILES['imagem_artista']['tmp_name'];
            $extensao = strtolower(pathinfo($_FILES['imagem_artista']['name'], PATHINFO_EXTENSION));
            $nome_unico = uniqid('artist_') . '.' . $extensao;
            $caminho_destino = 'uploads/' . $nome_unico;
            if (move_uploaded_file($ficheiro_tmp, $caminho_destino)) {
                $imagem_artista = $nome_unico;
            }
        }
        
        if (updateArtist($pdo, $_POST['artist_id'], trim($_POST['nome']), trim($_POST['genero']), trim($_POST['pais']), trim($_POST['biografia']), $imagem_artista)) {
            $sucesso = "Artista atualizado com sucesso!";
        } else {
            $erro = "Erro ao atualizar artista.";
        }
    }
}

// ==========================================
// EVENTOS - DELETE
// ==========================================
if (isset($_GET['delete_event'])) {
    if (deleteEvent($pdo, $_GET['delete_event'])) {
        $sucesso = "Evento apagado!";
    } else { $erro = "Erro ao apagar evento."; }
}

// EVENTOS - ADD
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    if (empty($_POST['nome']) || empty($_POST['tipo']) || empty($_POST['data_hora']) || empty($_POST['localizacao'])) {
        $erro = "Preenche os campos obrigatórios do evento.";
    } else {
        $imagem_evento = 'default.jpg';
        if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] == UPLOAD_ERR_OK) {
            $ficheiro_tmp = $_FILES['imagem_evento']['tmp_name'];
            $extensao = strtolower(pathinfo($_FILES['imagem_evento']['name'], PATHINFO_EXTENSION));
            $nome_unico = uniqid('event_') . '.' . $extensao;
            $caminho_destino = 'uploads/' . $nome_unico;
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

// EVENTOS - EDIT
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_event'])) {
    if (empty($_POST['nome']) || empty($_POST['tipo']) || empty($_POST['data_hora']) || empty($_POST['localizacao']) || empty($_POST['event_id'])) {
        $erro = "Dados incompletos para edição de evento.";
    } else {
        $evento_atual = getEventByIdFull($pdo, $_POST['event_id']);
        $imagem_evento = $evento_atual['image'];
        
        if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] == UPLOAD_ERR_OK) {
            $ficheiro_tmp = $_FILES['imagem_evento']['tmp_name'];
            $extensao = strtolower(pathinfo($_FILES['imagem_evento']['name'], PATHINFO_EXTENSION));
            $nome_unico = uniqid('event_') . '.' . $extensao;
            $caminho_destino = 'uploads/' . $nome_unico;
            if (move_uploaded_file($ficheiro_tmp, $caminho_destino)) {
                $imagem_evento = $nome_unico;
            }
        }
        
        $tent_id = !empty($_POST['tent_id']) ? $_POST['tent_id'] : NULL;
        $artistas_selecionados = isset($_POST['artistas']) ? $_POST['artistas'] : [];
        
        if (updateEvent($pdo, $_POST['event_id'], $tent_id, trim($_POST['nome']), trim($_POST['descricao']), $_POST['data_hora'], trim($_POST['localizacao']), $_POST['tipo'], $artistas_selecionados, $imagem_evento)) {
            $sucesso = "Evento atualizado com sucesso!";
        } else {
            $erro = "Erro ao atualizar evento.";
        }
    }
}

// ==========================================
// BARRACAS - DELETE
// ==========================================
if (isset($_GET['delete_tent'])) {
    if (deleteTent($pdo, $_GET['delete_tent'])) {
        $sucesso = "Barraca apagada!";
    } else { $erro = "Erro ao apagar barraca."; }
}

// BARRACAS - ADD
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

// BARRACAS - EDIT
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_tent'])) {
    if (empty($_POST['nome']) || empty($_POST['faculty_id']) || empty($_POST['localizacao']) || empty($_POST['abertura']) || empty($_POST['fecho']) || empty($_POST['tent_id'])) {
        $erro = "Dados incompletos para edição de barraca.";
    } else {
        if (updateTent($pdo, $_POST['tent_id'], $_POST['faculty_id'], trim($_POST['nome']), trim($_POST['localizacao']), $_POST['abertura'], $_POST['fecho'], trim($_POST['descricao']))) {
            $sucesso = "Barraca atualizada com sucesso!";
        } else {
            $erro = "Erro ao atualizar barraca.";
        }
    }
}

// ==========================================
// FACULDADES - CRUD
// ==========================================
if (isset($_GET['delete_faculty'])) {
    if (deleteFaculty($pdo, $_GET['delete_faculty'])) {
        $sucesso = "Faculdade apagada!";
    } else { $erro = "Erro ao apagar faculdade. Verifica se não tem barracas associadas."; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_faculty'])) {
    if (empty($_POST['nome']) || empty($_POST['acronimo'])) {
        $erro = "Nome e acrónimo são obrigatórios.";
    } else {
        if (addFaculty($pdo, trim($_POST['nome']), trim($_POST['acronimo']), trim($_POST['descricao']), trim($_POST['cor']))) {
            $sucesso = "Faculdade adicionada!";
        } else {
            $erro = "Erro ao adicionar faculdade.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_faculty'])) {
    if (empty($_POST['nome']) || empty($_POST['acronimo']) || empty($_POST['faculty_id'])) {
        $erro = "Dados incompletos para edição.";
    } else {
        if (updateFaculty($pdo, $_POST['faculty_id'], trim($_POST['nome']), trim($_POST['acronimo']), trim($_POST['descricao']), trim($_POST['cor']))) {
            $sucesso = "Faculdade atualizada!";
        } else {
            $erro = "Erro ao atualizar faculdade.";
        }
    }
}

// ==========================================
// BUSCAR DADOS
// ==========================================
$artistas = getArtists($pdo);
$eventos = getEvents($pdo);
$tendas = getTents($pdo);
$faculdades = getFaculties($pdo);
$tendas_detalhadas = getDetailedTents($pdo);
$estatisticas = getStats($pdo);

include 'views/admin_view.php';
