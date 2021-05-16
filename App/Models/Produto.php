<?php

namespace App\Models;

use MF\Model\Model;

class Produto extends Model {

    private $id;
    private $nome;
    public $categoria;
    private $subcategoria;
    private $descricao;
    private $imagem;
    private $valor;
    private $quantidade;
    private $status;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    //recuperar
    public function getAll() {
        $query = "SELECT id, nome, id_categoria, descricao, imagem, valor, quantidade FROM produtos WHERE status = :status ORDER BY id DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);


    }

    //recuperar com paginação
    public function getPorPagina($limit, $offset) {
        $query = "
        SELECT 
            id, 
            nome, 
            id_categoria, 
            descricao, 
            imagem, 
            valor, 
            quantidade 
        FROM 
            produtos
        WHERE 
            status = :status 
        ORDER BY 
            id DESC
        LIMIT
            $limit
        OFFSET
            $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //recuperar total
    public function getTotalRegistros() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            produtos
        WHERE 
            status = :status 
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //recuperar com paginação por categoria
    public function getPorCategoria($limit, $offset) {
        $query = "
        SELECT 
            id, 
            nome, 
            id_categoria,
            id_subcategoria,
            descricao, 
            imagem, 
            valor, 
            quantidade 
        FROM 
            produtos 
        WHERE
            id_categoria = :id_categoria
        AND
            status = :status 
        ORDER BY 
            id DESC
        LIMIT
            $limit
        OFFSET
            $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_categoria', $this->__get('categoria'));
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //recuperar total de produtos
    public function getTotalPorCategoria() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            produtos
        WHERE
            id_categoria LIKE :id_categoria
        AND
            status = :status 
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_categoria', $this->__get('categoria'));
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //recuperar com paginação por subcategoria
    public function getPorSubcategoria($limit, $offset) {
        $query = "
        SELECT 
            id, 
            nome, 
            id_categoria,
            id_subcategoria,
            descricao, 
            imagem, 
            valor, 
            quantidade 
        FROM 
            produtos 
        WHERE
            id_categoria = :id_categoria
        AND
            id_subcategoria = :id_subcategoria
        AND
            status = :status 
        ORDER BY 
            id DESC
        LIMIT
            $limit
        OFFSET
            $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_categoria', $this->__get('categoria'));
        $stmt->bindValue(':id_subcategoria', $this->__get('subcategoria'));
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Pesquisa produto
    public function pesquisaProduto($limit, $offset) {
        $query = "
        SELECT
            id, 
            nome, 
            id_categoria,
            id_subcategoria,
            descricao, 
            imagem, 
            valor, 
            quantidade 
        FROM
            produtos
        WHERE
            nome LIKE :nome
        AND
            status = :status  
        ";
        if($this->__get('categoria') != ''){
            $query = $query."AND id_categoria LIKE :categoria ";
        }
        
        $query = $query."ORDER BY id DESC LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->bindValue(':status', 'ativo');
        if($this->__get('categoria') != ''){
            $stmt->bindValue(':categoria', $this->__get('categoria'));
        }
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //recupera produtos pesquisados
    public function getTotalPesquisa() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            produtos
        WHERE
            nome LIKE :nome
        AND
            status = :status  
        ";
        if($this->__get('categoria') != ' '){
            $query = $query."AND id_categoria LIKE :categoria ";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->bindValue(':status', 'ativo');
        if($this->__get('categoria') != ' '){
            $stmt->bindValue(':categoria', $this->__get('categoria'));
        }
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    //recuperar total de produtos
    public function getTotalPorSubcategoria() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            produtos
        WHERE
            id_categoria = :id_categoria
        AND
            id_subcategoria = :id_subcategoria
        AND
            status = :status  
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_categoria', $this->__get('categoria'));
        $stmt->bindValue(':id_subcategoria', $this->__get('subcategoria'));
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    //retorna um produto por id
    public function getProdutoPorId(){
        $query = "
        SELECT 
            id, 
            nome, 
            id_categoria,
            id_subcategoria, 
            descricao, 
            imagem, 
            valor, 
            quantidade 
        FROM 
            produtos 
        WHERE
            id = :id
        AND
            status = :status  
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function cadastraProduto(){
        $query = "
                INSERT INTO 
                    `produtos` (`id`, 
                                `nome`, 
                                `id_categoria`, 
                                `id_subcategoria`, 
                                `descricao`, 
                                `imagem`, 
                                `valor`, 
                                `quantidade`) 
                VALUES (NULL, 
                        :nome, 
                        :categoria, 
                        :subcategoria, 
                        :descricao, 
                        :imagem, 
                        :valor, 
                        :quantidade
        )";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':categoria', $this->__get('categoria'));
        $stmt->bindValue(':subcategoria', $this->__get('subcategoria'));
        $stmt->bindValue(':descricao', $this->__get('descricao'));
        $stmt->bindValue(':imagem', $this->__get('imagem'));
        $stmt->bindValue(':valor', $this->__get('valor'));
        $stmt->bindValue(':quantidade', $this->__get('quantidade'));
        $stmt->execute();

        return $this;
    }

    public function excluiProduto(){
        $query = "
                UPDATE 
                    `produtos` 
                SET 
                    `status` = :status 
                WHERE 
                    `produtos`.`id` = :id;";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':status', '');
        $stmt->execute();
        return $this;
    }

    public function editaProduto(){
        $query = "
                UPDATE 
                    `produtos` 
                SET 
                    `nome` = :nome, 
                    `id_categoria` = :categoria, 
                    `id_subcategoria` = :subcategoria, 
                    `descricao` = :descricao, 
                    `imagem` = :imagem, 
                    `valor` = :valor, 
                    `quantidade` = :quantidade
                WHERE 
                    `produtos`.`id` = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':categoria', $this->__get('categoria'));
        $stmt->bindValue(':subcategoria', $this->__get('subcategoria'));
        $stmt->bindValue(':descricao', $this->__get('descricao'));
        $stmt->bindValue(':imagem', $this->__get('imagem'));
        $stmt->bindValue(':valor', $this->__get('valor'));
        $stmt->bindValue(':quantidade', $this->__get('quantidade'));
        $stmt->execute();
        return $this;
    }

    public function getQuantidade(){
        $query = "SELECT quantidade FROM `produtos` WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    
}