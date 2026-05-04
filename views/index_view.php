<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Queima '26 - Programa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .search-bar { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 50px; padding: 8px 20px; color: white; transition: all 0.3s; }
        .search-bar:focus { outline: none; border-color: var(--neon-blue); box-shadow: 0 0 15px rgba(0,153,255,0.2); background: rgba(255,255,255,0.08); }
        .countdown-box { background: linear-gradient(135deg, rgba(41,255,0,0.1), rgba(0,153,255,0.1)); border: 1px solid rgba(41,255,0,0.3); border-radius: 12px; padding: 8px 16px; font-family: 'Syne', sans-serif; font-size: 0.9rem; }
        .countdown-number { color: var(--neon-green); font-weight: 800; }
        .artist-tag { background: rgba(0,153,255,0.15); color: var(--neon-blue); border: 1px solid rgba(0,153,255,0.3); border-radius: 20px; padding: 2px 10px; font-size: 0.75rem; font-weight: 600; }
        .star-rating { color: #ffc107; cursor: pointer; transition: transform 0.2s; }
        .star-rating:hover { transform: scale(1.2); }
        .filter-select { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 10px; padding: 8px 12px; }
        .filter-select:focus { border-color: var(--neon-blue); outline: none; }
        select option { background-color: #212529 !important; color: white !important; }
        .comment-box { background: rgba(255,255,255,0.03); border-left: 3px solid var(--neon-green); padding: 10px; border-radius: 0 8px 8px 0; margin-top: 10px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fs-2 fw-bold" href="index.php">QUEIMA<span class="text-primary">'26</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <span class="text-secondary small fw-bold text-uppercase">Olá, 
                        <a href="profile.php" class="text-white text-decoration-underline"><?php echo htmlspecialchars($user_name); ?></a>
                    </span>
                </li>
                <li class="nav-item"><a href="index.php" class="nav-link fw-bold text-uppercase small px-3 active" style="color:var(--neon-green)">📋 Programa</a></li>
                <li class="nav-item"><a href="tents.php" class="nav-link fw-bold text-uppercase small px-3">⛺ Barracas</a></li>
                <li class="nav-item"><a href="agenda.php" class="nav-link fw-bold text-uppercase small px-3">📅 Agenda</a></li>
                <?php if($is_admin): ?>
                    <li class="nav-item ms-lg-2"><a href="admin.php" class="btn btn-sm btn-outline-warning fw-bold px-3">ADMIN</a></li>
                <?php endif; ?>
                <li class="nav-item ms-lg-3"><a href="logout.php" class="btn btn-bilhetes">SAIR</a></li>
            </ul>
        </div>
    </div>
</nav>

<section id="hero" style="background: linear-gradient(rgba(10, 10, 15, 0.7), rgba(10, 10, 15, 0.9)), url('uploads/capa.jpg'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 100vh;" class="position-relative d-flex flex-column align-items-center justify-content-center text-center">
    <div class="container">
        <h1 class="display-1 fw-bold text-white mb-2" style="letter-spacing: 2px;">QUEIMA <span class="text-primary">26</span></h1>
        <h2 class="text-white fw-light mb-4">das Fitas do Porto</h2>
        <a href="#programa" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-bold" style="backdrop-filter: blur(5px);">Descobrir o Programa</a>
    </div>
</section>

<section id="programa" class="pt-5" style="min-height: 100vh;">
    <div class="container mt-5 pt-4">
    <!-- Cabeçalho com Pesquisa e Filtros -->
    <div class="row mb-5 align-items-end">
        <div class="col-lg-4 mb-3 mb-lg-0">
            <h2 class="highlight-title mb-0">Programa Oficial</h2>
            <p class="text-muted small fw-bold text-uppercase mt-2">Descobre tudo o que te espera na Queima '26</p>
        </div>
        <div class="col-lg-8">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="search-bar w-100" placeholder="🔍 Pesquisar eventos..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select name="tipo" class="filter-select w-100" onchange="this.form.submit()">
                        <option value="">Todos os Tipos</option>
                        <?php foreach($tipos_evento as $t): ?>
                            <option value="<?php echo htmlspecialchars($t); ?>" <?php if($tipo_filtro==$t) echo 'selected'; ?>><?php echo htmlspecialchars($t); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="faculty" class="filter-select w-100" onchange="this.form.submit()">
                        <option value="">Todas as Faculdades</option>
                        <?php foreach($faculdades as $fac): ?>
                            <option value="<?php echo $fac['id']; ?>" <?php if($faculty_filtro==$fac['id']) echo 'selected'; ?>><?php echo htmlspecialchars($fac['acronym']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="filter-select w-100" onchange="this.form.submit()">
                        <option value="data" <?php if($ordenacao=='data') echo 'selected'; ?>>📅 Data</option>
                        <option value="popularidade" <?php if($ordenacao=='popularidade') echo 'selected'; ?>>🔥 Popular</option>
                        <option value="rating" <?php if($ordenacao=='rating') echo 'selected'; ?>>⭐ Rating</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Grelha de Eventos -->
    <div class="row">
        <?php if(empty($eventos)): ?>
            <div class="col-12 text-center py-5">
                <h3 class="text-white fw-bold" style="font-family:'Syne',sans-serif;">NENHUM EVENTO ENCONTRADO</h3>
                <p class="text-muted">Tenta ajustar os filtros ou a tua pesquisa.</p>
                <a href="index.php" class="btn btn-outline-light mt-3">Limpar Filtros</a>
            </div>
        <?php endif; ?>

        <?php foreach($eventos as $evento): 
            $event_time = strtotime($evento['date_time']);
            $diferenca_horas = ($event_time - time()) / 3600;
            $artistas_nomes = $nomes_artistas_por_evento[$evento['id']] ?? [];
            $evento_passado = $event_time < time();
        ?>
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="card event-card h-100 shadow-lg <?php echo $evento_passado ? 'opacity-50' : ''; ?>">
                    <div class="event-img-container" style="position:relative;">
                        <img src="uploads/<?php echo htmlspecialchars($evento['image'] ?? 'default.jpg'); ?>" class="event-img" alt="<?php echo htmlspecialchars($evento['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=500&auto=format'">

                        <?php if($diferenca_horas > 0 && $diferenca_horas <= 48 && !$evento_passado): ?>
                            <span class="badge badge-48h position-absolute top-0 end-0 m-3">PRÓXIMO</span>
                        <?php endif; ?>

                        <?php if($evento_passado): ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background:rgba(0,0,0,0.6);">
                                <span class="badge bg-dark border border-secondary px-3 py-2">EVENTO REALIZADO</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-dark border border-secondary text-uppercase small"><?php echo htmlspecialchars($evento['type']); ?></span>
                            <?php if(!$evento_passado && $event_time > time()): ?>
                                <div class="countdown-box" data-time="<?php echo $evento['date_time']; ?>">
                                    <span class="countdown-number">--</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h4 class="card-title mb-1">
                            <a href="details.php?type=event&id=<?php echo $evento['id']; ?>" class="text-white text-decoration-none hover-underline">
                                <?php echo htmlspecialchars($evento['name']); ?>
                            </a>
                        </h4>

                        <p class="text-neon-blue small fw-bold mb-2" style="color: var(--neon-blue)">
                            <?php echo date('d M | H:i', strtotime($evento['date_time'])); ?>
                        </p>

                        <?php if(!empty($artistas_nomes)): ?>
                            <div class="mb-3">
                                <?php foreach(array_slice($artistas_nomes, 0, 3) as $an): ?>
                                    <span class="artist-tag">🎤 <?php echo htmlspecialchars($an); ?></span>
                                <?php endforeach; ?>
                                <?php if(count($artistas_nomes) > 3): ?>
                                    <span class="artist-tag">+<?php echo count($artistas_nomes)-3; ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <p class="small text-secondary mb-3">
                            📍 <?php echo htmlspecialchars($evento['location']); ?>
                            <?php if($evento['tent_name']): ?>
                                <br>🎪 <a href="details.php?type=tent&id=<?php echo $evento['tent_id']; ?>" class="text-success text-decoration-none fw-bold"><?php echo htmlspecialchars($evento['tent_name']); ?></a>
                            <?php endif; ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fs-4 fw-bold text-warning">⭐ <?php echo number_format($evento['media_rating'], 1); ?></span>
                            <span class="small text-white-50 text-uppercase"><?php echo $evento['total_votos']; ?> votos</span>
                        </div>

                        <!-- Sistema de Rating com Estrelas -->
                        <form method="POST" class="mb-2">
                            <input type="hidden" name="rate_event" value="1">
                            <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="small text-white-50">Avalia:</span>
                                <select name="rating_value" data-bs-theme="dark" class="form-select form-select-sm bg-dark text-white border-secondary" style="width:auto;" required>
                                    <option value="" class="bg-dark text-white" style="background-color: #212529; color: #fff;">★</option>
                                    <?php $minha_nota = $meus_ratings[$evento['id']] ?? null; ?>
                                    <?php for($i=5; $i>=1; $i--): ?>
                                        <option value="<?php echo $i; ?>" class="bg-dark text-white" style="background-color: #212529; color: #fff;" <?php if($minha_nota==$i) echo 'selected'; ?>><?php echo $i; ?> ★</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <textarea name="rating_comment" class="form-control form-control-sm bg-dark text-white border-secondary" rows="1" placeholder="Comentário opcional..."><?php echo htmlspecialchars($meus_comentarios[$evento['id']] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary w-100 fw-bold">ENVIAR AVALIAÇÃO</button>
                        </form>
                    </div>

                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <form method="POST">
                            <input type="hidden" name="toggle_agenda" value="1">
                            <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                            <?php if(in_array($evento['id'], $minha_agenda)): ?>
                                <input type="hidden" name="acao" value="remove">
                                <button type="submit" class="btn btn-outline-danger w-100 fw-bold">REMOVER DA AGENDA</button>
                            <?php else: ?>
                                <input type="hidden" name="acao" value="add">
                                <button type="submit" class="btn btn-primary w-100 fw-bold" <?php echo $evento_passado ? 'disabled' : ''; ?>>ADICIONAR À AGENDA</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</section>

<footer class="mt-5 py-5 border-top border-secondary text-center text-muted">
    <p class="small">&copy; 2026 QUEIMA DAS FITAS DO PORTO — SINF1 PROJECT</p>
</footer>

<script>
// Countdown dinâmico
function updateCountdowns() {
    document.querySelectorAll('.countdown-box').forEach(box => {
        const target = new Date(box.getAttribute('data-time')).getTime();
        const now = new Date().getTime();
        const diff = target - now;

        if (diff <= 0) {
            box.innerHTML = '<span class="countdown-number">A COMEÇAR!</span>';
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

        let text = '';
        if (days > 0) text += days + 'd ';
        text += hours + 'h ' + minutes + 'm';
        box.querySelector('.countdown-number').textContent = text;
    });
}
setInterval(updateCountdowns, 60000);
updateCountdowns();
</script>
</body>
</html>