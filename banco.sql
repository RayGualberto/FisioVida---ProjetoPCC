CREATE DATABASE IF NOT EXISTS fisiovida CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fisiovida;

-- Tabela usuário
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

-- Tabela paciente
CREATE TABLE paciente (
    id_paciente INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),  
    telefone VARCHAR(20),
    cep VARCHAR(255),
    sexo ENUM('M','F','Outro'),
    cpf VARCHAR(14)
);

-- Tabela admin
CREATE TABLE admin (
    id_admin INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    sexo ENUM('M','F','Outro'),
    cpf VARCHAR(14),
    email VARCHAR(50),
    senha VARCHAR(100)
);

-- Tabela fisioterapeuta
CREATE TABLE fisioterapeuta (
    id_fisioterapeuta INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    cpf VARCHAR(14),
    registro_crefito VARCHAR(50),
    especialidade VARCHAR(100)
);

-- Tabela servico
CREATE TABLE servico (
    id_servico INT PRIMARY KEY AUTO_INCREMENT,
    nome_servico VARCHAR(100),
    descricao_servico VARCHAR(255),
    status ENUM('Ativo', 'Inativo'),
    fisioterapeuta_id INT NULL,
    FOREIGN KEY (fisioterapeuta_id) REFERENCES fisioterapeuta(id_fisioterapeuta)
);

-- Tabela agenda
CREATE TABLE agenda (
    id_Agenda INT PRIMARY KEY AUTO_INCREMENT,
    nome_paciente VARCHAR(100),
    data DATE,
    data_agendamento DATE DEFAULT (CURRENT_DATE),
    hora TIME,
    descricao_servico VARCHAR(255),
    status ENUM('pendente', 'confirmado', 'remarcado', 'recusado','cancelado','concluido') DEFAULT 'pendente',
    paciente_id_paciente INT,
    servico_id_servico INT,
    fisioterapeuta_id INT NULL,
    FOREIGN KEY (paciente_id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (servico_id_servico) REFERENCES servico(id_servico),
    FOREIGN KEY (fisioterapeuta_id) REFERENCES fisioterapeuta(id_fisioterapeuta)
);

-- Tabela avaliacao
CREATE TABLE avaliacao (
    id_avaliacao INT PRIMARY KEY AUTO_INCREMENT,
    nome_paciente VARCHAR(100),
    telefone VARCHAR(20),
    email VARCHAR(50),
    avaliacao VARCHAR(255)
);

-- Tabela prontuario
CREATE TABLE prontuario (
    id_prontuario INT PRIMARY KEY AUTO_INCREMENT,
    evolucao VARCHAR(255),
    data DATE,
    assinatura VARCHAR(255)
);

-- Tabela atendimento
CREATE TABLE atendimento (
    id_atendimento INT PRIMARY KEY AUTO_INCREMENT,
    data DATETIME,
    agenda_id INT,
    fisioterapeuta_id INT,
    FOREIGN KEY (agenda_id) REFERENCES agenda(id_Agenda),
    FOREIGN KEY (fisioterapeuta_id) REFERENCES fisioterapeuta(id_fisioterapeuta)
);

-- Tabela de Notificações 
CREATE TABLE IF NOT EXISTS notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destinatario_cpf VARCHAR(14) NULL,   -- CPF de quem vai receber a notificação
    remetente_cpf VARCHAR(14) NOT NULL,  -- CPF de quem enviou a notificação
    mensagem VARCHAR(255) NOT NULL,
    tipo VARCHAR(50),
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    lida TINYINT(1) DEFAULT 0
);

