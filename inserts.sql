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


INSERT INTO usuario (id, nome, email, senha, cpf, data_nasc, telefone, cep, sexo, tipo_usuario)
VALUES ('1', 'ray', 'admin1@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '08522233361', '2006-01-26', '61994475245', '72236800','M', 'admin');

INSERT INTO usuario (id, nome, email, senha, cpf, data_nasc, telefone, cep, sexo, tipo_usuario)
VALUES ('2', 'miguel', 'admin2@gmail.com', '$2y$10$JP6BCvg97qkC48Gy/sIi8u5ozLkE1A6WshEZO9cB8udpVyOIHFzqK', '00199288445', '2005-01-01', '61982084371', '72235226','M', 'admin');

INSERT INTO admin (nome, sexo, cpf, email, senha) 
VALUES ('Ray', 'M', '08522233361', 'admin1@gmail.com', '123456');

INSERT INTO admin (nome, sexo, cpf, email, senha) 
VALUES ('Miguel', 'M', '00199288445', 'admin2@gmail.com', '123456');


-- INSERT INTO fisioterapeuta (nome, telefone, endereco, cpf, especialidade) VALUES
-- ('Mariana Costa', '1198765-4321', 'Rua das Flores, 120 - São Paulo/SP', '123.456.789-00', 'Neurológica'),
-- ('Pedro Almeida', '2199876-5432', 'Av. Atlântica, 450 - Rio de Janeiro/RJ', '234.567.890-11', 'Pediátrica'),
-- ('Camila Rocha', '3199123-4567', 'Rua Minas Gerais, 87 - Belo Horizonte/MG', '345.678.901-22', 'Respiratória'),
-- ('Lucas Moreira', '4199345-6789', 'Rua XV de Novembro, 55 - Curitiba/PR', '456.789.012-33', 'Esportiva'),
-- ('Fernanda Silva', '5199456-7890', 'Av. Bento Gonçalves, 800 - Porto Alegre/RS', '567.890.123-44', 'Geriátrica'),
-- ('Juliana Ramos', '6199567-8901', 'SQN 210 Bloco B - Brasília/DF', '678.901.234-55', 'Uroginecológica e Obstétrica'),
-- ('Thiago Martins', '7199678-9012', 'Rua das Palmeiras, 45 - Salvador/BA', '789.012.345-66', 'Cardiorrespiratória'),
-- ('Patrícia Oliveira', '8199789-0123', 'Rua Pernambuco, 300 - Recife/PE', '890.123.456-77', 'Aquática (Hidroterapia)'),
-- ('Rafael Santos', '1199234-5678', 'Rua José Bonifácio, 200 - São Paulo/SP', '901.234.567-88', 'Neurológica');
