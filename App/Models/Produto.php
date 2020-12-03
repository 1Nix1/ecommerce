<?php

namespace App\Models;

use MF\Model\Model;

class Produto extends Model {

    private $id;
    private $nome;
    private $categoria;
    private $subcategoria;
    private $descricao;
    private $imagem;
    private $valor;
    private $quantidade;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    //recuperar
    public function getAll() {
        $query = "SELECT id, nome, id_categoria, descricao, imagem, valor, quantidade FROM produtos ORDER BY id DESC";

        $stmt = $this->db->prepare($query);
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
        ORDER BY 
            id DESC
        LIMIT
            $limit
        OFFSET
            $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //recuperar total de tweets
    public function getTotalRegistros() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            produtos
        ";

        $stmt = $this->db->prepare($query);
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
        ORDER BY 
            id DESC
        LIMIT
            $limit
        OFFSET
            $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_categoria', $this->__get('categoria'));
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
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_categoria', $this->__get('categoria'));
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
            nome LIKE :nome ";
        if($this->__get('categoria') != ' '){
            $query = $query."AND id_categoria LIKE :categoria ";
        }
        
        $query = $query."ORDER BY id DESC LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        if($this->__get('categoria') != ' '){
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
        ";
        if($this->__get('categoria') != ' '){
            $query = $query."AND id_categoria LIKE :categoria ";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
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
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_categoria', $this->__get('categoria'));
        $stmt->bindValue(':id_subcategoria', $this->__get('subcategoria'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    
}