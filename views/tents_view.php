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
            <span class="me-3">Olá, <a href="profile.php" class="text-white text-decoration-underline fw-bold"><?php echo htmlspecialchars($user_name); ?></a></span>
            
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">📋 Programa</a>
            <a href="tents.php" class="btn btn-light text-primary btn-sm me-2 fw-bold">⛺ Barracas</a>
            <a href="agenda.php" class="btn btn-outline-light btn-sm me-2">📅 Minha Agenda</a>
            
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