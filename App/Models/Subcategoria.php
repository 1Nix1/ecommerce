<?php

namespace App\Models;

use MF\Model\Model;

class Subcategoria extends Model {

    private $id;
    private $id_categoria;
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
            id_categoria, 
            nome
        FROM 
            subcategorias
        WHERE
            status = :status
        ORDER BY 
            id 
        DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', 'ativo');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //pesquisa por categoria
    public function pesquisaSubcategoria($limit, $offset) {
        $query = "
                SELECT 
                    s.id, 
                    s.nome,
                    s.id_categoria,
                    c.nome as categoria,
                    s.status
                FROM 
                    subcategorias as s
                INNER JOIN
                    categorias as c
                ON
                    s.id_categoria = c.id
                WHERE
                    s.nome LIKE :nome
                ORDER BY 
                    s.id 
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

    //recuperar
    public function getAllPaginacao($limit, $offset) {
        $query = "
        SELECT 
            s.id, 
            s.nome,
            s.id_categoria,
            c.nome as categoria,
            s.status
        FROM 
            subcategorias as s
        INNER JOIN
            categorias as c
        ON
            s.id_categoria = c.id
        ORDER BY 
            s.id 
        DESC LIMIT
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
            subcategorias
        ORDER BY 
            id
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
            subcategorias
        WHERE
            nome LIKE :nome
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function cadastraSubcategoria(){
        $query = "  INSERT INTO
                     `subcategorias` (`id`, 
                                    `nome`, 
                                    `id_categoria`, 
                                    `status`) 
                    VALUES (NULL, 
                            :nome, 
                            :categoria, 
                            :status)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':categoria', $this->__get('id_categoria'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();

        return $this;
    }

    public function getSubcategoria(){
        $query = "
        SELECT 
            s.id, 
            s.nome,
            s.id_categoria,
            c.nome as categoria,
            s.status
        FROM 
            subcategorias as s
        INNER JOIN
            categorias as c
        ON
            s.id_categoria = c.id
        WHERE
            s.id = :id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function editaSubcategoria(){
        $query = "  UPDATE 
                        `subcategorias` 
                    SET 
                        `nome` = :nome, 
                        `id_categoria` = :id_categoria, 
                        `status` = :status
                    WHERE 
                        `subcategorias`.`id` = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':id_categoria', $this->__get('id_categoria'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();

        return $this;
    }
    
}