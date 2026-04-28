<?php
// models/ProfileModel.php

// Vai buscar os dados atuais do utilizador
function getUserById($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT name, email FROM User WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Verifica se o email já está a ser usado por outra pessoa
function isEmailTaken($pdo, $email, $user_id) {
    $stmt = $pdo->prepare("SELECT id FROM User WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    return $stmt->fetch() ? true : false;
}

// Atualiza o perfil (com ou sem password nova)
function updateUserProfile($pdo, $user_id, $nome, $email, $nova_password = null) {
    if (!empty($nova_password)) {
        $hash_seguro = password_hash($nova_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE User SET name = ?, email = ?, password_hash = ? WHERE id = ?");
        return $stmt->execute([$nome, $email, $hash_seguro, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE User SET name = ?, email = ? WHERE id = ?");
        return $stmt->execute([$nome, $email, $user_id]);
    }
}
?>