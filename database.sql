-- Criação do banco de dados e tabela de mensagens
CREATE DATABASE IF NOT EXISTS chat_realtime CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE chat_realtime;

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(100) NOT NULL DEFAULT 'Usuário',
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_created_at (created_at),
    INDEX idx_id (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir algumas mensagens de exemplo (opcional)
INSERT INTO messages (user, message, created_at) VALUES
('Sistema', 'Bem-vindo ao sistema de conversa em tempo real!', NOW()),
('Sistema', 'As mensagens são atualizadas automaticamente quando o banco de dados é alterado.', NOW());

