<?php
session_start();
require_once 'db.php';

// Proteção: Se não houver sessão, vai para o login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$is_admin = ($_SESSION['user_role'] == 1);

// ==========================================
// 1. LÓGICA DA AGENDA PESSOAL
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_agenda'])) {
    $evento_id = $_POST['evento_id'];
    if ($_POST['acao'] == 'add') {
        $pdo->prepare("INSERT IGNORE INTO PersonalAgenda (user_id, event_id) VALUES (?, ?)")->execute([$user_id, $evento_id]);
    } else {
        $pdo->prepare("DELETE FROM PersonalAgenda WHERE user_id = ? AND event_id = ?")->execute([$user_id, $evento_id]);
    }
    header("Location: index.php");
    exit();
}

// ==========================================
// 2. LÓGICA DE AVALIAÇÃO (RATING)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rate_event'])) {
    $evento_id = $_POST['evento_id'];
    $nota = (int)$_POST['rating_value'];

    $stmt_check = $pdo->prepare("SELECT id FROM Rating WHERE user_id = ? AND event_id = ?");
    $stmt_check->execute([$user_id, $evento_id]);
    $ja_votou = $stmt_check->fetch();

    if ($ja_votou) {
        $pdo->prepare("UPDATE Rating SET value = ? WHERE id = ?")->execute([$nota, $ja_votou['id']]);
    } else {
        $pdo->prepare("INSERT INTO Rating (user_id, event_id, value) VALUES (?, ?, ?)")->execute([$user_id, $evento_id, $nota]);
    }
    header("Location: index.php");
    exit();
}

// ==========================================
// 3. IR BUSCAR DADOS
// ==========================================
$stmt_eventos = $pdo->query("
    SELECT 
        Event.*, 
        Tent.name AS tent_name,
        COALESCE(AVG(Rating.value), 0) AS media_rating,
        COUNT(Rating.id) AS total_votos
    FROM Event 
    LEFT JOIN Tent ON Event.tent_id = Tent.id 
    LEFT JOIN Rating ON Event.id = Rating.event_id
    GROUP BY Event.id
    ORDER BY Event.date_time ASC
");
$eventos = $stmt_eventos->fetchAll(PDO::FETCH_ASSOC);

$minha_agenda = $pdo->prepare("SELECT event_id FROM PersonalAgenda WHERE user_id = ?");
$minha_agenda->execute([$user_id]);
$minha_agenda = $minha_agenda->fetchAll(PDO::FETCH_COLUMN);

$meus_ratings = $pdo->prepare("SELECT event_id, value FROM Rating WHERE user_id = ? AND event_id IS NOT NULL");
$meus_ratings->execute([$user_id]);
$meus_ratings = $meus_ratings->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Programa - Queima das Fitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand">🔥 Queima das Fitas 2026</span>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Olá, <a href="profile.php" class="text-white text-decoration-underline fw-bold"><?php echo htmlspecialchars($user_name); ?></a></span>
            
            <a href="index.php" class="btn btn-light text-primary btn-sm me-2 fw-bold">📋 Programa</a>
            <a href="tents.php" class="btn btn-outline-light btn-sm me-2">⛺ Barracas</a>
            <a href="agenda.php" class="btn btn-outline-light btn-sm me-2">📅 Minha Agenda</a>
            
            <?php if($is_admin): ?><a href="admin.php" class="btn btn-warning btn-sm me-2">Painel Admin</a><?php endif; ?>
            <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">Programa Oficial</h2>
    
    <div class="row">
        <?php foreach($eventos as $evento): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body bg-white">
                        <h5 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($evento['name']); ?></h5>
                        <h6 class="card-subtitle mb-3 text-muted"><?php echo date('d/m/Y - H:i', strtotime($evento['date_time'])); ?></h6>
                        
                        <p class="card-text mb-1"><strong>📍 Local:</strong> <?php echo htmlspecialchars($evento['location']); ?></p>
                        <?php if($evento['tent_name']): ?>
                            <p class="card-text mb-1"><strong>🎪 Barraca:</strong> <?php echo htmlspecialchars($evento['tent_name']); ?></p>
                        <?php endif; ?>
                        
                        <div class="mt-3 p-2 bg-light rounded text-center">
                            <h4 class="mb-0 text-warning">
                                ⭐ <?php echo number_format($evento['media_rating'], 1); ?> 
                                <small class="text-muted fs-6">(<?php echo $evento['total_votos']; ?> votos)</small>
                            </h4>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 p-3">
                        
                        <form method="POST" class="mb-3 d-flex align-items-center">
                            <input type="hidden" name="rate_event" value="1">
                            <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                            
                            <select name="rating_value" class="form-select form-select-sm me-2" required>
                                <option value="">Dar Nota...</option>
                                <?php 
                                $minha_nota_atual = isset($meus_ratings[$evento['id']]) ? $meus_ratings[$evento['id']] : null;
                                for($i=5; $i>=1; $i--): 
                                    $selected = ($minha_nota_atual == $i) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?> Estrelas</option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" class="btn btn-outline-warning btn-sm fw-bold">Avaliar</button>
                        </form>

                        <form method="POST">
                            <input type="hidden" name="toggle_agenda" value="1">
                            <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                            <?php if(in_array($evento['id'], $minha_agenda)): ?>
                                <input type="hidden" name="acao" value="remove">
                                <button type="submit" class="btn btn-danger btn-sm w-100">❌ Remover da Agenda</button>
                            <?php else: ?>
                                <input type="hidden" name="acao" value="add">
                                <button type="submit" class="btn btn-success btn-sm w-100">⭐ Adicionar à Agenda</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>