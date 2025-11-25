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