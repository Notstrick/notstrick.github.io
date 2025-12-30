<?php
// Server-Sent Events - Envia atualizações em tempo real para o cliente
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no'); // Desabilita buffering do Nginx se estiver usando

require_once 'config.php';

// ID da última mensagem enviada
$lastId = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

// Função para enviar dados via SSE
function sendSSE($data) {
    echo "data: " . json_encode($data) . "\n\n";
    ob_flush();
    flush();
}

// Enviar mensagem de conexão
sendSSE(['type' => 'connected', 'message' => 'Conectado ao servidor', 'timestamp' => date('Y-m-d H:i:s')]);

// Loop infinito para verificar mudanças no banco
while (true) {
    try {
        $conn = getDBConnection();
        
        // Buscar novas mensagens desde o último ID
        $stmt = $conn->prepare("SELECT * FROM messages WHERE id > :last_id ORDER BY id ASC LIMIT 10");
        $stmt->execute([':last_id' => $lastId]);
        $messages = $stmt->fetchAll();
        
        foreach ($messages as $message) {
            sendSSE([
                'type' => 'message',
                'id' => $message['id'],
                'user' => $message['user'],
                'message' => $message['message'],
                'timestamp' => $message['created_at']
            ]);
            $lastId = $message['id'];
        }
        
        // Verificar se a conexão ainda está ativa
        if (connection_aborted()) {
            break;
        }
        
        // Aguardar 1 segundo antes da próxima verificação
        sleep(1);
        
    } catch (Exception $e) {
        sendSSE(['type' => 'error', 'message' => 'Erro: ' . $e->getMessage()]);
        sleep(5); // Aguardar mais tempo em caso de erro
    }
}
?>

