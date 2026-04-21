<?php
require_once 'db.php'; // Liga à base de dados
session_start(); // Inicia a sessão para "lembrar" o utilizador

$erro = ""; // Variável para guardar mensagens de erro

// Verifica se o utilizador clicou no botão "Entrar"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Usamos trim() no email para evitar o tal problema dos espaços em branco!
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Procura o utilizador pelo email
    $stmt = $pdo->prepare("SELECT * FROM User WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // ATUALIZAÇÃO DE SEGURANÇA: 
    // Verifica a password em texto limpo (para os dados de teste antigos como o Admin)
    // OU verifica com password_verify() (para as contas novas e seguras que vêm do register.php)
    if ($user && ($password == $user['password_hash'] || password_verify($password, $user['password_hash']))) {
        
        // Sucesso! Guardamos os dados na Sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role_id'];

        // Redireciona para a página principal
        header("Location: index.php");
        exit();
    } else {
        $erro = "Email ou password incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - Queima das Fitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Entrar</h3>
                    
                    <?php if($erro): ?>
                        <div class="alert alert-danger"><?php echo $erro; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        
                        <div class="text-center mt-3">
                            <small>Ainda não tens conta? <a href="register.php">Regista-te aqui</a></small>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>