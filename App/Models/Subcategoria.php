<?php

namespace App\Models;

use MF\Model\Model;

class Subcategoria extends Model {

    private $id;
    private $id_categoria;
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
            id_categoria, 
            nome
        FROM 
            subcategorias
        ORDER BY 
            id 
        DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);


    }

    
}