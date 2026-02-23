-- ============================================
-- MyFinances - Schema de Banco de Dados
-- ============================================

-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS myfinances CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE myfinances;

-- ============================================
-- Tabela: usuarios
-- Armazena informações dos usuários do sistema
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    foto_perfil VARCHAR(255) DEFAULT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabela: receitas
-- Armazena as receitas (proventos) dos usuários
-- ============================================
CREATE TABLE IF NOT EXISTS receitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    tipo_receita ENUM('unica', 'recorrente') DEFAULT 'unica',
    data_recebimento DATE NOT NULL,
    data_fim DATE NULL,
    receita_grupo_id VARCHAR(36) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario_data (usuario_id, data_recebimento),
    INDEX idx_receita_grupo (receita_grupo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabela: despesas
-- Armazena despesas parceladas
-- ============================================
CREATE TABLE IF NOT EXISTS despesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    quantidade_parcelas INT NOT NULL,
    valor_parcela DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabela: despesa_parcelas
-- Armazena cada parcela individual das despesas
-- ============================================
CREATE TABLE IF NOT EXISTS despesa_parcelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    despesa_id INT NOT NULL,
    usuario_id INT NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    status_pago BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (despesa_id) REFERENCES despesas(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario_periodo (usuario_id, ano, mes),
    INDEX idx_despesa (despesa_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabela: dividas_variaveis
-- Armazena dívidas com valores variáveis por mês
-- ============================================
CREATE TABLE IF NOT EXISTS dividas_variaveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario_periodo (usuario_id, ano, mes)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Inserção de dados de exemplo (opcional)
-- ============================================

-- Usuário de exemplo
-- Senha: admin123 (hash gerado com password_hash)
INSERT INTO usuarios (nome, email, senha, foto_perfil) VALUES 
('Administrador', 'admin@myfinances.com', '$2y$10$kbGmnxoAfvzoW29GtnAovOX2fQKC2MBxPjN3gNslMUnmxLBK640z6', NULL);

-- Receitas de exemplo
-- INSERT INTO receitas (usuario_id, descricao, valor, data_recebimento) VALUES
-- (1, 'Salário Janeiro', 5000.00, '2026-01-05'),
-- (1, 'Freelance', 1500.00, '2026-01-15');

-- Despesas de exemplo
-- INSERT INTO despesas (usuario_id, descricao, valor_total, data_inicio, data_fim, quantidade_parcelas, valor_parcela) VALUES
-- (1, 'Notebook', 3600.00, '2026-01-01', '2026-12-01', 12, 300.00);

-- ============================================
-- Fim do Schema
-- ============================================
