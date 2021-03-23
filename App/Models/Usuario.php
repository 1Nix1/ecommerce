<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model {

    private $id;
    private $nome;
    private $sobrenome;
    private $email;
    private $cpf;
    private $senha;
    private $conf_senha;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    //salvar
    public function salvar() {
        $query = "INSERT INTO usuarios(nome, sobrenome, email, cpf, senha) VALUES(:nome, :sobrenome, :email, :cpf, :senha)";
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':sobrenome', $this->__get('sobrenome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':cpf', $this->__get('cpf'));
            $stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash 32 caracteres
            $stmt->execute();
            return $this;
        }catch(Exception $e) {
            return 'Erro ao cadastrar usuario: '.$e;
        }
    }

    //valida se o cadastro pode ser feito
    public function validaCadastro() {
        $valido = true;
        
        if(!filter_var($this->__get('email'), FILTER_VALIDATE_EMAIL)){
            $valido = false;
        }

        if(!$this->validaCPF($this->__get('cpf'))) {
            $valido = false;
        }

        if(strlen($this->__get('senha')) < 4){
            $valido = false;
        }

        if($this->__get('senha') != $this->__get('conf_senha')){
            $valido = false;
        }

        if(strlen($this->__get('nome')) < 4){
            $valido = false;
        }

        return $valido;
    }

    public function validaCPF($cpf) {
 
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
            
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
    
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
    
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    //recuperar um usuário por e-mail
    public function getUsuarioPorEmail() {
        $query = "SELECT nome, email FROM usuarios WHERE email = :email";
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }catch(Exception $e) {
            return 'Erro ao procurar por usuario por email: '.$e;
        }
        
    }

    //Autenticar usuario
    public function autenticar() {

        $query = "SELECT id, nome, email FROM usuarios WHERE email = :email and senha = :senha";
        try{
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            if(is_array($usuario) && $usuario['id'] != '' && $usuario['nome'] != ''){
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
            }

            return $this;
        } catch (Exception $e) {
            return $e;
        }
    }

    //confirma que está logado
    public function authLogin(){
        if(!$_SESSION['id'] && !$_SESSION['nome']){
            header('Location: /');
        }
    }

}