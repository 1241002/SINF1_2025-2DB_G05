<?php
session_start();
require_once 'db.php';

// 1. Proteção: Se não houver sessão, vai para o login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Dados: Ir buscar os eventos
$stmt = $pdo->query("SELECT * FROM Event");
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Queima das Fitas - Programa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand">Queima das Fitas 2026</span>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Olá, <strong><?php echo $_SESSION['user_name']; ?></strong></span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="text-center mb-4">Programa de Eventos</h1>
    
    <div class="row">
        <?php foreach($eventos as $evento): ?>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($evento['name']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($evento['type']); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars($evento['location']); ?></p>
                        <p class="card-text"><small class="text-primary"><?php echo $evento['date_time']; ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>