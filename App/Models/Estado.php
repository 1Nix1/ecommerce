<?php

namespace App\Models;

use MF\Model\Model;

class Estado extends Model {

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
            nome
        FROM 
            estados
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
    public function getPorPagina($limit, $offset) {
        $query = "
        SELECT 
            id, 
            nome,
            status
        FROM 
            estados
        ORDER BY 
            id 
        DESC LIMIT 
            $limit 
        OFFSET 
            $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //pesquisa por categoria
    public function pesquisaEstado($limit, $offset) {
        $query = "
        SELECT
            id, 
            nome,
            status
        FROM
            estados
        WHERE
            nome LIKE :nome
        ORDER BY 
            id 
        DESC LIMIT 
            $limit 
        OFFSET 
            $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //recupera categoria pesquisada
    public function getTotal() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            estados
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //recupera categoria pesquisada
    public function getTotalPesquisa() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            estados
        WHERE
            nome LIKE :nome
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //recupera categoria por id
    public function getEstado(){
        $query = "
        SELECT 
            id, 
            nome,
            status
        FROM 
            estados
        WHERE
            id = :id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function cadastraEstado(){
        $query = "
                INSERT INTO 
                    `estados` (`id`,
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

    public function editaEstado(){
        $query = "
                UPDATE 
                    `estados` 
                SET 
                    `nome` = :nome, 
                    `status` = :status 
                WHERE 
                    `estados`.`id` = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();           
    }
    
}