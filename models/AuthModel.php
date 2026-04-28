<?php
// models/AuthModel.php

// 1. Verifica se um email já está registado
function emailExists($pdo, $email) {
    $stmt = $pdo->prepare("SELECT id FROM User WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() ? true : false;
}

// 2. Regista um novo utilizador na base de dados (o role_id = 2 é Estudante)
function registerUser($pdo, $nome, $email, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO User (name, email, password_hash, role_id) VALUES (?, ?, ?, 2)");
    return $stmt->execute([$nome, $email, $hash]);
}

// 3. Vai buscar o utilizador pelo email (vamos usar isto a seguir no Login)
function getUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM User WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>