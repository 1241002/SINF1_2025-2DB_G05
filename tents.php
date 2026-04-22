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
// LÓGICA DE AVALIAÇÃO (RATING DAS BARRACAS)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rate_tent'])) {
    $tent_id = $_POST['tent_id'];
    $nota = (int)$_POST['rating_value'];

    // Verifica se o utilizador já votou nesta barraca
    $stmt_check = $pdo->prepare("SELECT id FROM Rating WHERE user_id = ? AND tent_id = ?");
    $stmt_check->execute([$user_id, $tent_id]);
    $ja_votou = $stmt_check->fetch();

    if ($ja_votou) {
        $pdo->prepare("UPDATE Rating SET value = ? WHERE id = ?")->execute([$nota, $ja_votou['id']]);
    } else {
        $pdo->prepare("INSERT INTO Rating (user_id, tent_id, value) VALUES (?, ?, ?)")->execute([$user_id, $tent_id, $nota]);
    }
    header("Location: tents.php");
    exit();
}

// ==========================================
// IR BUSCAR DADOS DAS BARRACAS
// ==========================================
$stmt_tendas = $pdo->query("
    SELECT 
        Tent.*, 
        Faculty.name AS fac_name,
        Faculty.acronym AS fac_acronym,
        COALESCE(AVG(Rating.value), 0) AS media_rating,
        COUNT(Rating.id) AS total_votos
    FROM Tent 
    LEFT JOIN Faculty ON Tent.faculty_id = Faculty.id 
    LEFT JOIN Rating ON Tent.id = Rating.tent_id
    GROUP BY Tent.id
    ORDER BY Tent.name ASC
");
$tendas = $stmt_tendas->fetchAll(PDO::FETCH_ASSOC);

// Buscar as avaliações que ESTE utilizador já deu às barracas
$meus_ratings = $pdo->prepare("SELECT tent_id, value FROM Rating WHERE user_id = ? AND tent_id IS NOT NULL");
$meus_ratings->execute([$user_id]);
$meus_ratings = $meus_ratings->fetchAll(PDO::FETCH_KEY_PAIR); // Fica no formato: [tent_id => nota]

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Barracas - Queima das Fitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand">🔥 Queima das Fitas 2026</span>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Olá, <strong><?php echo htmlspecialchars($user_name); ?></strong></span>
            
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">Programa</a>
            <a href="agenda.php" class="btn btn-outline-light btn-sm me-2">Minha Agenda</a>
            
            <?php if($is_admin): ?>
                <a href="admin.php" class="btn btn-warning btn-sm me-2">Painel Admin</a>
            <?php endif; ?>
            
            <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4">⛺ Roteiro das Barracas</h2>
    
    <div class="row">
        <?php foreach($tendas as $tenda): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0 border-top border-warning border-4">
                    <div class="card-body bg-white">
                        <h5 class="card-title text-dark fw-bold"><?php echo htmlspecialchars($tenda['name']); ?></h5>
                        <h6 class="card-subtitle mb-3 text-muted">Faculdade: <?php echo htmlspecialchars($tenda['fac_acronym']); ?></h6>
                        
                        <p class="card-text mb-1"><strong>📍 Local:</strong> <?php echo htmlspecialchars($tenda['location']); ?></p>
                        <p class="card-text mb-1">
                            <strong>🕒 Horário:</strong> 
                            <?php echo date('H:i', strtotime($tenda['opening_hours'])) . ' - ' . date('H:i', strtotime($tenda['closing_hours'])); ?>
                        </p>
                        <p class="card-text mt-3"><small><?php echo htmlspecialchars($tenda['description']); ?></small></p>

                        <div class="mt-3 p-2 bg-light rounded text-center">
                            <h4 class="mb-0 text-warning">
                                ⭐ <?php echo number_format($tenda['media_rating'], 1); ?> 
                                <small class="text-muted fs-6">(<?php echo $tenda['total_votos']; ?> votos)</small>
                            </h4>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 p-3">
                        <form method="POST" class="d-flex align-items-center">
                            <input type="hidden" name="rate_tent" value="1">
                            <input type="hidden" name="tent_id" value="<?php echo $tenda['id']; ?>">
                            
                            <select name="rating_value" class="form-select form-select-sm me-2" required>
                                <option value="">Dar Nota...</option>
                                <?php 
                                $minha_nota_atual = isset($meus_ratings[$tenda['id']]) ? $meus_ratings[$tenda['id']] : null;
                                for($i=5; $i>=1; $i--): 
                                    $selected = ($minha_nota_atual == $i) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?> Estrelas</option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" class="btn btn-outline-warning btn-sm fw-bold w-100">Avaliar</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>