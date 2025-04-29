-- Tabela para catalogar uma coleção de jogos de videogame
CREATE TABLE Jogos (
    -- ID único do jogo na coleção, gerado automaticamente
    JogoID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Título completo do jogo, campo obrigatório
    Titulo VARCHAR(150) NOT NULL,

    -- Plataforma/Console para o qual o jogo pertence, campo obrigatório
    Plataforma VARCHAR(50) NOT NULL,

    -- Tipo de mídia física do jogo (ex: Blu-ray, Cartucho, DVD), opcional
    Midia VARCHAR(30) NULL,

    -- Região de compatibilidade do jogo (ex: NTSC-U, PAL), opcional
    Regiao VARCHAR(10) NULL,

    -- Estado de conservação da cópia física, opcional
    EstadoConservacao VARCHAR(50) NULL,

    -- Data em que o jogo foi adquirido, opcional
    DataAquisicao DATE NULL,

    -- Campo livre para anotações extras sobre o jogo/cópia, opcional
    Observacoes TEXT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Adicionando um índice na coluna Plataforma pode ajudar em buscas futuras
ALTER TABLE Jogos ADD INDEX idx_plataforma (Plataforma);
