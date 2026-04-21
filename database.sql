-- ==========================================
-- 1. CRIAÇÃO DA BASE DE DADOS
-- ==========================================
CREATE DATABASE IF NOT EXISTS SINF1_Queima_BD;
USE SINF1_Queima_BD;

-- ==========================================
-- 2. CRIAÇÃO DAS TABELAS (ESTRUTURA)
-- ==========================================

-- Tabela de Papéis (Admin / Student) [cite: 46-47, 105]
CREATE TABLE Role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Tabela de Utilizadores [cite: 18, 104]
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    FOREIGN KEY (role_id) REFERENCES Role(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabela de Faculdades [cite: 70, 106]
CREATE TABLE Faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    acronym VARCHAR(20) NOT NULL,
    description TEXT,
    colour VARCHAR(50)
) ENGINE=InnoDB;

-- Tabela de Barracas (Tents) [cite: 84, 107]
CREATE TABLE Tent (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    location VARCHAR(255) NOT NULL,
    opening_hours TIME NOT NULL,
    closing_hours TIME NOT NULL,
    description TEXT,
    FOREIGN KEY (faculty_id) REFERENCES Faculty(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabela de Eventos [cite: 54, 108]
CREATE TABLE Event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tent_id INT DEFAULT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    date_time DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL, 
    FOREIGN KEY (tent_id) REFERENCES Tent(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabela de Artistas [cite: 77, 109]
CREATE TABLE Artist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    musical_genre VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    short_biography TEXT NOT NULL
) ENGINE=InnoDB;

-- Tabela Associativa: Artistas nos Eventos [cite: 22, 68, 83]
CREATE TABLE Event_Artist (
    event_id INT NOT NULL,
    artist_id INT NOT NULL,
    PRIMARY KEY (event_id, artist_id),
    FOREIGN KEY (event_id) REFERENCES Event(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (artist_id) REFERENCES Artist(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabela da Agenda Pessoal [cite: 50, 91, 111]
CREATE TABLE PersonalAgenda (
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    PRIMARY KEY (user_id, event_id),
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (event_id) REFERENCES Event(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabela de Avaliações (Ratings) [cite: 28, 51, 96, 110]
CREATE TABLE Rating (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT DEFAULT NULL,
    tent_id INT DEFAULT NULL,
    value INT NOT NULL CHECK (value >= 1 AND value <= 5),
    CONSTRAINT CHK_Rating_Target CHECK (
        (event_id IS NOT NULL AND tent_id IS NULL) OR 
        (event_id IS NULL AND tent_id IS NOT NULL)
    ),
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (event_id) REFERENCES Event(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (tent_id) REFERENCES Tent(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ==========================================
-- 3. INSERÇÃO DE DADOS DE TESTE (TEST DATA) [cite: 116, 140]
-- ==========================================

INSERT INTO Role (id, name) VALUES (1, 'Admin'), (2, 'Student');

INSERT INTO User (role_id, name, email, password_hash) VALUES
(1, 'Administrador Principal', 'admin@queima.pt', 'hash_falsa_123'),
(2, 'João Silva', 'joao.silva@estudante.pt', 'hash_falsa_456'),
(2, 'Maria Santos', 'maria.santos@estudante.pt', 'hash_falsa_789');

INSERT INTO Faculty (id, name, acronym, description, colour) VALUES
(1, 'Faculdade de Engenharia', 'FEUP', 'A maior faculdade do Porto.', 'Tijolo'),
(2, 'Faculdade de Economia', 'FEP', 'A faculdade de gestão e economia.', 'Vermelho'),
(3, 'Faculdade de Medicina', 'FMUP', 'A faculdade de medicina.', 'Amarelo');

INSERT INTO Tent (id, faculty_id, name, location, opening_hours, closing_hours, description) VALUES
(1, 1, 'Barraca da FEUP', 'Praça Central', '20:00:00', '06:00:00', 'A barraca com mais pujança!'),
(2, 2, 'Barraca da FEP', 'Rua B', '20:00:00', '06:00:00', 'As melhores bebidas da Queima.');

INSERT INTO Event (id, tent_id, name, description, date_time, location, type) VALUES
(1, NULL, 'Monumental Serenata', 'Abertura oficial da Queima das Fitas.', '2026-05-03 00:01:00', 'Cordoaria', 'Academic ceremony'),
(2, NULL, 'Cortejo Académico', 'Desfile das faculdades pela cidade.', '2026-05-05 14:00:00', 'Baixa do Porto', 'Academic ceremony'),
(3, 1, 'Noite de Engenharia', 'Festa especial na barraca da FEUP.', '2026-05-06 22:00:00', 'Queimódromo', 'Cultural activity'),
(4, NULL, 'Concerto Quim Barreiros', 'O clássico concerto de terça-feira.', '2026-05-05 23:30:00', 'Palco Principal', 'Concert');

INSERT INTO Artist (id, name, musical_genre, country, short_biography) VALUES
(1, 'Quim Barreiros', 'Música Popular', 'Portugal', 'O mestre do acordeão.'),
(2, 'Slow J', 'Hip-Hop', 'Portugal', 'Artista de grande renome.');

INSERT INTO Event_Artist (event_id, artist_id) VALUES (4, 1);
INSERT INTO PersonalAgenda (user_id, event_id) VALUES (2, 1), (2, 4);
INSERT INTO Rating (user_id, event_id, tent_id, value) VALUES (2, 4, NULL, 5), (3, NULL, 1, 4);