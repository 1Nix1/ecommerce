<?php

namespace App\Models;

use MF\Model\Model;

class ItensPedido extends Model {

    private $id;
    private $id_pedido;
    private $id_produto;
    private $id_usuario;
    private $tamanho;
    private $quantidade;
    private $valor_unit;
    private $total;
    private $data;

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
            i.id, 
            i.id_pedido,
            i.id_produto,
            p.nome,
            i.id_usuario,
            i.tamanho,
            i.quantidade,
            i.valor_unit,
            i.total,
            i.data
        FROM 
            itens_pedido as i
        INNER JOIN
            produtos as p
        ON
            p.id = i.id_produto
        WHERE
        	i.id_usuario = :id_usuario
        AND
            i.id_pedido = :id_pedido
        ORDER BY 
            id 
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':id_pedido', $this->__get('id_pedido'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    //recuperar
    public function getEmailUsuario() {
        $query = "
        SELECT 
            u.email
        FROM 
            itens_pedido as i
        INNER JOIN
            produtos as p
        ON
            p.id = i.id_produto
        INNER JOIN
            usuarios as u
        ON
            u.id = i.id_usuario
        WHERE
        	i.id_usuario = :id_usuario
        AND
            i.id_pedido = :id_pedido
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':id_pedido', $this->__get('id_pedido'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    //gera pedido
    public function geraItensPedido(){
        $query = "INSERT INTO itens_pedido (id, id_pedido, id_produto, id_usuario, tamanho, quantidade, valor_unit, total) VALUES (NULL, :id_pedido, :id_produto, :id_usuario, :tamanho, :quantidade, :valor_unit, :total)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pedido', $this->__get('id_pedido'));
        $stmt->bindValue(':id_produto', $this->__get('id_produto'));
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':tamanho', $this->__get('tamanho'));
        $stmt->bindValue(':quantidade', $this->__get('quantidade'));
        $stmt->bindValue(':valor_unit',  $this->__get('valor_unit'));
        $stmt->bindValue(':total',  $this->__get('total'));
        $stmt->execute();
        return $this;


    }

    

    
}