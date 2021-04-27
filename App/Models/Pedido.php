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
            p.id, 
            p.data,
            e.nome,
            e.sobrenome,
            p.total,
            p.status
        FROM 
            pedidos as p
        INNER JOIN
        	enderecos as e
        ON
        	e.id = p.id_endereco
       	WHERE
        	p.id_usuario = :id_usuario
        ORDER BY 
            p.id 
        DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    //gera pedido
    public function geraPedido(){
        $query = "call add_pedido (:id_usuario, :id_endereco, :id_transportadora, :total)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':id_endereco', $this->__get('id_endereco'));
        $stmt->bindValue(':id_transportadora', $this->__get('id_transportadora'));
        $stmt->bindValue(':total', $this->__get('total'));
        $stmt->execute();
        return $this;
    }

    //Muda o status para pago
    public function alterStatusPago(){
        $query = "UPDATE `pedidos` SET `status` = :status WHERE `pedidos`.`id` = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', 'pago');
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();
        return $this;
    }

    //recuperar com paginação
    public function getPedido() {
        $query = "
            SELECT 
                p.id, 
                u.email,
                p.id_usuario,
                e.nome,
                e.sobrenome,
                e.telefone,
                tr.nome as transportadora,
                e.cep,
                e.cidade,
                es.nome as estado,
                e.rua,
                e.bairro,
                e.numero,
                e.complemento,
                pa.nome as pais,
                p.total,
                p.data,
                p.status
            FROM 
                pedidos as p
            INNER JOIN
                enderecos as e
            ON
                e.id = p.id_endereco
            INNER JOIN 
                usuarios as u
            ON
                u.id = p.id_usuario
            INNER JOIN 
                estados as es
            ON
                e.id_estado = es.id
            INNER JOIN
                pais as pa
            ON
                e.id_pais = pa.id
            INNER JOIN
                transportadoras as tr
            ON
                p.id_transportadora = tr.id
            WHERE
                p.id = :id
            AND
                p.id_usuario = :id_usuario
            ORDER BY 
                p.id 
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //recuperar com paginação
    public function getPorPagina($limit, $offset) {
        $query = "
        SELECT 
            p.id, 
            u.email,
            p.id_usuario,
            u.nome,
            u.sobrenome,
            p.total,
            p.data,
            p.status
        FROM 
            pedidos as p
        INNER JOIN
        	enderecos as e
        ON
        	e.id = p.id_endereco
        INNER JOIN 
            usuarios as u
        ON
            u.id = p.id_usuario
        ORDER BY 
            p.id 
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
    public function pesquisaPedido($limit, $offset, $email) {
        $query = "
        SELECT 
            p.id, 
            p.data,
            p.id_usuario,
            u.nome,
            u.sobrenome,
            u.email,
            p.total,
            p.status
        FROM 
            pedidos as p
        INNER JOIN
        	enderecos as e
        ON
        	e.id = p.id_endereco
        INNER JOIN usuarios as u
        ON
            u.id = p.id_usuario
        WHERE
            u.email LIKE :email
        ORDER BY 
            p.id 
        DESC LIMIT
            $limit
        OFFSET
            $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', '%'.$email.'%');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //recuperar total
    public function getTotalRegistros() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            pedidos
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //recupera categoria pesquisada
    public function getTotalPesquisa($email) {
        $query = "
                SELECT
                    count(p.id) as total
                FROM 
                    pedidos as p 
                INNER JOIN 
                    usuarios as u 
                ON u.id = p.id_usuario
                WHERE
                    u.email LIKE :email
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', '%'.$email.'%');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updatePedidoEnviado(){
        $query = "
                UPDATE 
                    `pedidos` 
                SET 
                    `status` = :status 
                WHERE 
                    `pedidos`.`id` = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();

        return $this;    
    }
    
}