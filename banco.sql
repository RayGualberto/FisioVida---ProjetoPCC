-- Criação do Banco de Dados
CREATE DATABASE IF NOT EXISTS fisiovida;
USE fisiovida;

-- Tabela USUARIO
CREATE TABLE usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    email VARCHAR(50),
    senha VARCHAR(100),
    cpf VARCHAR(11),
    data_nasc DATE,
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    tipo ENUM('paciente', 'fisioterapeuta', 'admin') NOT NULL
);


-- Tabela PERFIL
CREATE TABLE perfil (
    id_perfil INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255),
    descricao VARCHAR(100),
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- Tabela PACIENTE
CREATE TABLE paciente (
    id_paciente INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    sexo ENUM('M','F','Outro'),
    cpf VARCHAR(11)
);

-- Tabela SERVICO
CREATE TABLE servico (
    id_servico INT PRIMARY KEY AUTO_INCREMENT,
    nome_servico VARCHAR(100),
    descricao_servico VARCHAR(100),
    status ENUM('Ativo', 'Inativo')
);

-- Tabela FISIOTERAPEUTA
CREATE TABLE fisioterapeuta (
    id_Fisioterapeuta INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    telefone VARCHAR(20),
    endereco VARCHAR(200),
    cpf VARCHAR(11),
    registro_crefito VARCHAR(50),
    especialidade VARCHAR(100)
);

-- Tabela AGENDA
CREATE TABLE agenda (
    id_Agenda INT PRIMARY KEY AUTO_INCREMENT,
    nome_paciente VARCHAR(100),
    data DATE,
    data_agendamento DATE,
    hora TIME,
    descricao_servico VARCHAR(255),
    paciente_id_paciente INT,
    servico_id_servico INT,
    FOREIGN KEY (paciente_id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (servico_id_servico) REFERENCES servico(id_servico)
);

-- Tabela ATENDIMENTO
CREATE TABLE atendimento (
    id_atendimento INT PRIMARY KEY AUTO_INCREMENT,
    data DATETIME,
    Agenda_id_Agenda INT,
    Fisioterapeuta_id_Fisioterapeuta INT,
    FOREIGN KEY (Agenda_id_Agenda) REFERENCES agenda(id_Agenda),
    FOREIGN KEY (Fisioterapeuta_id_Fisioterapeuta) REFERENCES fisioterapeuta(id_Fisioterapeuta)
);

-- Tabela AVALIACAO
CREATE TABLE avaliacao (
    id_avaliacao INT PRIMARY KEY AUTO_INCREMENT,
    nome_paciente VARCHAR(100),
    avaliacao VARCHAR(255),
    paciente_id_paciente INT,
    atendimento_id_atendimento INT,
    FOREIGN KEY (paciente_id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (atendimento_id_atendimento) REFERENCES atendimento(id_atendimento)
);

-- Tabela PRONTUARIO
CREATE TABLE prontuario (
    id_prontuario INT PRIMARY KEY AUTO_INCREMENT,
    evolucao VARCHAR(255),
    data DATE,
    assinatura VARCHAR(255),
    paciente_id_paciente INT,
    FOREIGN KEY (paciente_id_paciente) REFERENCES paciente(id_paciente)
);
