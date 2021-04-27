/*pedido*/
DELIMITER $$
CREATE PROCEDURE add_pedido (IN id_usuario int, IN id_endereco int, IN id_transportadora int, IN total DOUBLE(16,2)) 
BEGIN 
	SET  @id = (SELECT CASE WHEN MAX(id) IS NULL THEN 1 ELSE MAX(id)+1 END FROM pedidos);

    INSERT INTO pedidos (id, id_usuario, id_endereco, id_transportadora, total, status) VALUES (@id, id_usuario, id_endereco, id_transportadora, total, '');
END $$
DELIMITER ;


/*CARRINHO*/
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

/*ENDEREÇO*/
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