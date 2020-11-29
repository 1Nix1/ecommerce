<?php

namespace App\Controllers;

//Recursos framework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

    public function index() {
        $this->render('index');
    }

    public function inscreverse() {

        $this->view->usuario = array(
            'nome' => '',
            'sobrenome' => '',
            'cpf' => '',
            'email' => ''
        );

        $this->view->erroCadastro = false;

        $this->render('inscreverse');
    }

    public function registrar() {
        //receber os dados do formulario
        $usuario = Container::getModel('Usuario');

        //junta nome e sobrenome
        $nome = ucfirst($_POST['nome']);
        $sobrenome = ucfirst($_POST['sobrenome']);
        $nome_completo = $nome.' '.$sobrenome;

        //seta os vslores do objeto
        $usuario->__set('nome', $nome_completo);
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('cpf', $_POST['cpf']);
        $usuario->__set('senha', md5($_POST['senha']));
        $usuario->__set('conf_senha', md5($_POST['conf-senha']));

        if($usuario->validaCadastro() && count($usuario->getUsuarioPorEmail()) == 0) {

            $usuario->salvar();

            $this->render('cadastro');
        } else {

            $this->view->usuario = array(
                'nome' => $_POST['nome'],
                'sobrenome' => $_POST['sobrenome'],
                'email' => $_POST['email'],
                'cpf' => $_POST['cpf']
            );

            $this->view->erroCadastro = true;

            $this->render('inscreverse');
        }




    }
}