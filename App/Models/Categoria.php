<?php

namespace App\Models;

use MF\Model\Model;

class Categoria extends Model {

    private $id;
    private $nome;
    private $status;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    //recuperar
    public function getAll() {
        $query = "
        SELECT 
            id, 
            nome,
            status
        FROM 
            categorias
        WHERE
            status = :status
        ORDER BY 
            id 
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);


    }

    //recuperar
    public function getAllPaginacao($limit, $offset) {
        $query = "
        SELECT 
            id, 
            nome,
            status
        FROM 
            categorias
        ORDER BY 
            id 
        DESC
        LIMIT
            $limit
        OFFSET
            $offset
        
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);


    }

    //recuperar
    public function getTotal() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            categorias
        ORDER BY 
            id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);


    }

    //recupera categoria por id
    public function getCategoria(){
        $query = "
        SELECT 
            id, 
            nome,
            status
        FROM 
            categorias
        WHERE
            id = :id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function cadastraCategoria(){
        $query = "
                INSERT INTO 
                    `categorias` (`id`,
                                 `nome`, 
                                 `status`) 
                VALUES (NULL, 
                        :nome, 
                        :status)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();

        return $this;
    }

    public function editaCategoria(){
        $query = "
                UPDATE 
                    `categorias` 
                SET 
                    `nome` = :nome, 
                    `status` = :status 
                WHERE 
                    `categorias`.`id` = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();           
    }

    
}