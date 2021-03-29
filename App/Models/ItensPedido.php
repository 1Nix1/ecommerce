<?php

namespace App\Models;

use MF\Model\Model;

class Pedido extends Model {

    private $id;
    private $id_usuario;
    private $id_endereco;
    private $id_transportadora;
    private $total;
    private $status;
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
            id, 
            nome
        FROM 
            pais
        ORDER BY 
            id 
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    //gera pedido
    public function geraPedido(){
        $query = "INSERT INTO pedidos (id, id_usuario, id_endereco, id_transportadora, total, estatus) VALUES (NULL, :id_usuario, :id_endereco, :id_transportadora, :total, :status)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':id_endereco', $this->__get('id_endereco'));
        $stmt->bindValue(':id_transportadora', $this->__get('id_transportadora'));
        $stmt->bindValue(':total', $this->__get('total'));
        $stmt->bindValue(':status', 'pago');
        $stmt->execute();
        return $this;


    }

    

    
}