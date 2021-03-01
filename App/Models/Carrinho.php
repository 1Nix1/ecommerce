<?php

namespace App\Models;

use MF\Model\Model;

class Carrinho extends Model {
    private $id;
    private $id_produto;
    private $id_usuario;
    private $data;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function getAll(){
        $query = "
        SELECT 
            id, 
            id_produto,
            id_usuario,
            data_hora
        FROM 
            itens_carrinho
        WHERE
            id_usuario = :id_usuario
        ORDER BY 
            id 
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFormatado(){
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

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
