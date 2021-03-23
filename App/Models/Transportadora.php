<?php

namespace App\Models;

use MF\Model\Model;

class Transportadora extends Model {
    private $id;
    private $nome;
    private $valor;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function getTransportadora(){
        $query = "SELECT id, nome, tempo_entrega, valor FROM transportadoras";


        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    
}
