<?php

namespace App\Models;

use MF\Model\Model;

class Carrinho extends Model {
    private $id;
    private $id_produto;
    private $id_usuario;
    private $tamanho;
    private $quantidade;
    private $transportadora;
    private $endereco;
    private $valor_unit;
    private $data;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function getForPedido(){
        $query = "
        SELECT i.id, 
                i.id_usuario,
                i.transportadora, 
                i.endereco,
                SUM(i.total + t.valor) AS total 
        FROM 
            itens_carrinho AS i 
        INNER JOIN 
            transportadoras AS t 
        ON 
            i.transportadora = t.id 
        WHERE 
            id_usuario = :id_usuario 
        GROUP BY i.id_usuario
        ";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (Exception $e){
            return false;
        }
    }

    public function getForItensPedido(){
        $query = "
        SELECT i.id, 
                i.id_produtos,
                i.id_usuario,
                p.id as id_pedido,
                i.tamanho,
                i.quantidade, 
                i.valor_unit,
                i.total as sub_total
        FROM 
            itens_carrinho AS i
        INNER JOIN
            pedidos AS p
        ON
            p.id_usuario = i.id_usuario AND p.status = ''
        INNER JOIN 
            transportadoras AS t 
        ON 
            i.transportadora = t.id 
        WHERE 
            i.id_usuario = :id_usuario
        ORDER BY i.id
        ";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (Exception $e){
            return false;
        }
    }

    public function getFormatado(){
        $query = "
        SELECT 
            i.id, 
            i.id_produtos, 
            i.id_usuario,
            i.tamanho, 
            i.quantidade, 
            i.transportadora,
            i.endereco,
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

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }catch(Exception $e){
            return false;
        }
    }

    public function insertCarrinho(){
        //CALL add_carrinho (id_produto, id_usuario, tamanho, quantidade, valor_unit) 
        $query = "CALL add_carrinho (:id_produto, :id_usuario, :tamanho, :quantidade, :valor_unitario)";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_produto', $this->__get('id_produto'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':tamanho', $this->__get('tamanho'));
            $stmt->bindValue(':quantidade', $this->__get('quantidade'));
            $stmt->bindValue(':valor_unitario', $this->__get('valor_unit'));
            $stmt->execute();
        }catch(Exception $e){
            return false;
        }
    }

    public function removeItem(){
        $query = "DELETE FROM itens_carrinho WHERE id = :id AND id_usuario = :id_usuario";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public function removeAllItens(){
        $query = "DELETE FROM itens_carrinho WHERE id_usuario = :id_usuario";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public function updateFrete(){
        $query = "UPDATE itens_carrinho SET transportadora = :transportadora WHERE id_usuario = :id_usuario";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':transportadora', $this->__get('transportadora'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return true;
        }catch(Exception $e){
            return false;
        }

    }

    public function updateEndereco(){
        $query = "UPDATE itens_carrinho SET endereco = :endereco WHERE id_usuario = :id_usuario";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':endereco', $this->__get('endereco'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $this;
        }catch(Exception $e){
            return false;
        }
    }

    public function totalCarrinho(){

        $query = " SELECT SUM(i.total) AS total_carrinho, 
                          t.valor AS frete, 
                          SUM(i.total) + t.valor AS total
                    FROM 
                        itens_carrinho AS i
                    INNER JOIN
                        transportadoras AS t
                    WHERE i.id_usuario = :id_usuario AND t.id = i.transportadora";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }catch(Exception $e){
            return false;
        }
    }

    public function contaItensCarrinho(){
        $query = "
        SELECT 
            count(id)
        FROM 
            itens_carrinho 
        WHERE 
            id_usuario = :id_usuario
        ORDER BY id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
