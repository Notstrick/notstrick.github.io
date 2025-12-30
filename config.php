<?php
// Configuração do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'chat_realtime');
define('DB_USER', 'root');
define('DB_PASS', '');

// Função para conectar ao banco
function getDBConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch(PDOException $e) {
        die("Erro de conexão: " . $e->getMessage());
    }
}
?>

