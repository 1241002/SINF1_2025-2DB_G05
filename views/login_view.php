<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - Queima '26</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Importamos as mesmas fontes para manter a consistência -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-container d-flex align-items-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <!-- Branding no Login -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-black" style="letter-spacing: -2px;">QUEIMA<span style="color:var(--neon-blue)">'26</span></h1>
                <p class="text-muted text-uppercase small fw-bold">Área Reservada</p>
            </div>

            <!-- Card Estilo Glassmorphism -->
            <div class="card event-card shadow-lg p-3">
                <div class="card-body">
                    <h3 class="fw-bold mb-4" style="font-family: 'Syne', sans-serif;">LOGIN</h3>
                    
                    <?php if(!empty($erro)): ?>
                        <div class="alert alert-danger bg-danger text-white border-0 small py-2">
                            ⚠️ <?php echo htmlspecialchars($erro); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small text-secondary fw-bold text-uppercase">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="exemplo@estudante.pt" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small text-secondary fw-bold text-uppercase">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase" style="letter-spacing: 1px;">
                            Entrar no Recinto
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="register.php" class="text-decoration-none small fw-bold" style="color: var(--neon-green);">
                            Ainda não tens conta? Regista-te aqui
                        </a>
                    </div>
                </div>
            </div>
            
            <p class="text-center text-secondary mt-5 small">
                &copy; 2026 Federação Académica do Porto
            </p>
        </div>
    </div>
</div>

</body>
</html>