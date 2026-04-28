<?php
// testes.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<div style='font-family: sans-serif; max-width: 800px; margin: auto; padding: 20px;'>";
echo "<h2>🛠️ Sistema de Testes Automáticos - SINF1</h2>";
echo "<p>Este script verifica se todas as tuas 3 camadas (Models) estão a comunicar corretamente com a Base de Dados.</p><hr>";

// ==========================================
// 1. TESTAR INCLUDES E FICHEIROS
// ==========================================
try {
    require_once 'db.php';
    require_once 'models/AuthModel.php';
    require_once 'models/AdminModel.php';
    require_once 'models/IndexModel.php';
    require_once 'models/AgendaModel.php';
    require_once 'models/TentsModel.php';
    require_once 'models/ProfileModel.php';
    echo "<div style='color:green; margin-bottom: 5px;'>✅ <b>INCLUDES:</b> Todos os ficheiros Model foram encontrados e carregados.</div>";
} catch (Exception $e) {
    die("<div style='color:red; font-weight:bold;'>❌ ERRO FATAL NOS INCLUDES: Não foi possível carregar os ficheiros. " . $e->getMessage() . "</div>");
}

// ==========================================
// 2. TESTAR LIGAÇÃO À BASE DE DADOS
// ==========================================
if (isset($pdo)) {
    echo "<div style='color:green; margin-bottom: 20px;'>✅ <b>BASE DE DADOS:</b> Ligação PDO ao MySQL está ativa e a funcionar.</div>";
} else {
    die("<div style='color:red; font-weight:bold;'>❌ BASE DE DADOS: Variável \$pdo não encontrada. O teu db.php tem problemas!</div>");
}

echo "<h3>📊 Testes de Leitura (SELECTs)</h3>";

// Função auxiliar para automatizar as verificações de leitura
function testarLeitura($nome_teste, $funcao_executar) {
    try {
        $resultado = $funcao_executar();
        $tipo = gettype($resultado);
        $tamanho = is_array($resultado) ? count($resultado) : 'N/A';
        echo "<div style='color:green; padding: 5px; border-left: 4px solid green; margin-bottom: 5px; background: #f0fdf4;'>✅ <b>$nome_teste</b>: Sucesso! (Devolveu Array com $tamanho itens)</div>";
    } catch (Exception $e) {
        echo "<div style='color:red; padding: 5px; border-left: 4px solid red; margin-bottom: 5px; background: #fef2f2;'>❌ <b>$nome_teste FALHOU</b>: " . $e->getMessage() . "</div>";
    }
}

// Correr testes de leitura nos Models
testarLeitura("AuthModel -> emailExists", function() use ($pdo) { return emailExists($pdo, 'teste@falso.pt'); });
testarLeitura("AdminModel -> getArtists", function() use ($pdo) { return getArtists($pdo); });
testarLeitura("AdminModel -> getEvents", function() use ($pdo) { return getEvents($pdo); });
testarLeitura("AdminModel -> getFaculties", function() use ($pdo) { return getFaculties($pdo); });
testarLeitura("AdminModel -> getDetailedTents", function() use ($pdo) { return getDetailedTents($pdo); });
testarLeitura("IndexModel -> getEventsWithRatings", function() use ($pdo) { return getEventsWithRatings($pdo); });
testarLeitura("TentsModel -> getTentsWithRatings", function() use ($pdo) { return getTentsWithRatings($pdo); });


echo "<h3 style='margin-top: 30px;'>📝 Testes de Escrita (INSERT) com Modo de Segurança</h3>";
echo "<p><small>Nota: Estes dados não ficam gravados. O sistema faz um 'Rollback' imediato para proteger a tua BD.</small></p>";

try {
    // Inicia o modo "Simulação" (Transação)
    $pdo->beginTransaction();

    // Tentar usar uma função de Inserção do teu AdminModel
    addArtist($pdo, "Artista de Teste Automático", "Rock", "Portugal", "Isto é um teste do sistema.");
    echo "<div style='color:green; padding: 5px; border-left: 4px solid green; margin-bottom: 5px; background: #f0fdf4;'>✅ <b>AdminModel -> addArtist</b>: Conseguiu executar o INSERT com sucesso.</div>";

    // 🔴 Cancela a inserção para manter a BD limpa!
    $pdo->rollBack();
    echo "<div style='color:blue; padding: 5px;'>ℹ️ Rollback executado: O artista de teste foi apagado logo de seguida. A BD está segura.</div>";

} catch (Exception $e) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    echo "<div style='color:red; padding: 5px; border-left: 4px solid red; margin-bottom: 5px; background: #fef2f2;'>❌ <b>ERRO DE ESCRITA</b>: As tuas funções INSERT/UPDATE estão a falhar -> " . $e->getMessage() . "</div>";
}

echo "<hr><h3 style='color: #0369a1;'>🎉 Fim da Verificação Automática!</h3>";
echo "</div>";
?>