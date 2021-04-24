<?php

namespace App\Models;

use MF\Model\Model;

class Transportadora extends Model {
    private $id;
    private $nome;
    private $tempo_entrega;
    private $valor;
    private $status;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function getTransportadora(){
        $query = "
                SELECT 
                    id, 
                    nome, 
                    tempo_entrega, 
                    valor 
                FROM 
                    transportadoras 
                WHERE 
                    status = :status
                ORDER BY 
                    id
                DESC 
        ";


        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPorPagina($limit, $offset){
        $query = "
                SELECT 
                    id, 
                    nome, 
                    tempo_entrega, 
                    valor, 
                    status 
                FROM 
                    transportadoras
                ORDER BY 
                    id
                DESC LIMIT 
                    $limit 
                OFFSET 
                    $offset";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function pesquisaTransportadora($limit, $offset) {
        $query = "
        SELECT
            id, 
            nome, 
            tempo_entrega, 
            valor, 
            status 
        FROM
            transportadoras
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

    //recupera por id
    public function getTransportadoraPorId(){
        $query = "
        SELECT 
            id, 
            nome, 
            tempo_entrega, 
            valor, 
            status 
        FROM 
            transportadoras
        WHERE
            id = :id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //recupera pesquisa
    public function getTotal() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            transportadoras
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //recupera pesquisa
    public function getTotalPesquisa() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            transportadoras
        WHERE
            nome LIKE :nome
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function salva(){
        $query = "
                INSERT INTO 
                    `transportadoras` (`id`, 
                                        `nome`, 
                                        `tempo_entrega`, 
                                        `valor`, 
                                        `status`) 
                VALUES (NULL, 
                        :nome, 
                        :tempo_entrega, 
                        :valor, 
                        :status);
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':tempo_entrega', $this->__get('tempo_entrega'));
        $stmt->bindValue(':valor', $this->__get('valor'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();

        return $this;
    }

    public function updateTransportadora(){
        $query = "
                UPDATE 
                    `transportadoras` 
                SET 
                    `nome` = :nome, 
                    `tempo_entrega` = :tempo_entrega, 
                    `valor` = :valor, 
                    `status` = :status 
                WHERE 
                    `transportadoras`.`id` = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':tempo_entrega', $this->__get('tempo_entrega'));
        $stmt->bindValue(':valor', $this->__get('valor'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();

        return $this;
    }

    
}
