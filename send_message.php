<?php
// Script para enviar mensagens ao banco de dados
header('Content-Type: application/json');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$user = isset($_POST['user']) ? trim($_POST['user']) : 'Usuário';

if (empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Mensagem vazia']);
    exit;
}

try {
    $conn = getDBConnection();
    
    // Inserir mensagem no banco
    $stmt = $conn->prepare("INSERT INTO messages (user, message, created_at) VALUES (:user, :message, NOW())");
    $stmt->execute([
        ':user' => $user,
        ':message' => $message
    ]);
    
    echo json_encode([
        'success' => true,
        'id' => $conn->lastInsertId(),
        'message' => 'Mensagem enviada com sucesso'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro ao salvar mensagem: ' . $e->getMessage()]);
}
?>

