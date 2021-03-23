<?php

namespace App\Models;

use MF\Model\Model;

class Pais extends Model {

    private $id;
    private $nome;

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

    

    
}