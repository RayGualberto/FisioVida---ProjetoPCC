-- inserir na tabela serviços mysql

INSERT INTO servico (id_servico, nome_servico, descricao_servico, status) VALUES
(1, 'Neurológica', 'Neurológica', 'ativo'),
(2, 'Pediátrica', 'Pediátrica', 'ativo'),
(3, 'Respiratória', 'Respiratória', 'ativo'),
(4, 'Esportiva', 'Esportiva', 'ativo'),
(5, 'Geriátrica', 'Geriátrica', 'ativo'),
(6, 'Uroginecológica e Obstétrica', 'Uroginecológica e Obstétrica', 'ativo'),
(7, 'Cardiorrespiratória', 'Cardiorrespiratória', 'ativo'),
(8, 'Aquática (Hidroterapia)', 'Aquática (Hidroterapia)', 'ativo');


INSERT INTO usuario (id, nome, email, senha, cpf, data_nasc, telefone, cep, tipo_usuario)
VALUES ('1', 'ray', 'admin1@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '08522233361', '2006-01-26', '61994475245', '72236800', 'admin');

INSERT INTO usuario (id, nome, email, senha, cpf, data_nasc, telefone, cep, tipo_usuario)
VALUES ('2', 'miguel', 'admin2@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '00199288445', '2005-01-01', '61982084371', '72235226', 'admin');

INSERT INTO admin (nome, sexo, cpf, email, senha) 
VALUES ('Ray', 'M', '08522233361', 'admin1@gmail.com', '123456');

INSERT INTO admin (nome, sexo, cpf, email, senha) 
VALUES ('Miguel', 'M', '00199288445', 'admin2@gmail.com', '123456');
