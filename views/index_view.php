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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Programa Oficial</h2>
        
        <!-- REQUISITO: ORDENAÇÃO COM JAVASCRIPT -->
        <form method="GET" class="d-flex align-items-center">
            <label class="me-2 fw-bold text-secondary text-nowrap">Ordenar por:</label>
            <select name="sort" class="form-select form-select-sm w-auto border-primary" onchange="this.form.submit()">
                <option value="data" <?php if($ordenacao == 'data') echo 'selected'; ?>>📅 Data (Mais Próximos)</option>
                <option value="popularidade" <?php if($ordenacao == 'popularidade') echo 'selected'; ?>>🔥 Popularidade (+ Votados)</option>
                <option value="rating" <?php if($ordenacao == 'rating') echo 'selected'; ?>>⭐ Melhor Classificação</option>
            </select>
        </form>
    </div>
    
    <div class="row">
        <?php foreach($eventos as $evento): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body bg-white">
                        
                        <!-- REQUISITO: LÓGICA DO ALERTA DE 48 HORAS -->
                        <?php 
                            $event_time = strtotime($evento['date_time']);
                            $agora = time();
                            $diferenca_horas = ($event_time - $agora) / 3600; 
                        ?>

                        <h5 class="card-title text-primary fw-bold">
                            <?php echo htmlspecialchars($evento['name']); ?>
                            
                            <?php if($diferenca_horas > 0 && $diferenca_horas <= 48): ?>
                                <span class="badge bg-danger ms-2 align-middle text-uppercase" style="font-size: 0.7rem;">
                                    ⏳ Menos de 48h!
                                </span>
                            <?php endif; ?>
                        </h5>

                        <h6 class="card-subtitle mb-3 text-muted"><?php echo date('d/m/Y - H:i', strtotime($evento['date_time'])); ?></h6>
                        
                        <p class="card-text mb-1"><strong>📍 Local:</strong> <?php echo htmlspecialchars($evento['location']); ?></p>
                        
                        <!-- REQUISITO: INTERATIVIDADE (LINK PARA DETALHES) -->
                        <?php if($evento['tent_name']): ?>
                            <p class="card-text mb-1">
                                <strong>🎪 Barraca:</strong> 
                                <a href="details.php?type=tent&id=<?php echo $evento['tent_id']; ?>" class="text-decoration-none fw-bold text-success">
                                    <?php echo htmlspecialchars($evento['tent_name']); ?>
                                </a>
                            </p>
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