<?php
session_start();
require_once 'db.php';

$erro = "";
$sucesso = "";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_conf = $_POST['password_conf'];

    // 1. Validar se as passwords coincidem
    if ($password !== $password_conf) {
        $erro = "As passwords não coincidem. Tenta novamente!";
    } else {
        // 2. Verificar se o email já existe na base de dados
        $stmt = $pdo->prepare("SELECT id FROM User WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $erro = "Este email já está registado! Tenta fazer login.";
        } else {
            // 3. Encriptar a password (Segurança Máxima!)
            $hash_seguro = password_hash($password, PASSWORD_DEFAULT);
            
            // 4. Inserir na Base de Dados (Papel 2 = Estudante)
            $role_id_estudante = 2; 
            
            $inserir = $pdo->prepare("INSERT INTO User (role_id, name, email, password_hash) VALUES (?, ?, ?, ?)");
            
            if ($inserir->execute([$role_id_estudante, $nome, $email, $hash_seguro])) {
                $sucesso = "Conta criada com sucesso! Já podes fazer login.";
            } else {
                $erro = "Ocorreu um erro ao criar a conta.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo - Queima das Fitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Criar Conta de Estudante</h3>
                    
                    <?php if($erro): ?>
                        <div class="alert alert-danger"><?php echo $erro; ?></div>
                    <?php endif; ?>
                    
                    <?php if($sucesso): ?>
                        <div class="alert alert-success">
                            <?php echo $sucesso; ?>
                            <br><a href="login.php" class="btn btn-sm btn-success mt-2">Ir para o Login</a>
                        </div>
                    <?php else: ?>
                    
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nome Completo</label>
                                <input type="text" name="nome" class="form-control" required placeholder="Ex: Rui Costa">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email de Estudante</label>
                                <input type="email" name="email" class="form-control" required placeholder="rui@estudante.pt">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmar Password</label>
                                <input type="password" name="password_conf" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Registar</button>
                        </form>
                        
                    <?php endif; ?>

                    <div class="text-center">
                        <small>Já tens conta? <a href="login.php">Entra aqui</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>