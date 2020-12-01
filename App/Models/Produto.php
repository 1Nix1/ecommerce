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
}