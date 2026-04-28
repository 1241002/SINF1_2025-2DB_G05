<?php
// profile.php (O Controller)

session_start();
require_once 'db.php';
require_once 'models/ProfileModel.php'; // Inclui a camada de dados

// 1. PROTEÇÃO
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = ($_SESSION['user_role'] == 1);
$sucesso = "";
$erro = "";

// 2. PROCESSAR A ATUALIZAÇÃO DOS DADOS (Lógica de Negócio)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $nova_password = $_POST['nova_password'];

    if (empty($nome) || empty($email)) {
        $erro = "O nome e o email são campos obrigatórios.";
    } else {
        // Usa o Model para verificar se o email já existe
        if (isEmailTaken($pdo, $email, $user_id)) {
            $erro = "Este email já está registado noutra conta.";
        } else {
            // Usa o Model para atualizar os dados
            updateUserProfile($pdo, $user_id, $nome, $email, $nova_password);
            
            // Atualiza a sessão para que o nome novo apareça logo no menu
            $_SESSION['user_name'] = $nome;
            $sucesso = "O teu perfil foi atualizado com sucesso!";
        }
    }
}

// 3. IR BUSCAR OS DADOS ATUAIS (Chamando o Model)
$user = getUserById($pdo, $user_id);

// 4. CARREGAR A VIEW
include 'views/profile_view.php';
?>