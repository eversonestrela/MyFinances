-- ============================================
-- Migration: Módulo de Categorias
-- Data: 2026-05-13
-- Descrição: Cria tabela de categorias e vincula
--            com despesas e dívidas variáveis
-- ============================================

USE myfinances;

-- ============================================
-- Tabela: categorias
-- Categorias de lançamentos por usuário
-- ============================================
CREATE TABLE IF NOT EXISTS categorias (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT          NOT NULL,
    nome        VARCHAR(100) NOT NULL,
    icone       VARCHAR(50)  NOT NULL DEFAULT 'bi-tag',
    cor         VARCHAR(20)  NOT NULL DEFAULT '#667eea',
    ativo       TINYINT(1)   NOT NULL DEFAULT 1,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_categorias_usuario       (usuario_id),
    INDEX idx_categorias_usuario_ativo (usuario_id, ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Alter: despesas — adiciona categoria_id
-- ============================================
ALTER TABLE despesas
    ADD COLUMN categoria_id INT NULL AFTER usuario_id;

ALTER TABLE despesas
    ADD CONSTRAINT fk_despesas_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL;

ALTER TABLE despesas
    ADD INDEX idx_despesas_categoria (categoria_id);

-- ============================================
-- Alter: dividas_variaveis — adiciona categoria_id
-- ============================================
ALTER TABLE dividas_variaveis
    ADD COLUMN categoria_id INT NULL AFTER usuario_id;

ALTER TABLE dividas_variaveis
    ADD CONSTRAINT fk_dividas_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL;

ALTER TABLE dividas_variaveis
    ADD INDEX idx_dividas_categoria (categoria_id);

-- ============================================
-- Seed: categorias padrão para o usuário admin
-- Execute para cada usuário existente ou deixe
-- que o usuário crie as suas próprias.
-- ============================================
INSERT INTO categorias (usuario_id, nome, icone, cor) VALUES
(1, 'Cartão de Crédito', 'bi-credit-card-fill',  '#e74c3c'),
(1, 'Alimentação',       'bi-basket-fill',        '#e67e22'),
(1, 'Transporte',        'bi-car-front-fill',     '#3498db'),
(1, 'Saúde',             'bi-heart-pulse-fill',   '#e91e63'),
(1, 'Moradia',           'bi-house-fill',         '#9b59b6'),
(1, 'Internet/Telefone', 'bi-wifi',               '#1abc9c'),
(1, 'Lazer',             'bi-controller',         '#f39c12'),
(1, 'Investimentos',     'bi-graph-up-arrow',     '#27ae60'),
(1, 'Empréstimos',       'bi-bank',               '#c0392b'),
(1, 'Outros',            'bi-three-dots',         '#95a5a6');
