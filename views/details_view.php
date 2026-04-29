<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - Queima '26</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-hero { position: relative; height: 400px; overflow: hidden; border-radius: 20px; }
        .detail-hero img { width: 100%; height: 100%; object-fit: cover; }
        .detail-hero-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 60px 40px 40px; background: linear-gradient(transparent, #000); }
        .artist-card-mini { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 15px; transition: all 0.3s; }
        .artist-card-mini:hover { border-color: var(--neon-blue); transform: translateY(-3px); }
        .comment-item { background: rgba(255,255,255,0.03); border-radius: 12px; padding: 15px; margin-bottom: 10px; }
    </style>
</head>
<body class="login-container">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <a href="javascript:history.back()" class="btn btn-outline-light btn-sm mb-4 fw-bold px-3 py-2" style="border-radius: 10px; border-color: rgba(255,255,255,0.2);">← VOLTAR ATRÁS</a>

            <!-- HERO IMAGE -->
            <div class="detail-hero shadow-lg mb-5">
                <?php if($type === 'event'): ?>
                    <img src="uploads/<?php echo htmlspecialchars($data['image'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($data['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=1200&auto=format'">
                <?php elseif($type === 'artist'): ?>
                    <img src="uploads/<?php echo htmlspecialchars($data['image'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($data['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1493225255756-d9584f8606e9?w=1200&auto=format'">
                <?php else: ?>
                    <img src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&auto=format" alt="Tent">
                <?php endif; ?>

                <div class="detail-hero-overlay">
                    <?php if($type === 'artist'): ?>
                        <span class="badge bg-info text-dark mb-2 text-uppercase fw-bold"><?php echo htmlspecialchars($data['musical_genre']); ?></span>
                        <h1 class="display-4 fw-black text-white m-0" style="letter-spacing: -2px;"><?php echo htmlspecialchars($data['name']); ?></h1>
                        <p class="text-muted fw-bold mt-2">🌍 <?php echo htmlspecialchars($data['country']); ?></p>
                    <?php elseif($type === 'tent'): ?>
                        <span class="badge bg-dark border border-secondary mb-2 text-uppercase fw-bold"><?php echo htmlspecialchars($data['fac_acronym']); ?></span>
                        <h1 class="display-4 fw-black text-white m-0" style="letter-spacing: -2px;"><?php echo htmlspecialchars($data['name']); ?></h1>
                        <p class="text-muted fw-bold mt-2">📍 <?php echo htmlspecialchars($data['location']); ?></p>
                    <?php elseif($type === 'event'): ?>
                        <span class="badge bg-dark border border-secondary mb-2 text-uppercase fw-bold"><?php echo htmlspecialchars($data['type']); ?></span>
                        <h1 class="display-4 fw-black text-white m-0" style="letter-spacing: -2px;"><?php echo htmlspecialchars($data['name']); ?></h1>
                        <p class="text-muted fw-bold mt-2">📅 <?php echo date('d \d\e F \d\e Y \à\s H:i', strtotime($data['date_time'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card event-card shadow-lg overflow-hidden">
                <div class="card-body p-5">

                    <?php if($type === 'artist'): ?>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--neon-green);">
                                    <p class="text-secondary small fw-bold text-uppercase mb-1">País de Origem</p>
                                    <p class="fs-5 fw-bold text-white"><?php echo htmlspecialchars($data['country']); ?></p>
                                </div>
                                <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--neon-blue);">
                                    <p class="text-secondary small fw-bold text-uppercase mb-1">Género Musical</p>
                                    <p class="fs-5 fw-bold text-white"><?php echo htmlspecialchars($data['musical_genre']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h5 class="text-white fw-bold mb-3" style="font-family: 'Syne', sans-serif; color: var(--neon-blue) !important;">BIOGRAFIA</h5>
                                <p class="text-secondary lh-lg" style="text-align: justify;"><?php echo nl2br(htmlspecialchars($data['short_biography'])); ?></p>
                            </div>
                        </div>

                    <?php elseif($type === 'tent'): ?>
                        <div class="row">
                            <div class="col-md-5 mb-4">
                                <div class="p-4 rounded mb-3" style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--neon-green);">
                                    <p class="text-secondary small fw-bold text-uppercase mb-2">📍 LOCALIZAÇÃO</p>
                                    <p class="text-white fw-bold m-0"><?php echo htmlspecialchars($data['location']); ?></p>
                                </div>
                                <div class="p-4 rounded mb-3" style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--neon-blue);">
                                    <p class="text-secondary small fw-bold text-uppercase mb-2">⏰ HORÁRIO</p>
                                    <p class="text-white fw-bold m-0"><?php echo date('H:i', strtotime($data['opening_hours'])); ?> — <?php echo date('H:i', strtotime($data['closing_hours'])); ?></p>
                                </div>
                                <div class="p-4 rounded" style="background: rgba(255,255,255,0.03); border-left: 4px solid #ffc107;">
                                    <p class="text-secondary small fw-bold text-uppercase mb-2">⭐ RATING MÉDIO</p>
                                    <p class="text-white fw-bold m-0 fs-4"><?php echo number_format($ratings_summary['media'] ?? 0, 1); ?> <small class="text-muted fs-6">(<?php echo $ratings_summary['total'] ?? 0; ?> votos)</small></p>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h5 class="text-white fw-bold mb-3" style="font-family: 'Syne', sans-serif;">SOBRE A BARRACA</h5>
                                <p class="text-secondary lh-lg"><?php echo nl2br(htmlspecialchars($data['description'])); ?></p>
                                <p class="mt-4 text-muted small text-uppercase fw-bold">Representa: <span class="text-white"><?php echo htmlspecialchars($data['fac_name']); ?></span></p>
                            </div>
                        </div>

                        <?php if(!empty($comentarios)): ?>
                            <hr class="border-secondary my-4">
                            <h5 class="text-white fw-bold mb-3" style="font-family: 'Syne', sans-serif;">💬 COMENTÁRIOS</h5>
                            <?php foreach($comentarios as $com): ?>
                                <div class="comment-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-white small"><?php echo htmlspecialchars($com['user_name']); ?></span>
                                        <span class="text-warning small"><?php echo str_repeat('★', $com['value']); ?></span>
                                    </div>
                                    <p class="text-secondary small mb-0"><?php echo htmlspecialchars($com['comment']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    <?php elseif($type === 'event'): ?>
                        <div class="row">
                            <div class="col-md-5 mb-4">
                                <div class="p-4 rounded mb-3" style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--neon-green);">
                                    <p class="text-secondary small fw-bold text-uppercase mb-2">📍 LOCALIZAÇÃO</p>
                                    <p class="text-white fw-bold m-0"><?php echo htmlspecialchars($data['location']); ?></p>
                                </div>
                                <div class="p-4 rounded mb-3" style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--neon-blue);">
                                    <p class="text-secondary small fw-bold text-uppercase mb-2">📅 DATA E HORA</p>
                                    <p class="text-white fw-bold m-0"><?php echo date('d/m/Y H:i', strtotime($data['date_time'])); ?></p>
                                </div>
                                <?php if($data['tent_name']): ?>
                                <div class="p-4 rounded mb-3" style="background: rgba(255,255,255,0.03); border-left: 4px solid #ff00ff;">
                                    <p class="text-secondary small fw-bold text-uppercase mb-2">🎪 BARRACA</p>
                                    <a href="details.php?type=tent&id=<?php echo $data['tent_id_real']; ?>" class="text-white fw-bold m-0 text-decoration-none"><?php echo htmlspecialchars($data['tent_name']); ?> →</a>
                                </div>
                                <?php endif; ?>
                                <div class="p-4 rounded" style="background: rgba(255,255,255,0.03); border-left: 4px solid #ffc107;">
                                    <p class="text-secondary small fw-bold text-uppercase mb-2">⭐ RATING MÉDIO</p>
                                    <p class="text-white fw-bold m-0 fs-4"><?php echo number_format($ratings_summary['media'] ?? 0, 1); ?> <small class="text-muted fs-6">(<?php echo $ratings_summary['total'] ?? 0; ?> votos)</small></p>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h5 class="text-white fw-bold mb-3" style="font-family: 'Syne', sans-serif;">SOBRE O EVENTO</h5>
                                <p class="text-secondary lh-lg"><?php echo nl2br(htmlspecialchars($data['description'] ?? 'Sem descrição disponível.')); ?></p>
                            </div>
                        </div>

                        <?php if(!empty($artistas)): ?>
                            <hr class="border-secondary my-4">
                            <h5 class="text-white fw-bold mb-3" style="font-family: 'Syne', sans-serif;">🎤 ARTISTAS</h5>
                            <div class="row g-3">
                                <?php foreach($artistas as $art): ?>
                                    <div class="col-md-6">
                                        <a href="details.php?type=artist&id=<?php echo $art['id']; ?>" class="text-decoration-none">
                                            <div class="artist-card-mini d-flex align-items-center gap-3">
                                                <img src="uploads/<?php echo htmlspecialchars($art['image'] ?? 'default.jpg'); ?>" class="rounded-circle" style="width:50px;height:50px;object-fit:cover;" onerror="this.src='https://images.unsplash.com/photo-1493225255756-d9584f8606e9?w=100&auto=format'">
                                                <div>
                                                    <p class="fw-bold text-white mb-0"><?php echo htmlspecialchars($art['name']); ?></p>
                                                    <p class="text-muted small mb-0"><?php echo htmlspecialchars($art['musical_genre']); ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($comentarios)): ?>
                            <hr class="border-secondary my-4">
                            <h5 class="text-white fw-bold mb-3" style="font-family: 'Syne', sans-serif;">💬 COMENTÁRIOS</h5>
                            <?php foreach($comentarios as $com): ?>
                                <div class="comment-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-white small"><?php echo htmlspecialchars($com['user_name']); ?></span>
                                        <span class="text-warning small"><?php echo str_repeat('★', $com['value']); ?></span>
                                    </div>
                                    <p class="text-secondary small mb-0"><?php echo htmlspecialchars($com['comment']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>

            <div class="text-center mt-5">
                <p class="text-secondary small">&copy; 2026 QUEIMA DAS FITAS DO PORTO — SINF1 PROJECT</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>