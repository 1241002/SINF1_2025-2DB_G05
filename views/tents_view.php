<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Barracas - Queima '26</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .search-bar { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 50px; padding: 8px 20px; color: white; transition: all 0.3s; width: 100%; }
        .search-bar:focus { outline: none; border-color: var(--neon-blue); box-shadow: 0 0 15px rgba(0,153,255,0.2); background: rgba(255,255,255,0.08); }
        .filter-select { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 10px; padding: 8px 12px; }
        .comment-section { background: rgba(255,255,255,0.02); border-radius: 12px; padding: 15px; margin-top: 15px; max-height: 200px; overflow-y: auto; }
        .comment-item { padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .comment-item:last-child { border-bottom: none; }
    </style>
</head>
<body class="login-container">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fs-2 fw-bold" href="index.php">QUEIMA<span style="color:var(--neon-blue)">'26</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <span class="text-secondary small fw-bold text-uppercase">Olá, <a href="profile.php" class="text-white text-decoration-underline"><?php echo htmlspecialchars($user_name); ?></a></span>
                </li>
                <li class="nav-item"><a href="index.php" class="nav-link fw-bold text-uppercase small px-3">📋 Programa</a></li>
                <li class="nav-item"><a href="tents.php" class="nav-link fw-bold text-uppercase small px-3 active" style="color: var(--neon-green);">⛺ Barracas</a></li>
                <li class="nav-item"><a href="agenda.php" class="nav-link fw-bold text-uppercase small px-3">📅 Agenda</a></li>
                <?php if($is_admin): ?><li class="nav-item ms-lg-2"><a href="admin.php" class="btn btn-sm btn-outline-warning fw-bold px-3">ADMIN</a></li><?php endif; ?>
                <li class="nav-item ms-lg-3"><a href="logout.php" class="btn btn-bilhetes">SAIR</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-4">
    <div class="row mb-5 align-items-end">
        <div class="col-lg-6 mb-3 mb-lg-0">
            <h2 class="highlight-title">Roteiro das Barracas</h2>
            <p class="text-muted text-uppercase small fw-bold mt-2">Explora as barraquinhas e deixa a tua nota!</p>
        </div>
        <div class="col-lg-6">
            <form method="GET" class="row g-2">
                <div class="col-md-7">
                    <input type="text" name="search" class="search-bar" placeholder="🔍 Pesquisar barracas..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select name="faculty" class="filter-select w-100" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        <?php foreach($faculdades as $fac): ?>
                            <option value="<?php echo $fac['id']; ?>" <?php if($faculty_filtro==$fac['id']) echo 'selected'; ?>><?php echo htmlspecialchars($fac['acronym']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-light w-100 fw-bold btn-sm" style="height:100%;">FILTRAR</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <?php if(empty($tendas)): ?>
            <div class="col-12 text-center py-5">
                <h3 class="text-white fw-bold" style="font-family:'Syne',sans-serif;">NENHUMA BARRACA ENCONTRADA</h3>
                <a href="tents.php" class="btn btn-outline-light mt-3">Limpar Filtros</a>
            </div>
        <?php endif; ?>

        <?php foreach($tendas as $tenda): 
            $comentarios_tenda = getTentComments($pdo, $tenda['id']);
        ?>
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="card event-card h-100 shadow-lg">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h4 class="card-title mb-0"><a href="details.php?type=tent&id=<?php echo $tenda['id']; ?>" class="text-white text-decoration-none"><?php echo htmlspecialchars($tenda['name']); ?></a></h4>
                            <span class="badge bg-dark border border-secondary text-uppercase" style="letter-spacing: 1px;"><?php echo htmlspecialchars($tenda['fac_acronym']); ?></span>
                        </div>

                        <div class="mb-4">
                            <p class="small text-secondary mb-1">📍 LOCALIZAÇÃO</p>
                            <p class="fw-bold text-white"><?php echo htmlspecialchars($tenda['location']); ?></p>
                            <p class="small text-secondary mb-1 mt-3">🕒 HORÁRIO</p>
                            <p class="fw-bold" style="color: var(--neon-blue)"><?php echo date('H:i', strtotime($tenda['opening_hours'])) . ' — ' . date('H:i', strtotime($tenda['closing_hours'])); ?></p>
                        </div>

                        <div class="p-3 rounded mb-4" style="background: rgba(255,255,255,0.03); min-height: 80px;">
                            <p class="small text-white-50 mb-0 italic">"<?php echo htmlspecialchars($tenda['description']); ?>"</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fs-4 fw-bold text-warning">⭐ <?php echo number_format($tenda['media_rating'], 1); ?></span>
                            <span class="small text-white-50 text-uppercase"><?php echo $tenda['total_votos']; ?> votos</span>
                        </div>

                        <?php if(!empty($comentarios_tenda)): ?>
                            <div class="comment-section mb-3">
                                <p class="small text-muted fw-bold text-uppercase mb-2">💬 Últimos comentários</p>
                                <?php foreach(array_slice($comentarios_tenda, 0, 2) as $com): ?>
                                    <div class="comment-item">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold text-white small"><?php echo htmlspecialchars($com['user_name']); ?></span>
                                            <span class="text-warning small"><?php echo str_repeat('★', $com['value']); ?></span>
                                        </div>
                                        <p class="text-secondary small mb-0"><?php echo htmlspecialchars($com['comment']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                                <?php if(count($comentarios_tenda) > 2): ?>
                                    <a href="details.php?type=tent&id=<?php echo $tenda['id']; ?>" class="small text-neon-blue text-decoration-none">Ver todos →</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <form method="POST" class="d-flex gap-2 flex-column">
                            <input type="hidden" name="rate_tent" value="1">
                            <input type="hidden" name="tent_id" value="<?php echo $tenda['id']; ?>">
                            <div class="d-flex gap-2">
                                <select name="rating_value" data-bs-theme="dark" class="form-select bg-dark text-white border-secondary" required>
                                    <option value="" class="bg-dark text-white" style="background-color: #212529; color: #fff;">Nota...</option>
                                    <?php $minha_nota = $meus_ratings[$tenda['id']] ?? null; ?>
                                    <?php for($i=5; $i>=1; $i--): ?>
                                        <option value="<?php echo $i; ?>" class="bg-dark text-white" style="background-color: #212529; color: #fff;" <?php if($minha_nota==$i) echo 'selected'; ?>><?php echo $i; ?> Estrelas</option>
                                    <?php endfor; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm fw-bold px-3" style="background: var(--neon-green); border:none; color:black; white-space:nowrap;">AVALIAR</button>
                            </div>
                            <textarea name="rating_comment" class="form-control form-control-sm bg-dark text-white border-secondary" rows="1" placeholder="Comentário opcional..."><?php echo htmlspecialchars($meus_comentarios[$tenda['id']] ?? ''); ?></textarea>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer class="mt-5 py-5 border-top border-secondary text-center text-muted">
    <p class="small">&copy; 2026 QUEIMA DAS FITAS DO PORTO — SINF1 PROJECT</p>
</footer>

</body>
</html>