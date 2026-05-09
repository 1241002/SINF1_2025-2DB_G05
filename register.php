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
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "O email introduzido não é válido.";
    } elseif (strlen($password) < 6) {
        $erro = "A password tem de ter pelo menos 6 caracteres.";
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