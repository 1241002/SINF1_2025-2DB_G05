<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Artistas - Queima '26</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .search-bar { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 50px; padding: 8px 20px; color: white; transition: all 0.3s; width: 100%; }
        .search-bar:focus { outline: none; border-color: var(--neon-blue); box-shadow: 0 0 15px rgba(0,153,255,0.2); background: rgba(255,255,255,0.08); }
        .artist-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; transition: all 0.3s; overflow: hidden; }
        .artist-card:hover { border-color: var(--neon-green); transform: translateY(-4px); box-shadow: 0 8px 30px rgba(41,255,0,0.1); }
        .artist-photo { width: 100%; aspect-ratio: 1/1; object-fit: cover; }
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
                <li class="nav-item"><a href="tents.php" class="nav-link fw-bold text-uppercase small px-3">⛺ Barracas</a></li>
                <li class="nav-item"><a href="artists.php" class="nav-link fw-bold text-uppercase small px-3 active" style="color: var(--neon-green);">🎤 Artistas</a></li>
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
            <h2 class="highlight-title">Artistas da Queima</h2>
            <p class="text-muted text-uppercase small fw-bold mt-2"><?php echo count($artistas); ?> artista<?php echo count($artistas) != 1 ? 's' : ''; ?> confirmado<?php echo count($artistas) != 1 ? 's' : ''; ?></p>
        </div>
        <div class="col-lg-6">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="search-bar" placeholder="🔍 Pesquisar artistas, géneros, países..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-outline-light fw-bold px-4" style="white-space:nowrap;">FILTRAR</button>
            </form>
        </div>
    </div>

    <div class="row">
        <?php if(empty($artistas)): ?>
            <div class="col-12 text-center py-5">
                <h3 class="text-white fw-bold" style="font-family:'Syne',sans-serif;">NENHUM ARTISTA ENCONTRADO</h3>
                <a href="artists.php" class="btn btn-outline-light mt-3">Limpar Filtros</a>
            </div>
        <?php endif; ?>

        <?php foreach($artistas as $artista): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <a href="details.php?type=artist&id=<?php echo $artista['id']; ?>" class="text-decoration-none">
                    <div class="artist-card h-100">
                        <img
                            src="uploads/<?php echo htmlspecialchars($artista['image'] ?? 'default.jpg'); ?>"
                            alt="<?php echo htmlspecialchars($artista['name']); ?>"
                            class="artist-photo"
                            onerror="this.src='https://images.unsplash.com/photo-1493225255756-d9584f8606e9?w=400&auto=format'">
                        <div class="p-3">
                            <h5 class="text-white fw-bold mb-1"><?php echo htmlspecialchars($artista['name']); ?></h5>
                            <p class="small mb-1" style="color: var(--neon-blue);"><?php echo htmlspecialchars($artista['musical_genre']); ?></p>
                            <p class="small text-secondary mb-0">🌍 <?php echo htmlspecialchars($artista['country']); ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer class="mt-5 py-5 border-top border-secondary text-center text-muted">
    <p class="small">&copy; 2026 QUEIMA DAS FITAS DO PORTO — SINF1 PROJECT</p>
</footer>

</body>
</html>
