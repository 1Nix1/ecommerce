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
    private $status;

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
        $query = "INSERT INTO tbl_usuario_admin(nome, sobrenome, email, senha, status) VALUES(:nome, :sobrenome, :email, :senha, :status)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':sobrenome', $this->__get('sobrenome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash 32 caracteres
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();
        return $this;
    }

    //valida se o cadastro pode ser feito
    public function validaCadastro()
    {
        $valido = true;

        /*if (!filter_var($this->__get('email'), FILTER_VALIDATE_EMAIL)) {
            $valido = false;
        }*/

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
    public function validaCadastroSemSenha()
    {
        $valido = true;

        if (!filter_var($this->__get('email'), FILTER_VALIDATE_EMAIL)) {
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

    //recuperar um usuário por e-mail
    public function getUsuarioPorEmail(){
        $query = "SELECT nome, sobrenome, email FROM tbl_usuario_admin WHERE email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUsuarioAdmin(){
        $query = "SELECT id, nome, sobrenome, email, status FROM tbl_usuario_admin WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getEditUser(){
        $query = "SELECT nome, sobrenome, email, cpf FROM usuarios WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateUsuario(){
        $query = "UPDATE `tbl_usuario_admin` SET `nome` = :nome, `sobrenome` = :sobrenome, `email` = :email, `senha` = :senha, `status` = :status WHERE `tbl_usuario_admin`.`id` = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':sobrenome', $this->__get('sobrenome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();

        return $this;
    }

    public function updateUsuarioSemSenha(){
        $query = "UPDATE `tbl_usuario_admin` SET `nome` = :nome, `sobrenome` = :sobrenome, `email` = :email, `status` = :status WHERE `tbl_usuario_admin`.`id` = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':sobrenome', $this->__get('sobrenome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':status', $this->__get('status'));
        $stmt->execute();

        return $this;
    }

    //Autenticar usuario
    public function autenticar()
    {

        $query = "SELECT id, nome, sobrenome, email FROM tbl_usuario_admin WHERE email = :email AND senha = :senha AND status = :status";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->bindValue(':status', 'ativo');
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

    //recuperar
    public function getPorPagina($limit, $offset) {
        $query = "
                SELECT 
                    id, 
                    nome,
                    sobrenome,
                    email,
                    status
                FROM 
                    tbl_usuario_admin
                ORDER BY 
                    id 
                DESC LIMIT 
                    $limit 
                OFFSET 
                    $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function pesquisaUsuarioAdmin($limit, $offset) {
        $query = "
        SELECT
            id, 
            nome,
            sobrenome,
            email,
            status
        FROM
            tbl_usuario_admin
        WHERE
            email LIKE :email
        ORDER BY 
            id 
        DESC LIMIT 
            $limit 
        OFFSET 
            $offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', '%'.$this->__get('email').'%');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //pega o total de registros
    public function getTotal() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            tbl_usuario_admin
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalPesquisa() {
        $query = "
        SELECT 
            count(*) as total
        FROM 
            tbl_usuario_admin
        WHERE
            email LIKE :email
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', '%'.$this->__get('email').'%');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
