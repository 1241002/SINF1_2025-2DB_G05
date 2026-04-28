<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Queima '26 - Programa</title>
    <!-- Bootstrap e JS para o menu funcionar em telemóveis -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR CORRIGIDA -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fs-2 fw-bold" href="index.php">QUEIMA<span style="color:var(--neon-blue)">'26</span></a>
        
        <!-- Botão Hambúrguer para Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Conteúdo da Navbar -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Nome do Utilizador e Link para Perfil -->
                <li class="nav-item me-3">
                    <span class="text-secondary small fw-bold text-uppercase">Olá, 
                        <a href="profile.php" class="text-white text-decoration-underline">
                            <?php echo htmlspecialchars($user_name); ?>
                        </a>
                    </span>
                </li>

                <!-- Links de Navegação (Sempre visíveis aqui) -->
                <li class="nav-item">
                    <a href="index.php" class="nav-link fw-bold text-uppercase small px-3">📋 Programa</a>
                </li>
                <li class="nav-item">
                    <a href="tents.php" class="nav-link fw-bold text-uppercase small px-3">⛺ Barracas</a>
                </li>
                <li class="nav-item">
                    <a href="agenda.php" class="nav-link fw-bold text-uppercase small px-3">📅 Agenda</a>
                </li>

                <!-- Acesso Admin -->
                <?php if($is_admin): ?>
                    <li class="nav-item ms-lg-2">
                        <a href="admin.php" class="btn btn-sm btn-outline-warning fw-bold px-3">ADMIN</a>
                    </li>
                <?php endif; ?>

                <!-- Botão Sair -->
                <li class="nav-item ms-lg-3">
                    <a href="logout.php" class="btn btn-bilhetes">SAIR</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-4">
    <!-- Cabeçalho com Ordenação -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <h2 class="highlight-title mb-0">Programa Oficial</h2>
        <form method="GET" class="bg-dark rounded-pill px-3 py-1 border border-secondary">
            <select name="sort" class="form-select bg-transparent text-white border-0 small" onchange="this.form.submit()">
                <option value="data" <?php if($ordenacao == 'data') echo 'selected'; ?>>DATA</option>
                <option value="popularidade" <?php if($ordenacao == 'popularidade') echo 'selected'; ?>>POPULARIDADE</option>
                <option value="rating" <?php if($ordenacao == 'rating') echo 'selected'; ?>>MELHOR RATING</option>
            </select>
        </form>
    </div>

    <!-- Grelha de Eventos -->
    <div class="row">
        <?php foreach($eventos as $evento): ?>
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="card event-card h-100 shadow-lg">
                    <div class="event-img-container">
                        <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=500&auto=format" class="event-img" alt="Festival">
                        <?php 
                            $event_time = strtotime($evento['date_time']);
                            $diferenca_horas = ($event_time - time()) / 3600; 
                            if($diferenca_horas > 0 && $diferenca_horas <= 48): 
                        ?>
                            <span class="badge badge-48h position-absolute top-0 end-0 m-3">PRÓXIMO</span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body p-4">
                        <h4 class="card-title mb-1"><?php echo htmlspecialchars($evento['name']); ?></h4>
                        <p class="text-neon-blue small fw-bold mb-3" style="color: var(--neon-blue)">
                            <?php echo date('d M | H:i', strtotime($evento['date_time'])); ?>
                        </p>
                        
                        <p class="small text-secondary mb-4">
                            📍 <?php echo htmlspecialchars($evento['location']); ?>
                            <?php if($evento['tent_name']): ?>
                                <br>🎪 <a href="details.php?type=tent&id=<?php echo $evento['tent_id']; ?>" class="text-success text-decoration-none fw-bold">
                                    <?php echo htmlspecialchars($evento['tent_name']); ?>
                                </a>
                            <?php endif; ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-4 fw-bold text-warning">⭐ <?php echo number_format($evento['media_rating'], 1); ?></span>
                            <span class="small text-muted text-uppercase"><?php echo $evento['total_votos']; ?> votos</span>
                        </div>
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
                                <button type="submit" class="btn btn-primary w-100 fw-bold">ADICIONAR À AGENDA</button>
                            <?php endif; ?>
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