CREATE DATABASE IF NOT EXISTS fisiovida CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fisiovida;

-- ==============================
-- TABELA USUARIO
-- ==============================
CREATE TABLE usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    email VARCHAR(50) UNIQUE,
    senha VARCHAR(100),
    cpf VARCHAR(14) UNIQUE,
    data_nasc DATE,
    telefone VARCHAR(20),
    cep VARCHAR(200),
    sexo ENUM('M','F','Outro'),
    foto VARCHAR(255) DEFAULT '../img/imagem_perfil.JPEG',
    tipo_usuario ENUM('paciente', 'fisioterapeuta', 'admin') NOT NULL
);

-- ==============================
-- TABELA PACIENTE
-- ==============================
CREATE TABLE paciente (
    id_paciente INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    telefone VARCHAR(20),
    cep VARCHAR(255),
    sexo ENUM('M','F','Outro'),
    cpf VARCHAR(14)
);

-- ==============================
-- TABELA ADMIN
-- ==============================
CREATE TABLE admin (
    id_admin INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    sexo ENUM('M','F','Outro'),
    cpf VARCHAR(14),
    email VARCHAR(50),
    senha VARCHAR(100)
);

-- ==============================
-- TABELA FISIOTERAPEUTA
-- ==============================
CREATE TABLE fisioterapeuta (
    id_Fisioterapeuta INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    cpf VARCHAR(14),
    registro_crefito VARCHAR(50),
    especialidade VARCHAR(100)
);

-- ==============================
-- TABELA SERVICO
-- ==============================
CREATE TABLE servico (
    id_servico INT PRIMARY KEY AUTO_INCREMENT,
    nome_servico VARCHAR(100),
    descricao_servico VARCHAR(100),
    status ENUM('Ativo', 'Inativo')
);

-- ==============================
-- TABELA AGENDA (AJUSTADA)
-- ==============================
CREATE TABLE agenda (
    id_Agenda INT PRIMARY KEY AUTO_INCREMENT,
    nome_paciente VARCHAR(100),
    data DATE,
    data_agendamento DATE DEFAULT (CURRENT_DATE),
    hora TIME,
    descricao_servico VARCHAR(255),
    status ENUM('pendente', 'confirmado', 'remarcado', 'recusado') DEFAULT 'pendente',
    paciente_id_paciente INT,
    servico_id_servico INT,
    fisioterapeuta_id INT,
    FOREIGN KEY (paciente_id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (servico_id_servico) REFERENCES servico(id_servico),
    FOREIGN KEY (fisioterapeuta_id) REFERENCES fisioterapeuta(id_Fisioterapeuta)
);

-- ==============================
-- TABELA AVALIACAO
-- ==============================
CREATE TABLE avaliacao (
    id_avaliacao INT PRIMARY KEY AUTO_INCREMENT,
    nome_paciente VARCHAR(100),
    telefone VARCHAR(20),
    email VARCHAR(50),
    avaliacao VARCHAR(255)
);

-- ==============================
-- TABELA PRONTUARIO
-- ==============================
CREATE TABLE prontuario (
    id_prontuario INT PRIMARY KEY AUTO_INCREMENT,
    evolucao VARCHAR(255),
    data DATE,
    assinatura VARCHAR(255)
);

-- ==============================
-- TABELA ATENDIMENTO (opcional, usada em relatórios)
-- ==============================
CREATE TABLE atendimento (
    id_atendimento INT PRIMARY KEY AUTO_INCREMENT,
    data DATETIME,
    agenda_id INT,
    fisioterapeuta_id INT,
    FOREIGN KEY (agenda_id) REFERENCES agenda(id_Agenda),
    FOREIGN KEY (fisioterapeuta_id) REFERENCES fisioterapeuta(id_Fisioterapeuta)
);
