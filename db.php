<?php
// Configurações da ligação
$host = 'localhost';
$dbname = 'SINF1_Queima_BD';
$username = 'root';
$password = ''; // No XAMPP, a password por defeito é vazia

try {
    // Criar a ligação usando PDO (mais seguro)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar para mostrar erros caso algo corra mal
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Se quiseres testar, descomenta a linha abaixo:
    // echo "Ligação OK!"; 

} catch (PDOException $e) {
    die("Erro na ligação: " . $e->getMessage());
}
?>