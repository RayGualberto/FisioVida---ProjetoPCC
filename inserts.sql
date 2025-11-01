-- ==============================
-- INSERÇÃO DE DADOS
-- ==============================

-- SERVIÇOS
INSERT INTO servico (nome_servico, descricao_servico, status) VALUES
('Neurológica', 'Neurológica', 'Ativo'),
('Pediátrica', 'Pediátrica', 'Ativo'),
('Respiratória', 'Respiratória', 'Ativo'),
('Esportiva', 'Esportiva', 'Ativo'),
('Geriátrica', 'Geriátrica', 'Ativo'),
('Uroginecológica e Obstétrica', 'Uroginecológica e Obstétrica', 'Ativo'),
('Cardiorrespiratória', 'Cardiorrespiratória', 'Ativo'),
('Aquática (Hidroterapia)', 'Aquática (Hidroterapia)', 'Ativo');

-- USUÁRIOS ADMIN
INSERT INTO usuario (nome, email, senha, cpf, data_nasc, telefone, cep, sexo, tipo_usuario)
VALUES
('Ray', 'admin1@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '08522233361', '2006-01-26', '61994475245', '72236800', 'M', 'admin'),
('Miguel', 'admin2@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '00199288445', '2005-01-01', '61982084371', '72235226', 'M', 'admin');

-- ADMINS
INSERT INTO admin (nome, sexo, cpf, email, senha)
VALUES
('Ray', 'M', '08522233361', 'admin1@gmail.com', '123456'),
('Miguel', 'M', '00199288445', 'admin2@gmail.com', '123456');

-- FISIOTERAPEUTAS (EXEMPLOS)
INSERT INTO fisioterapeuta (nome, telefone, endereco, cpf, registro_crefito, especialidade) VALUES
('Mariana Costa', '11987654321', 'Rua das Flores, 120 - São Paulo/SP', '12345678900', 'CREFITO-001', 'Neurológica'),
('Pedro Almeida', '21998765432', 'Av. Atlântica, 450 - Rio de Janeiro/RJ', '23456789011', 'CREFITO-002', 'Pediátrica'),
('Camila Rocha', '31991234567', 'Rua Minas Gerais, 87 - Belo Horizonte/MG', '34567890122', 'CREFITO-003', 'Respiratória'),
('Lucas Moreira', '41993456789', 'Rua XV de Novembro, 55 - Curitiba/PR', '45678901233', 'CREFITO-004', 'Esportiva'),
('Fernanda Silva', '51994567890', 'Av. Bento Gonçalves, 800 - Porto Alegre/RS', '56789012344', 'CREFITO-005', 'Geriátrica'),
('Juliana Ramos', '61995678901', 'SQN 210 Bloco B - Brasília/DF', '67890123455', 'CREFITO-006', 'Uroginecológica e Obstétrica'),
('Thiago Martins', '71996789012', 'Rua das Palmeiras, 45 - Salvador/BA', '78901234566', 'CREFITO-007', 'Cardiorrespiratória'),
('Patrícia Oliveira', '81997890123', 'Rua Pernambuco, 300 - Recife/PE', '89012345677', 'CREFITO-008', 'Aquática (Hidroterapia)');