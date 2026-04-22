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
// REMOVER DA AGENDA
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_agenda'])) {
    $evento_id = $_POST['evento_id'];
    $stmt = $pdo->prepare("DELETE FROM PersonalAgenda WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$user_id, $evento_id]);
    
    header("Location: agenda.php");
    exit();
}

// ==========================================
// IR BUSCAR OS EVENTOS GUARDADOS PELO UTILIZADOR
// ==========================================
$stmt_agenda = $pdo->prepare("
    SELECT Event.*, Tent.name AS tent_name 
    FROM Event 
    INNER JOIN PersonalAgenda ON Event.id = PersonalAgenda.event_id 
    LEFT JOIN Tent ON Event.tent_id = Tent.id 
    WHERE PersonalAgenda.user_id = ?
    ORDER BY Event.date_time ASC
");
$stmt_agenda->execute([$user_id]);
$meus_eventos = $stmt_agenda->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minha Agenda - Queima das Fitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand">🔥 Queima das Fitas 2026</span>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Olá, <a href="profile.php" class="text-white text-decoration-underline fw-bold"><?php echo htmlspecialchars($user_name); ?></a></span>
            
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">📋 Programa</a>
            <a href="tents.php" class="btn btn-outline-light btn-sm me-2">⛺ Barracas</a>
            <a href="agenda.php" class="btn btn-light text-primary btn-sm me-2 fw-bold">📅 Minha Agenda</a>
            
            <?php if($is_admin): ?>
                <a href="admin.php" class="btn btn-warning btn-sm me-2">Painel Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📅 A Minha Agenda Pessoal</h2>
        <a href="index.php" class="btn btn-primary">Adicionar mais eventos</a>
    </div>
    
    <?php if(empty($meus_eventos)): ?>
        <div class="alert alert-info text-center py-5">
            <h4>Ainda não tens nada planeado!</h4>
            <p>Vai ao Programa Oficial e adiciona os eventos que não queres perder.</p>
            <a href="index.php" class="btn btn-outline-primary mt-3">Ir para o Programa</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($meus_eventos as $evento): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-primary h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?php echo htmlspecialchars($evento['name']); ?></h5>
                            <h6 class="card-subtitle mb-3 text-muted">
                                <?php echo date('d/m/Y - H:i', strtotime($evento['date_time'])); ?>
                            </h6>
                            <p class="card-text mb-1"><strong>📍 Local:</strong> <?php echo htmlspecialchars($evento['location']); ?></p>
                            <?php if($evento['tent_name']): ?>
                                <p class="card-text mb-1"><strong>🎪 Barraca:</strong> <?php echo htmlspecialchars($evento['tent_name']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <form method="POST">
                                <input type="hidden" name="remove_agenda" value="1">
                                <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">❌ Remover da Agenda</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>