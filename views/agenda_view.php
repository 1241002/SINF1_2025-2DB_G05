<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minha Agenda - Queima '26</title>
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
                <!-- Nome do Utilizador e Link para Perfil (Requisito Global) -->
                <li class="nav-item me-3">
                    <span class="text-secondary small fw-bold text-uppercase">Olá, 
                        <a href="profile.php" class="text-white text-decoration-underline">
                            <?php echo htmlspecialchars($user_name); ?>
                        </a>
                    </span>
                </li>

                <!-- Links de Navegação -->
                <li class="nav-item">
                    <a href="index.php" class="nav-link fw-bold text-uppercase small px-3">📋 Programa</a>
                </li>
                <li class="nav-item">
                    <a href="tents.php" class="nav-link fw-bold text-uppercase small px-3">⛺ Barracas</a>
                </li>
                <li class="nav-item">
                    <a href="agenda.php" class="nav-link fw-bold text-uppercase small px-3 active" style="color: var(--neon-blue);">📅 Agenda</a>
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
    <!-- Cabeçalho da Agenda -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="highlight-title">A Minha Agenda</h2>
            <p class="text-muted text-uppercase small fw-bold mt-2">O teu plano exclusivo para a Queima</p>
        </div>
        <a href="index.php" class="btn btn-outline-light btn-sm fw-bold px-4 py-2" style="border-radius: 10px;">
            + ADICIONAR EVENTOS
        </a>
    </div>
    
    <?php if(empty($meus_eventos)): ?>
        <!-- Estado Vazio Estilizado -->
        <div class="card event-card text-center py-5 shadow-lg">
            <div class="card-body">
                <h3 class="fw-bold mb-3" style="font-family: 'Syne', sans-serif;">AINDA NÃO TENS PLANOS?</h3>
                <p class="text-secondary">Explora o Programa Oficial e cria o teu roteiro personalizado para não perderes nada[cite: 1].</p>
                <a href="index.php" class="btn btn-primary mt-3 px-5 py-3 fw-bold">IR PARA O PROGRAMA</a>
            </div>
        </div>
    <?php else: ?>
        <!-- Lista de Eventos na Agenda -->
        <div class="row">
            <?php foreach($meus_eventos as $evento): ?>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="card event-card h-100 shadow-lg">
                        <div class="event-img-container">
                            <img src="uploads/<?php echo htmlspecialchars($evento['image'] ?? 'default.jpg'); ?>" class="event-img" alt="<?php echo htmlspecialchars($evento['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=500&auto=format'">
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-dark py-2 px-3 border border-secondary">
                                    <?php echo date('d M', strtotime($evento['date_time'])); ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <h4 class="card-title mb-1 text-white"><?php echo htmlspecialchars($evento['name']); ?></h4>
                            <p class="small fw-bold mb-3" style="color: var(--neon-blue)">
                                🕒 <?php echo date('H:i', strtotime($evento['date_time'])); ?>
                            </p>
                            
                            <p class="small text-secondary mb-1 text-uppercase fw-bold">📍 Localização</p>
                            <p class="fw-bold mb-3 text-white"><?php echo htmlspecialchars($evento['location']); ?></p>

                            <?php if($evento['tent_name']): ?>
                                <p class="small text-secondary mb-1 text-uppercase fw-bold">🎪 Barraca</p>
                                <!-- Link para detalhes (Requisito de Interatividade) -->
                                <a href="details.php?type=tent&id=<?php echo $evento['tent_id']; ?>" class="fw-bold text-success text-decoration-none d-block mb-0">
                                    <?php echo htmlspecialchars($evento['tent_name']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0 p-4 pt-0">
                            <form method="POST">
                                <input type="hidden" name="remove_agenda" value="1">
                                <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-2 fw-bold" style="border-radius: 10px;">
                                    ❌ REMOVER DA AGENDA[cite: 1]
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<footer class="mt-5 py-5 border-top border-secondary text-center text-muted">
    <p class="small">&copy; 2026 QUEIMA DAS FITAS DO PORTO — O TEU PLANO ACADÉMICO</p>
</footer>

</body>
</html>