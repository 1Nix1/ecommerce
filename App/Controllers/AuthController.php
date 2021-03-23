<?php 

namespace App\Controllers;

//Recursos framework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {

    //Autenticar cadastro
    public function logar() {

        try{
            $usuario = Container::getModel('Usuario');

            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', md5($_POST['senha']));

            $usuario->autenticar();

            if($usuario->__get('id') != '' && $usuario->__get('nome')) {

                session_start();
                $_SESSION['id'] = $usuario->__get('id');
                $_SESSION['nome'] = $usuario->__get('nome');

                header('Location: /');
            } else {
                header('Location: /login?login=erro');
            }

        } catch(Exception $e) {
            return $e;
        }
    }

    
    //sair
    public function sair() {
        session_start();
        session_destroy();
        header('Location: /');
    }
}
