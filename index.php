<?php 
// 1. Incluir a ligação à base de dados
require_once 'db.php'; 

// 2. Ir buscar os eventos à base de dados
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

<div class="container mt-5">
    <h1 class="text-center mb-4">Programa da Queima das Fitas</h1>
    
    <div class="row">
        <?php foreach($eventos as $evento): ?>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $evento['name']; ?></h5>
                        <p class="card-text text-muted"><?php echo $evento['type']; ?></p>
                        <p class="card-text"><?php echo $evento['location']; ?></p>
                        <small class="text-primary"><?php echo $evento['date_time']; ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>