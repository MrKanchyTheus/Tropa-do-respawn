-- =======================================================
-- Tropa do Respawn - Esquema do Banco de Dados MySQL
-- Compatível com MySQL 5.7+ / MariaDB / InfinityFree / XAMPP
-- =======================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `nome_completo` VARCHAR(120) NOT NULL,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `senha` VARCHAR(255) NOT NULL,
  `data_nascimento` DATE NOT NULL,
  `avatar` VARCHAR(255) DEFAULT 'assets/img/mascote.jpeg',
  `nivel` INT DEFAULT 1,
  `xp` INT DEFAULT 150,
  `tipo` VARCHAR(20) DEFAULT 'usuario',
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Jogos
CREATE TABLE IF NOT EXISTS `jogos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(80) NOT NULL UNIQUE,
  `nome` VARCHAR(100) NOT NULL,
  `categoria` VARCHAR(50) NOT NULL,
  `descricao` TEXT NOT NULL,
  `imagem` VARCHAR(255) NOT NULL,
  `banner` VARCHAR(255) NULL,
  `total_dicas` INT DEFAULT 0,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Artigos e Guias da Wiki
CREATE TABLE IF NOT EXISTS `artigos_wiki` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `jogo_id` INT NULL,
  `usuario_id` INT NOT NULL,
  `categoria` VARCHAR(50) NOT NULL,
  `titulo` VARCHAR(220) NOT NULL,
  `slug` VARCHAR(250) NOT NULL UNIQUE,
  `resumo` TEXT NOT NULL,
  `conteudo` LONGTEXT NOT NULL,
  `imagem` VARCHAR(255) NULL,
  `tempo_leitura` VARCHAR(30) DEFAULT '5 min',
  `visualizacoes` INT DEFAULT 0,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Fóruns / Tópicos de Discussão
CREATE TABLE IF NOT EXISTS `foruns` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL,
  `jogo_id` INT NULL,
  `categoria` VARCHAR(50) NOT NULL,
  `titulo` VARCHAR(220) NOT NULL,
  `conteudo` LONGTEXT NOT NULL,
  `tags` VARCHAR(255) NULL,
  `visualizacoes` INT DEFAULT 0,
  `media_avaliacao` DECIMAL(3,2) DEFAULT 0.00,
  `total_avaliacoes` INT DEFAULT 0,
  `total_comentarios` INT DEFAULT 0,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Comentários dos Fóruns
CREATE TABLE IF NOT EXISTS `forum_comentarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `forum_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `conteudo` TEXT NOT NULL,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`forum_id`) REFERENCES `foruns`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Avaliações dos Fóruns (1 a 5 estrelas)
CREATE TABLE IF NOT EXISTS `forum_avaliacoes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `forum_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `nota` INT NOT NULL CHECK (`nota` BETWEEN 1 AND 5),
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_forum_usuario` (`forum_id`, `usuario_id`),
  FOREIGN KEY (`forum_id`) REFERENCES `foruns`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Chamados de Suporte
CREATE TABLE IF NOT EXISTS `chamados_suporte` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NULL,
  `nome` VARCHAR(120) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `canal` VARCHAR(50) DEFAULT 'Formulário',
  `categoria` VARCHAR(60) NOT NULL,
  `mensagem` TEXT NOT NULL,
  `status` VARCHAR(30) DEFAULT 'Aberto',
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Auditoria e Histórico (Requisito CORE)
CREATE TABLE IF NOT EXISTS `historico_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NULL,
  `usuario_nome` VARCHAR(120) NOT NULL,
  `acao` VARCHAR(80) NOT NULL,
  `entidade` VARCHAR(60) NOT NULL,
  `registro_id` INT NULL,
  `detalhes` TEXT NOT NULL,
  `ip_origem` VARCHAR(50) NOT NULL,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET foreign_key_checks = 1;
