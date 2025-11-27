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
('Aquática (Hidroterapia)', 'Aquática (Hidroterapia)', 'Ativo'),
('Quiropraxia', 'Quiropraxia', 'Ativo');

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



-- =====================================================
-- 4 NOVOS FISIOTERAPEUTAS (senha: 123456 - hash abaixo)
-- =====================================================
-- HASH UTILIZADO → $2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK

-- Tabela usuário
INSERT INTO usuario (nome, email, senha, cpf, data_nasc, telefone, cep, sexo, tipo_usuario)
VALUES
('Ana Clara', 'ana.fisio@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '11122233344', '1990-03-12', '61999990001', '72236000', 'F', 'fisioterapeuta'),

('João Pedro', 'joao.fisio@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '22233344455', '1988-07-21', '61999990002', '72236001', 'M', 'fisioterapeuta'),

('Mariana Silva', 'mariana.fisio@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '33344455566', '1992-09-15', '61999990003', '72236002', 'F', 'fisioterapeuta'),

('Carlos Eduardo', 'carlos.fisio@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '44455566677', '1985-11-01', '61999990004', '72236003', 'M', 'fisioterapeuta');


-- Tabela fisioterapeuta
INSERT INTO fisioterapeuta (nome, telefone, endereco, cpf, registro_crefito, especialidade)
VALUES
('Ana Clara', '61999990001', 'Águas Lindas - GO', '11122233344', 'CREFITO-12345', 'Neurológica'),
('João Pedro', '61999990002', 'Brasília - DF', '22233344455', 'CREFITO-67890', 'Esportiva'),
('Mariana Silva', '61999990003', 'Valparaíso - GO', '33344455566', 'CREFITO-54321', 'Pediátrica'),
('Carlos Eduardo', '61999990004', 'Ceilândia - DF', '44455566677', 'CREFITO-98765', 'Cardiorrespiratória');