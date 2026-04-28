<?php
// login.php (Controller)
session_start();
require_once 'db.php';
require_once 'models/AuthModel.php'; // Usamos o MESMO ficheiro do registo!

// Se já estiver logado, atira-o para o index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $erro = "Preenche o email e a password.";
    } else {
        // Vai buscar os dados do utilizador ao Model
        $user = getUserByEmail($pdo, $email);

        // Verifica se o utilizador existe e se a password bate certo
        if ($user && password_verify($password, $user['password_hash'])) {
            // Sucesso! Cria a "pulseira" da sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role_id'];

            header("Location: index.php");
            exit();
        } else {
            $erro = "Email ou password incorretos.";
        }
    }
}

// Carrega o HTML
include 'views/login_view.php';
?>