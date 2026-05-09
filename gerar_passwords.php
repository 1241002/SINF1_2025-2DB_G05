<?php
// Ficheiro temporário para gerar hashes reais de password
// Corre uma vez, copia os hashes, e apaga este ficheiro.

$hashes = [
    'admin123 (Admin)'     => password_hash('admin123', PASSWORD_DEFAULT),
    'joao123 (João Silva)' => password_hash('joao123',  PASSWORD_DEFAULT),
    'maria123 (Maria Santos)' => password_hash('maria123', PASSWORD_DEFAULT),
];

echo '<pre>';
foreach ($hashes as $label => $hash) {
    echo htmlspecialchars($label) . ":\n" . htmlspecialchars($hash) . "\n\n";
}
echo '</pre>';
?>
