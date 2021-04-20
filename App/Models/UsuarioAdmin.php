<?php

namespace App\Models;

use MF\Model\Model;

class UsuarioAdmin extends Model
{

    private $id;
    private $nome;
    private $sobrenome;
    private $senha;
    private $conf_senha;

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    //salvar
    public function salvar()
    {
        $query = "INSERT INTO usuarios(nome, sobrenome, email, cpf, senha) VALUES(:nome, :sobrenome, :email, :cpf, :senha)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':sobrenome', $this->__get('sobrenome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':cpf', $this->__get('cpf'));
        $stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash 32 caracteres
        $stmt->execute();
        return $this;
    }

    //valida se o cadastro pode ser feito
    public function validaCadastro()
    {
        $valido = true;

        if (!filter_var($this->__get('email'), FILTER_VALIDATE_EMAIL)) {
            $valido = false;
        }

        if (!$this->validaCPF($this->__get('cpf'))) {
            $valido = false;
        }

        if (strlen($this->__get('senha')) < 4) {
            $valido = false;
        }

        if ($this->__get('senha') != $this->__get('conf_senha')) {
            $valido = false;
        }

        if (strlen($this->__get('nome')) < 4) {
            $valido = false;
        }

        return $valido;
    }

    //valida se o cadastro pode ser feito
    public function validaSenha()
    {
        $valido = '';

        if (strlen($this->__get('senha')) < 4) {
            $valido = 'senha-invalida';
        }

        if ($this->__get('senha') != $this->__get('conf_senha')) {
            $valido = 'senhas-diferentes';
        }

        return $valido;
    }

    public function validaCPF($cpf)
    {

        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

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
    public function getUsuarioPorEmail()
    {
        $query = "SELECT nome, sobrenome, email FROM usuarios WHERE email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getEditUser()
    {
        $query = "SELECT nome, sobrenome, email, cpf FROM usuarios WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateUsuario()
    {
        $query = "UPDATE `usuarios` SET `nome` = :nome, `sobrenome` = :sobrenome, `email` = :email, `cpf` = :cpf WHERE `usuarios`.`id` = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':sobrenome', $this->__get('sobrenome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':cpf', $this->__get('cpf'));
        $stmt->execute();

        return $this;
    }

    public function updateSenha()
    {
        $query = "UPDATE `usuarios` SET `senha` = :senha WHERE `usuarios`.`id` = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':senha', md5($this->__get('senha')));
        $stmt->execute();

        return $this;
    }

    //Autenticar usuario
    public function autenticar()
    {

        $query = "SELECT id, nome, sobrenome, email FROM tbl_usuario_admin WHERE email = :email and senha = :senha";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (is_array($usuario) && $usuario['id'] != '' && $usuario['nome'] != '') {
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);
            $this->__set('sobrenome', $usuario['sobrenome']);
            $this->__set('email', $usuario['email']);
        }

        return $this;
    }

    //confirma que está logado
    public function authLogin()
    {
        if (!$_SESSION['id'] && !$_SESSION['nome']) {
            header('Location: /admin/login_admin');
        }
    }
}
