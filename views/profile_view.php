<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>O Meu Perfil - Queima '26</title>
    <!-- Bootstrap e JS para o menu funcionar em telemóveis -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Fontes e CSS Principal -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-container">

<!-- NAVBAR CORRIGIDA E CONSISTENTE -->
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
                <!-- Nome do Utilizador e Link para Perfil (Destaque na página atual) -->
                <li class="nav-item me-3">
                    <span class="text-secondary small fw-bold text-uppercase">Olá, 
                        <a href="profile.php" class="text-white text-decoration-underline fw-black">
                            <?php echo htmlspecialchars($user['name']); ?>
                        </a>
                    </span>
                </li>

                <!-- Links de Navegação Principal -->
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

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <!-- Título com efeito Highlighter -->
            <div class="mb-4">
                <h2 class="highlight-title">Configurações de Perfil</h2>
            </div>

            <div class="card event-card shadow-lg p-2">
                <div class="card-body p-4">
                    
                    <?php if(!empty($erro)): ?>
                        <div class="alert alert-danger bg-danger text-white border-0 small mb-4">
                            ⚠️ <?php echo htmlspecialchars($erro); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($sucesso)): ?>
                        <div class="alert alert-success bg-success text-white border-0 small mb-4">
                            ✅ <?php echo htmlspecialchars($sucesso); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label small text-secondary fw-bold text-uppercase">Nome Completo</label>
                                <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            
                            <div class="col-md-12 mb-4">
                                <label class="form-label small text-secondary fw-bold text-uppercase">Endereço de Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="p-3 rounded mb-4" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1);">
                            <h6 class="text-white fw-bold mb-3" style="font-family: 'Syne', sans-serif; letter-spacing: 1px;">SEGURANÇA</h6>
                            
                            <div class="mb-2">
                                <label class="form-label small text-secondary fw-bold text-uppercase">
                                    Nova Password 
                                    <span class="badge bg-dark text-secondary fw-normal lowercase" style="font-size: 0.65rem;">Opcional</span>
                                </label>
                                <input type="password" name="nova_password" class="form-control" placeholder="Deixa vazio para manter a atual">
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 fw-bold text-uppercase">
                                Atualizar Perfil
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-secondary small">
                    Estás registado como: <strong><?php echo $is_admin ? 'Administrador' : 'Estudante'; ?></strong>[cite: 1]
                </p>
            </div>
            
        </div>
    </div>
</div>

<footer class="mt-5 py-5 border-top border-secondary text-center text-muted">
    <p class="small">&copy; 2026 QUEIMA DAS FITAS DO PORTO — SINF1 PROJECT</p>
</footer>

</body>
</html>