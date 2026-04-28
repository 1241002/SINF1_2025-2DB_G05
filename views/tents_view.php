<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Barracas - Queima '26</title>
    <!-- Bootstrap e JS para o menu funcionar em telemóveis -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Fontes e CSS Principal -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-container">

<!-- NAVBAR COMPLETA E CONSISTENTE -->
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
                <!-- Saudação Personalizada e Link para Perfil (Requisito Global) -->
                <li class="nav-item me-3">
                    <span class="text-secondary small fw-bold text-uppercase">Olá, 
                        <a href="profile.php" class="text-white text-decoration-underline">
                            <?php echo htmlspecialchars($user_name); ?>
                        </a>
                    </span>
                </li>

                <!-- Links de Navegação Principal -->
                <li class="nav-item">
                    <a href="index.php" class="nav-link fw-bold text-uppercase small px-3">📋 Programa</a>
                </li>
                <li class="nav-item">
                    <a href="tents.php" class="nav-link fw-bold text-uppercase small px-3 active" style="color: var(--neon-green);">⛺ Barracas</a>
                </li>
                <li class="nav-item">
                    <a href="agenda.php" class="nav-link fw-bold text-uppercase small px-3">📅 Agenda</a>
                </li>

                <!-- Painel Admin (Apenas para Administradores) -->
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
    <div class="mb-5">
        <h2 class="highlight-title">Roteiro das Barracas</h2>
        <p class="text-muted text-uppercase small fw-bold mt-2">Explora as barraquinhas de cada faculdade e deixa a tua nota!</p>
    </div>
    
    <div class="row">
        <?php foreach($tendas as $tenda): ?>
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="card event-card h-100 shadow-lg">
                    
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h4 class="card-title mb-0"><?php echo htmlspecialchars($tenda['name']); ?></h4>
                            <span class="badge bg-dark border border-secondary text-uppercase" style="letter-spacing: 1px;">
                                <?php echo htmlspecialchars($tenda['fac_acronym']); ?>
                            </span>
                        </div>

                        <div class="mb-4">
                            <p class="small text-secondary mb-1">📍 LOCALIZAÇÃO</p>
                            <p class="fw-bold"><?php echo htmlspecialchars($tenda['location']); ?></p>
                            
                            <p class="small text-secondary mb-1 mt-3">🕒 HORÁRIO DE FUNCIONAMENTO</p>
                            <p class="fw-bold" style="color: var(--neon-blue)">
                                <?php echo date('H:i', strtotime($tenda['opening_hours'])) . ' — ' . date('H:i', strtotime($tenda['closing_hours'])); ?>
                            </p>
                        </div>

                        <div class="p-3 rounded mb-4" style="background: rgba(255,255,255,0.03); min-height: 80px;">
                            <p class="small text-muted mb-0 italic">
                                "<?php echo htmlspecialchars($tenda['description']); ?>"
                            </p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fs-4 fw-bold text-warning">⭐ <?php echo number_format($tenda['media_rating'], 1); ?></span>
                            <span class="small text-muted text-uppercase"><?php echo $tenda['total_votos']; ?> votos</span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <!-- Formulário de Avaliação (Escala 1-5) -->
                        <form method="POST" class="d-flex gap-2">
                            <input type="hidden" name="rate_tent" value="1">
                            <input type="hidden" name="tent_id" value="<?php echo $tenda['id']; ?>">
                            
                            <select name="rating_value" class="form-select bg-dark text-white border-secondary small" required>
                                <option value="">Nota...</option>
                                <?php 
                                $minha_nota_atual = isset($meus_ratings[$tenda['id']]) ? $meus_ratings[$tenda['id']] : null;
                                for($i=5; $i>=1; $i--): 
                                    $selected = ($minha_nota_atual == $i) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?> Estrelas</option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm fw-bold px-3" style="background: var(--neon-green); border:none; color:black;">
                                AVALIAR
                            </button>
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