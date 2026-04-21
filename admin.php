<?php
session_start();
require_once 'db.php';

// ==========================================
// 1. PROTEÇÃO (Role 1 = Admin)
// ==========================================
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: index.php");
    exit();
}

$sucesso = "";
$erro = "";

// ==========================================
// 2. PROCESSAR AÇÕES DOS ARTISTAS
// ==========================================
if (isset($_GET['delete_artist'])) {
    $stmt = $pdo->prepare("DELETE FROM Artist WHERE id = ?");
    if ($stmt->execute([$_GET['delete_artist']])) {
        $sucesso = "Artista apagado!";
    } else { $erro = "Erro ao apagar artista."; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_artist'])) {
    $nome = trim($_POST['nome']);
    $genero = trim($_POST['genero']);
    $pais = trim($_POST['pais']);
    $biografia = trim($_POST['biografia']);

    if (empty($nome) || empty($genero) || empty($pais) || empty($biografia)) {
        $erro = "Preenche todos os campos do artista.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO Artist (name, musical_genre, country, short_biography) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nome, $genero, $pais, $biografia])) $sucesso = "Artista '$nome' adicionado!";
    }
}

// ==========================================
// 3. PROCESSAR AÇÕES DOS EVENTOS
// ==========================================
if (isset($_GET['delete_event'])) {
    $stmt = $pdo->prepare("DELETE FROM Event WHERE id = ?");
    if ($stmt->execute([$_GET['delete_event']])) {
        $sucesso = "Evento apagado!";
    } else { $erro = "Erro ao apagar evento."; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $nome = trim($_POST['nome']);
    $tipo = $_POST['tipo'];
    $data_hora = $_POST['data_hora'];
    $localizacao = trim($_POST['localizacao']);
    $descricao = trim($_POST['descricao']);
    $tent_id = !empty($_POST['tent_id']) ? $_POST['tent_id'] : NULL;

    if (empty($nome) || empty($tipo) || empty($data_hora) || empty($localizacao)) {
        $erro = "Preenche os campos obrigatórios do evento.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO Event (tent_id, name, description, date_time, location, type) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$tent_id, $nome, $descricao, $data_hora, $localizacao, $tipo])) $sucesso = "Evento '$nome' adicionado!";
    }
}

// ==========================================
// 4. PROCESSAR AÇÕES DAS BARRACAS
// ==========================================
if (isset($_GET['delete_tent'])) {
    $stmt = $pdo->prepare("DELETE FROM Tent WHERE id = ?");
    if ($stmt->execute([$_GET['delete_tent']])) {
        $sucesso = "Barraca apagada!";
    } else { $erro = "Erro ao apagar barraca."; }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_tent'])) {
    $nome = trim($_POST['nome']);
    $faculty_id = $_POST['faculty_id'];
    $localizacao = trim($_POST['localizacao']);
    $abertura = $_POST['abertura'];
    $fecho = $_POST['fecho'];
    $descricao = trim($_POST['descricao']);

    if (empty($nome) || empty($faculty_id) || empty($localizacao) || empty($abertura) || empty($fecho)) {
        $erro = "Preenche os campos obrigatórios da barraca.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO Tent (faculty_id, name, location, opening_hours, closing_hours, description) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$faculty_id, $nome, $localizacao, $abertura, $fecho, $descricao])) $sucesso = "Barraca '$nome' adicionada!";
    }
}

// ==========================================
// 5. IR BUSCAR OS DADOS (Read)
// ==========================================
$artistas = $pdo->query("SELECT * FROM Artist ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$tendas = $pdo->query("SELECT * FROM Tent ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$eventos = $pdo->query("SELECT Event.*, Tent.name AS tent_name FROM Event LEFT JOIN Tent ON Event.tent_id = Tent.id ORDER BY date_time ASC")->fetchAll(PDO::FETCH_ASSOC);

// Buscar Faculdades e Barracas detalhadas para a aba 3
$faculdades = $pdo->query("SELECT * FROM Faculty ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$tendas_detalhadas = $pdo->query("SELECT Tent.*, Faculty.acronym AS fac_acronym FROM Tent LEFT JOIN Faculty ON Tent.faculty_id = Faculty.id ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Queima das Fitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">⚙️ Dashboard de Administração</span>
        <div class="d-flex">
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">Voltar ao Site</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php if($erro): ?><div class="alert alert-danger"><?php echo $erro; ?></div><?php endif; ?>
    <?php if($sucesso): ?><div class="alert alert-success"><?php echo $sucesso; ?></div><?php endif; ?>

    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#artistas" type="button">Artistas</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#eventos" type="button">Eventos</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#barracas" type="button">Barracas</button></li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        
        <div class="tab-pane fade show active" id="artistas">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-primary">
                        <div class="card-header bg-primary text-white">Adicionar Artista</div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="add_artist" value="1">
                                <div class="mb-2"><input type="text" name="nome" class="form-control form-control-sm" required placeholder="Nome"></div>
                                <div class="mb-2"><input type="text" name="genero" class="form-control form-control-sm" required placeholder="Género"></div>
                                <div class="mb-2"><input type="text" name="pais" class="form-control form-control-sm" required placeholder="País"></div>
                                <div class="mb-2"><textarea name="biografia" class="form-control form-control-sm" rows="2" required placeholder="Biografia"></textarea></div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Guardar Artista</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead><tr><th>Nome</th><th>Género</th><th>País</th><th>Ações</th></tr></thead>
                                <tbody>
                                    <?php foreach($artistas as $art): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($art['name']); ?></td>
                                        <td><?php echo htmlspecialchars($art['musical_genre']); ?></td>
                                        <td><?php echo htmlspecialchars($art['country']); ?></td>
                                        <td><a href="admin.php?delete_artist=<?php echo $art['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apagar?');">Apagar</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="eventos">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-success">
                        <div class="card-header bg-success text-white">Adicionar Evento</div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="add_event" value="1">
                                <div class="mb-2"><input type="text" name="nome" class="form-control form-control-sm" required placeholder="Nome do Evento"></div>
                                <div class="mb-2">
                                    <select name="tipo" class="form-select form-select-sm" required>
                                        <option value="">Escolher Tipo...</option>
                                        <option value="Academic ceremony">Cerimónia Académica</option>
                                        <option value="Concert">Concerto</option>
                                        <option value="Cultural activity">Atividade Cultural</option>
                                    </select>
                                </div>
                                <div class="mb-2"><input type="datetime-local" name="data_hora" class="form-control form-control-sm" required></div>
                                <div class="mb-2"><input type="text" name="localizacao" class="form-control form-control-sm" required placeholder="Localização"></div>
                                <div class="mb-2">
                                    <select name="tent_id" class="form-select form-select-sm">
                                        <option value="">Associar Barraca (Opcional)</option>
                                        <?php foreach($tendas as $tenda): ?>
                                            <option value="<?php echo $tenda['id']; ?>"><?php echo htmlspecialchars($tenda['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3"><textarea name="descricao" class="form-control form-control-sm" rows="2" placeholder="Descrição (Opcional)"></textarea></div>
                                <button type="submit" class="btn btn-success btn-sm w-100">Guardar Evento</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead><tr><th>Nome</th><th>Data/Hora</th><th>Local</th><th>Tipo</th><th>Ações</th></tr></thead>
                                <tbody>
                                    <?php foreach($eventos as $evento): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($evento['name']); ?></strong></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($evento['date_time'])); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($evento['location']); ?>
                                            <?php if($evento['tent_name']): ?><br><span class="badge bg-secondary"><?php echo htmlspecialchars($evento['tent_name']); ?></span><?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($evento['type']); ?></span></td>
                                        <td><a href="admin.php?delete_event=<?php echo $evento['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apagar?');">Apagar</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="barracas">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-warning">
                        <div class="card-header bg-warning text-dark">Adicionar Barraca</div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="add_tent" value="1">
                                <div class="mb-2"><input type="text" name="nome" class="form-control form-control-sm" required placeholder="Nome da Barraca"></div>
                                <div class="mb-2">
                                    <select name="faculty_id" class="form-select form-select-sm" required>
                                        <option value="">Escolher Faculdade...</option>
                                        <?php foreach($faculdades as $fac): ?>
                                            <option value="<?php echo $fac['id']; ?>"><?php echo htmlspecialchars($fac['name'] . ' (' . $fac['acronym'] . ')'); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-2"><input type="text" name="localizacao" class="form-control form-control-sm" required placeholder="Localização"></div>
                                
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <label class="form-label" style="font-size: 0.8rem;">Abertura</label>
                                        <input type="time" name="abertura" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" style="font-size: 0.8rem;">Fecho</label>
                                        <input type="time" name="fecho" class="form-control form-control-sm" required>
                                    </div>
                                </div>

                                <div class="mb-3"><textarea name="descricao" class="form-control form-control-sm" rows="2" placeholder="Descrição"></textarea></div>
                                <button type="submit" class="btn btn-warning btn-sm w-100">Guardar Barraca</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <table class="table table-hover table-sm">
                                <thead><tr><th>Nome</th><th>Faculdade</th><th>Horário</th><th>Local</th><th>Ações</th></tr></thead>
                                <tbody>
                                    <?php foreach($tendas_detalhadas as $tenda): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($tenda['name']); ?></strong></td>
                                        <td><span class="badge bg-dark"><?php echo htmlspecialchars($tenda['fac_acronym']); ?></span></td>
                                        <td><?php echo date('H:i', strtotime($tenda['opening_hours'])) . ' - ' . date('H:i', strtotime($tenda['closing_hours'])); ?></td>
                                        <td><?php echo htmlspecialchars($tenda['location']); ?></td>
                                        <td><a href="admin.php?delete_tent=<?php echo $tenda['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apagar?');">Apagar</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>