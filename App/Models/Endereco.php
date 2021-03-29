<?php

namespace App\Models;

use MF\Model\Model;

class Endereco extends Model {

    private $id;
    private $id_usuario;
    private $nome;
    private $sobrenome;
    private $telefone;
    private $cep;
    private $cidade;
    private $id_estado;
    private $rua;
    private $bairro;
    private $numero;
    private $complemento;
    private $id_pais;
    private $ordem;
    private $status;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    //Recuperar os dados do banco
    public function getAll(){
        $query = "
            SELECT e.id as id, 
                e.id_usuario as id_usuario, 
                e.nome as nome, 
                e.sobrenome as sobrenome, 
                e.telefone as telefone, 
                e.cep as cep, 
                e.cidade as cidade, 
                e.id_estado as id_estado, 
                t.nome as estado, 
                e.rua as rua, 
                e.bairro as bairro, 
                e.numero as numero, 
                e.complemento as complemento, 
                e.id_pais as id_pais, 
                p.nome AS pais, 
                e.ordem as ordem, 
                e.status as status 
            FROM 
                enderecos AS e 
            INNER JOIN 
                estados AS t ON e.id_estado = t.id 
            INNER JOIN pais AS p ON e.id_pais = p.id 
                WHERE e.id_usuario = :id_usuario 
            AND
                e.status = 'ativo'
            ORDER BY e.id
        ";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            return 'Erro ao selecionar endereço: '.$e;
        }
    }

    //Recuperar os dados do banco
    public function getEdit(){
        $query = "
            SELECT e.id as id, 
                e.id_usuario as id_usuario, 
                e.nome as nome, 
                e.sobrenome as sobrenome, 
                e.telefone as telefone, 
                e.cep as cep, 
                e.cidade as cidade, 
                e.id_estado as id_estado, 
                t.nome as estado, 
                e.rua as rua, 
                e.bairro as bairro, 
                e.numero as numero, 
                e.complemento as complemento, 
                e.id_pais as id_pais, 
                p.nome AS pais, 
                e.ordem as ordem, 
                e.status as status 
            FROM 
                enderecos AS e 
            INNER JOIN 
                estados AS t ON e.id_estado = t.id 
            INNER JOIN pais AS p ON e.id_pais = p.id 
                WHERE e.id_usuario LIKE :id_usuario 
            AND
                e.id LIKE :id
            AND
                status = 'ativo'
            ORDER BY e.id
        ";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            return 'Erro ao selecionar endereço: '.$e;
        }
    }

    //salvar
    public function salvar() {
        $query = "CALL add_endereco(:id_usuario, :nome, :sobrenome, :telefone, :cep, :cidade, :id_estado, :rua, :bairro, :numero, :complemento, :id_pais);";
        
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':sobrenome', $this->__get('sobrenome'));
            $stmt->bindValue(':telefone', $this->__get('telefone'));
            $stmt->bindValue(':cep', $this->__get('cep'));
            $stmt->bindValue(':cidade', $this->__get('cidade'));
            $stmt->bindValue(':id_estado', $this->__get('id_estado'));
            $stmt->bindValue(':rua', $this->__get('rua'));
            $stmt->bindValue(':bairro', $this->__get('bairro'));
            $stmt->bindValue(':numero', $this->__get('numero'));
            $stmt->bindValue(':complemento', $this->__get('complemento'));
            $stmt->bindValue(':id_pais', $this->__get('id_pais'));
            $stmt->execute();
            return $this;
        }catch(Exception $e) {
            return 'Erro ao cadastrar endereço: '.$e;
        }
    }

    public function editar(){

        $query = "UPDATE `enderecos` SET `nome` = :nome, `sobrenome` = :sobrenome, `telefone` = :telefone, `cep` = :cep, `cidade` = :cidade, `id_estado` = :id_estado, `rua` = :rua, `bairro` = :bairro, `numero` = :numero, `complemento` = :complemento, `id_pais` = :id_pais WHERE `enderecos`.`id_usuario` = :id_usuario AND `enderecos`.`id` = :id";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':sobrenome', $this->__get('sobrenome'));
            $stmt->bindValue(':telefone', $this->__get('telefone'));
            $stmt->bindValue(':cep', $this->__get('cep'));
            $stmt->bindValue(':cidade', $this->__get('cidade'));
            $stmt->bindValue(':id_estado', $this->__get('id_estado'));
            $stmt->bindValue(':rua', $this->__get('rua'));
            $stmt->bindValue(':bairro', $this->__get('bairro'));
            $stmt->bindValue(':numero', $this->__get('numero'));
            $stmt->bindValue(':complemento', $this->__get('complemento'));
            $stmt->bindValue(':id_pais', $this->__get('id_pais'));
            $stmt->execute();
            return $this;
        }catch(Exception $e) {
            return 'Erro ao editar endereço: '.$e;
        }
    }

    //"Excluir" endereço
    public function excluir(){
        $query = "UPDATE `enderecos` SET `status` = :status WHERE `enderecos`.`id_usuario` = :id_usuario AND `enderecos`.`id` = :id"; 

        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':status', 'inativo');
            $stmt->execute();
            return $this;
        } catch(Exception $e) {
            return 'Erro ao excluir endereço: '.$e;
        }
    }
    
}