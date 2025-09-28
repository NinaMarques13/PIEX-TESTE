CREATE DATABASE IF NOT EXISTS hortas_db;
USE hortas_db;

-- pagina_inicial
CREATE TABLE pagina_inicial (
    id_pagina_inicial INT AUTO_INCREMENT PRIMARY KEY,
    ds_missao VARCHAR(255),
    ds_como_funciona VARCHAR(255),
    ds_faq VARCHAR(255)
);

-- endereco_hortas
CREATE TABLE endereco_hortas (
    id_endereco_hortas INT AUTO_INCREMENT PRIMARY KEY,
    nm_rua VARCHAR(50),
    nr_cep VARCHAR(8),
    nm_bairro VARCHAR(50),
    nm_estado CHAR(2),
    nm_cidade VARCHAR(50),
    nm_pais VARCHAR(20)
);

-- hortas
CREATE TABLE hortas (
    id_hortas INT AUTO_INCREMENT PRIMARY KEY,
    pagina_inicial_id_pagina_inicial INT,
    endereco_hortas_id_endereco_hortas INT,
    nr_cnpj VARCHAR(14),
    nome VARCHAR(50),
    descricao VARCHAR(255),
    receitas_geradas BIGINT,
    nome_produtor VARCHAR(50),
    email_produtor VARCHAR(50),
    hash_senha VARCHAR(255), 
    CONSTRAINT fk_hortas_pagina FOREIGN KEY (pagina_inicial_id_pagina_inicial)
        REFERENCES pagina_inicial(id_pagina_inicial)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_hortas_endereco FOREIGN KEY (endereco_hortas_id_endereco_hortas)
        REFERENCES endereco_hortas(id_endereco_hortas)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- estoques
CREATE TABLE estoques (
    id_estoques INT AUTO_INCREMENT PRIMARY KEY,
    hortas_id_hortas INT,
    total_itens BIGINT,
    dt_validade DATE,
    nm_item VARCHAR(100),
    descricao VARCHAR(255),
    unidade_medida VARCHAR(30),
    CONSTRAINT fk_estoques_hortas FOREIGN KEY (hortas_id_hortas)
        REFERENCES hortas(id_hortas)
        ON DELETE CASCADE ON UPDATE CASCADE
);

