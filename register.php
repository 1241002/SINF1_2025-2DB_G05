<?php
// register.php (Controller)
session_start();
require_once 'db.php';
require_once 'models/AuthModel.php'; // Chama a base de dados

$erro = "";
$sucesso = "";

// Lógica de processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($nome) || empty($email) || empty($password)) {
        $erro = "Preenche todos os campos.";
    } else {
        if (emailExists($pdo, $email)) { // Usa a função do Model
            $erro = "Este email já está registado!";
        } else {
            if (registerUser($pdo, $nome, $email, $password)) { // Usa a função do Model
                $sucesso = "Conta criada com sucesso! Podes fazer login.";
            } else {
                $erro = "Erro ao criar conta. Tenta novamente.";
            }
        }
    }
}

// Carrega o HTML
include 'views/register_view.php';
?>