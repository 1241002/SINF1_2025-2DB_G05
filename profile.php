<?php
session_start();
require_once 'db.php';

// Proteção: Se não houver sessão, vai para o login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = ($_SESSION['user_role'] == 1);
$sucesso = "";
$erro = "";

// ==========================================
// PROCESSAR A ATUALIZAÇÃO DOS DADOS
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $nova_password = $_POST['nova_password'];

    if (empty($nome) || empty($email)) {
        $erro = "O nome e o email são campos obrigatórios.";
    } else {
        // 1. Verificar se o novo email já está a ser usado por OUTRA pessoa
        $stmt_check = $pdo->prepare("SELECT id FROM User WHERE email = ? AND id != ?");
        $stmt_check->execute([$email, $user_id]);
        
        if ($stmt_check->fetch()) {
            $erro = "Este email já está registado noutra conta.";
        } else {
            // 2. Atualizar a base de dados
            if (!empty($nova_password)) {
                // Se o utilizador escreveu uma password nova, atualizamos tudo (com hash de segurança!)
                $hash_seguro = password_hash($nova_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE User SET name = ?, email = ?, password_hash = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $hash_seguro, $user_id]);
            } else {
                // Se a password ficou em branco, atualizamos só o nome e email
                $stmt = $pdo->prepare("UPDATE User SET name = ?, email = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $user_id]);
            }
            
            // Atualizar o nome na "pulseira" da sessão para mudar imediatamente na barra de navegação
            $_SESSION['user_name'] = $nome;
            $sucesso = "O teu perfil foi atualizado com sucesso!";
        }
    }
}

// ==========================================
// IR BUSCAR OS DADOS ATUAIS PARA MOSTRAR NO FORMULÁRIO
// ==========================================
$stmt = $pdo->prepare("SELECT name, email FROM User WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

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
            <span class="me-3">Olá, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span>
            
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
                    
                    <?php if($erro): ?>
                        <div class="alert alert-danger"><?php echo $erro; ?></div>
                    <?php endif; ?>
                    
                    <?php if($sucesso): ?>
                        <div class="alert alert-success"><?php echo $sucesso; ?></div>
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