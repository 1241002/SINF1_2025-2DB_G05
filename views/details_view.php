<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - Queima '26</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fontes e CSS Principal -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-container">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Botão Voltar Estilizado -->
            <a href="javascript:history.back()" class="btn btn-outline-light btn-sm mb-4 fw-bold px-3 py-2" style="border-radius: 10px; border-color: rgba(255,255,255,0.2);">
                ← VOLTAR ATRÁS
            </a>

            <!-- Card Principal de Detalhes -->
            <div class="card event-card shadow-lg overflow-hidden">
                
                <!-- Placeholder para Imagem de Cabeçalho -->
                <div class="event-img-container" style="height: 300px; background: linear-gradient(45deg, #111, #222);">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1200&auto=format" class="event-img" alt="Header" style="opacity: 0.6;">
                    <div class="position-absolute bottom-0 start-0 p-5 w-100" style="background: linear-gradient(transparent, var(--queima-black));">
                        <?php if($type === 'artist'): ?>
                            <span class="badge bg-info text-dark mb-2 text-uppercase fw-bold"><?php echo htmlspecialchars($data['musical_genre']); ?></span>
                            <h1 class="display-4 fw-black text-white m-0" style="letter-spacing: -2px; line-height: 1;"><?php echo htmlspecialchars($data['name']); ?></h1>
                        <?php else: ?>
                            <span class="badge bg-dark border border-secondary mb-2 text-uppercase fw-bold"><?php echo htmlspecialchars($data['fac_acronym']); ?></span>
                            <h1 class="display-4 fw-black text-white m-0" style="letter-spacing: -2px; line-height: 1;"><?php echo htmlspecialchars($data['name']); ?></h1>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body p-5">
                    
                    <?php if($type === 'artist'): ?>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-secondary small fw-bold text-uppercase mb-1">País de Origem</p>
                                <p class="fs-5 fw-bold text-white"><?php echo htmlspecialchars($data['country']); ?></p>
                            </div>
                            <div class="col-md-8">
                                <hr class="d-md-none border-secondary">
                                <h5 class="text-white fw-bold mb-3" style="font-family: 'Syne', sans-serif; color: var(--neon-blue) !important;">BIOGRAFIA</h5>
                                <p class="text-secondary lh-lg" style="text-align: justify;">
                                    <?php echo nl2br(htmlspecialchars($data['short_biography'])); ?>
                                </p>
                            </div>
                        </div>

                    <?php elseif($type === 'tent'): ?>
                        <div class="row">
                            <div class="col-md-5 mb-4">
                                <div class="p-4 rounded mb-3" style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--neon-green);">
                                    <p class="text-secondary small fw-bold text-uppercase mb-2">📍 LOCALIZAÇÃO</p>
                                    <p class="text-white fw-bold m-0"><?php echo htmlspecialchars($data['location']); ?></p>
                                </div>
                                
                                <div class="p-4 rounded" style="background: rgba(255,255,255,0.03); border-left: 4px solid var(--neon-blue);">
                                    <p class="text-secondary small fw-bold text-uppercase mb-2">⏰ HORÁRIO</p>
                                    <p class="text-white fw-bold m-0">
                                        <?php echo date('H:i', strtotime($data['opening_hours'])); ?> — <?php echo date('H:i', strtotime($data['closing_hours'])); ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-7">
                                <h5 class="text-white fw-bold mb-3" style="font-family: 'Syne', sans-serif;">SOBRE A BARRACA</h5>
                                <p class="text-secondary lh-lg">
                                    <?php echo nl2br(htmlspecialchars($data['description'])); ?>
                                </p>
                                <p class="mt-4 text-muted small text-uppercase fw-bold">
                                    Representa: <span class="text-white"><?php echo htmlspecialchars($data['fac_name']); ?></span>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Footer Simples -->
            <div class="text-center mt-5">
                <p class="text-secondary small">&copy; 2026 QUEIMA DAS FITAS DO PORTO — SINF1 PROJECT</p>
            </div>

        </div>
    </div>
</div>

</body>
</html>