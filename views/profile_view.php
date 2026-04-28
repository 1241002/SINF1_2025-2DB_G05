<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Queima das Fitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand">🔥 Queima das Fitas 2026</span>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Olá, <a href="profile.php" class="text-white text-decoration-underline fw-bold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></a></span>
            
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">📋 Programa</a>
            <a href="tents.php" class="btn btn-outline-light btn-sm me-2">⛺ Barracas</a>
            <a href="agenda.php" class="btn btn-outline-light btn-sm me-2">📅 Minha Agenda</a>
            
            <?php if($is_admin): ?>
                <a href="admin.php" class="btn btn-warning btn-sm me-2">Painel Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-white pb-0 border-0 mt-3">
                    <h3 class="text-center text-primary">⚙️ O Meu Perfil</h3>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(!empty($erro)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
                    <?php endif; ?>
                    
                    <?php if(!empty($sucesso)): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <hr class="my-4">
                        <h6 class="text-muted mb-3">Segurança</h6>
                        
                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Nova Password <small class="text-secondary fw-normal">(Deixa em branco para não alterar)</small></label>
                            <input type="password" name="nova_password" class="form-control" placeholder="Escreve apenas se quiseres mudar...">
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Guardar Alterações</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>