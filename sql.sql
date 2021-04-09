CREATE TABLE usuarios(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(150),
    email varchar(250),
    cpf varchar(11),
    senha varchar(32)
);

CREATE TABLE categorias(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(150),
);

CREATE TABLE subcategorias(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(150),
    id_categoria int,
    FOREIGN KEY id_categoria REFERENCES categorias(id)
);

CREATE TABLE Produtos(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(150),
    id_categoria int,
    id_subcategoria int,
    descricao varchar(250),
    imagem varchar(250),
    valor double(17,2),
    quantidade int,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id),
    FOREIGN KEY (id_subcategoria) REFERENCES subcategorias(id)
);

CREATE TABLE itens_carrinho(
    id int PRIMARY KEY AUTO_INCREMENT,
    id_produtos int,
    id_usuario int,
    data_hora TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produtos) REFERENCES produtos(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

ALTER TABLE itens_carrinho
ADD transportadora INT NOT NULL AFTER `quantidade`,
ADD CONSTRAINT FK_transportadora FOREIGN KEY(transportadora) 
REFERENCES transportadoras(id);

CREATE TABLE transportadoras(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(250),
    valor DOUBLE(16,2)
);

CREATE TABLE endereco(
    id int PRIMARY KEY AUTO_INCREMENT,
    id_usuario int,
    nome varchar(250),
    sobrenome varchar(250),
    telefone varchar(15),
    cep varchar(20),
    id_cidade int,
    id_estado int,
    rua varchar(250),
    bairro varchar(250),
    numero int,
    complemento varchar(250),
    pais varchar(60),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_cidade) REFERENCES cidades(id),
    FOREIGN KEY (id_estado) REFERENCES estados(id)
);

CREATE TABLE pedidos(
    id int PRIMARY KEY AUTO_INCREMENT,
    id_usuario int,
    id_endereco int,
    total DOUBLE(16,2),
    estatus varchar(15),
    data TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_endereco) REFERENCES enderecos(id)
);

CREATE TABLE itens_pedido(
    id int PRIMARY KEY AUTO_INCREMENT,
    id_pedido int,
    id_produto int,
    quantidade DOUBLE(16,2),
    data TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id),
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
);

$query = "
        SELECT 
            i.id, 
            i.id_produtos, 
            i.id_usuario, 
            i.quantidade, 
            i.total, 
            i.data_hora, 
            p.imagem, 
            p.nome, 
            p.valor 
        FROM 
            itens_carrinho AS i 
        INNER JOIN 
            produtos AS p 
        ON 
            i.id_produtos = p.id 
        WHERE 
            id_usuario = :id_usuario
        ORDER BY id
        ";


DELIMITER $$
CREATE PROCEDURE add_carrinho (IN id_produto int, IN id_usuario int, IN tamanho varchar(3), IN quantidade int, IN valor_unit decimal(10,2)) 
BEGIN 
INSERT INTO `itens_carrinho` (`id`, 
                              `id_produtos`, 
                              `id_usuario`,
                              `tamanho`,
                              `quantidade`,
                              `valor_unit`,
                              `total`, 
                              `data_hora`) VALUES (NULL, 
                                                   id_produto, 
                                                   id_usuario,
                                                   tamanho,
                                                   quantidade,
                                                   valor_unit,
                                                   valor_unit * quantidade, 
                                                   current_timestamp()); 
END $$
DELIMITER ;

/*Select com os valores do carrinho*/
SELECT SUM(i.total) AS total_carrinho, t.valor AS frete, SUM(i.total) + t.valor AS total
	FROM itens_carrinho AS i
    INNER JOIN
    	transportadoras AS t
    WHERE i.id_usuario = 6 AND t.id = i.transportadora

CALL add_carrinho (id_produto, id_usuario, tamanho, quantidade, transportadora, valor_unit) 

/*Insert endereço*/
DELIMITER $$
CREATE PROCEDURE add_endereco (IN id_usuario int, IN nome varchar(250), IN sobrenome varchar(250), IN telefone varchar(15), IN cep varchar(20), IN cidade varchar(150), IN id_estado int, IN rua varchar(250), IN bairro varchar(250), IN numero int, IN complemento varchar(250), IN id_pais int) 
BEGIN 
	SET  @ordem = (SELECT CASE WHEN MAX(ordem) IS NULL THEN 1 ELSE MAX(ordem)+1 END FROM enderecos);

    INSERT INTO `enderecos` (`id`, 
                             `id_usuario`, 
                             `nome`, 
                             `sobrenome`, 
                             `telefone`, 
                             `cep`, 
                             `cidade`, 
                             `id_estado`, 
                             `rua`, 
                             `bairro`, 
                             `numero`, 
                             `complemento`, 
                             `id_pais`, 
                             `ordem`, 
                             `status`) VALUES (NULL, 
                                               id_usuario, 
                                               nome, 
                                               sobrenome, 
                                               telefone, 
                                               cep, 
                                               cidade, 
                                               id_estado, 
                                               rua, 
                                               bairro, 
                                               numero, 
                                               complemento, 
                                               id_pais, 
                                               @ordem,
                                               'ativo'); 
END $$
DELIMITER ;

CALL add_endereco (6, 'Thiago', 'Augusto', '986539475', '86200000', 'Ibiporã', 1, 'Rua das amoreiras', 'Centro', 1580, 'Casa azul', 'Brasil')

/*add pedido*/

DELIMITER $$
CREATE PROCEDURE add_pedido (IN id_usuario int) 
BEGIN 
	DECLARE id_usuario INT;
    DECLARE endereco INT;
    DECLARE total DOUBLE(16,2);
    DECLARE id_transportadora INT;
    DECLARE valor_t DOUBLE(16,2);
	SET FOREIGN_KEY_CHECKS = OFF;
    SELECT id_usuario = id_usuario, endereco = endereco, total = total, id_transportadora = transportadora FROM itens_carrinho WHERE id_usuario = id_usuario GROUP BY id_usuario limit 1;
    SELECT valor_t = valor from transportadoras WHERE id = id_transportadora;

    INSERT INTO pedidos  (id_usuario, id_endereco, id_transportadora, total, status) VALUES (@id_usuario, @endereco, @id_transportadora, @total + @valor_t, 'pago');
	SET FOREIGN_KEY_CHECKS = ON;
END $$
DELIMITER ;
CALL add_pedido(6)
SET FOREIGN_KEY_CHECKS = OFF;

ALTER TABLE enderecos ADD CONSTRAINT fk_id_pais FOREIGN KEY (id_pais) REFERENCES pais(id)

/*Seleciona todas as foregin key do banco*/
select *
from information_schema.referential_constraints
where constraint_schema = 'ecommerce'

/*Exclui a procedure*/
DROP PROCEDURE nome_da_procedure


SELECT e.id as id, 
        e.id_usuario as id_usuario,
        e.nome as nome,
        e.sobrenome as sobrenome,
        e.telefone as telefone,
        e.cep as cep,
        e.cidade as cidade,
        e.id_estado as id_estado,
        t.nome as estado, 
        e.rua as rua, 
        e.bairro as bairro, 
        e.numero as numero, 
        e.complemento as complemento, 
        e.id_pais as id_pais, 
        p.nome AS pais, 
        e.ordem as ordem, 
        e.status as status 
        FROM 
            enderecos AS e 
        INNER JOIN 
            estados AS t ON e.id_estado = t.id 
        INNER JOIN 
            pais AS p ON e.id_pais = p.id 
        WHERE 
            e.id_usuario LIKE 6 
        ORDER BY e.id



/*adiciona pedido*/
DELIMITER $$
CREATE PROCEDURE add_pedido (IN id_usuario int, IN id_endereco int, IN id_transportadora int, IN total DOUBLE(16,2)) 
BEGIN 
	SET  @id = (SELECT CASE WHEN MAX(id) IS NULL THEN 1 ELSE MAX(id)+1 END FROM pedidos);

    INSERT INTO pedidos (id, id_usuario, id_endereco, id_transportadora, total, status) VALUES (@id, id_usuario, id_endereco, id_transportadora, total, '');
END $$
DELIMITER ;
call add_pedido (6, 11, 1, 756.20)
