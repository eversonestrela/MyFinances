-- ============================================
-- Migration: Adicionar suporte a receitas recorrentes
-- Data: 2026-02-23
-- ============================================

-- Adicionar colunas para receitas recorrentes
ALTER TABLE receitas 
ADD COLUMN tipo_receita ENUM('unica', 'recorrente') DEFAULT 'unica' AFTER valor,
ADD COLUMN data_fim DATE NULL AFTER data_recebimento,
ADD COLUMN receita_grupo_id VARCHAR(36) NULL AFTER data_fim,
ADD INDEX idx_receita_grupo (receita_grupo_id);

-- receita_grupo_id: UUID para agrupar receitas recorrentes do mesmo lançamento
