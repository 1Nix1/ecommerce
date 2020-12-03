<?php

namespace App\Controllers;

//Recursos framework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

    public function index() {
        session_start();

        //Inicio buscar produtos
        $produto = Container::getModel('Produto');

        //variaveis de páginação
        $total_registros_pagina = 16;
        //$descocamento = 0;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $produtos = $produto->getPorPagina($total_registros_pagina, $deslocamento);
        $total_produtos = $produto->getTotalRegistros();

        $this->view->total_de_paginas = ceil($total_produtos['total'] / $total_registros_pagina);

        $this->view->produtos = $produtos;
        //Fim buscar produtos

        //Inicio buscar categorias
        $categoria = Container::getModel('Categoria');
        $this->view->categorias = $categoria->getAll();       
        //Fim buscar categorias

        //Inicio buscar subcategorias
        $subcategoria = Container::getModel('Subcategoria');
        $this->view->subcategorias = $subcategoria->getAll();
        //Fim buscar subcategorias

        $this->render('index');
    }

    public function filterCategoria() {
        session_start();

        $id_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

        //Inicio buscar produtos
        $produto = Container::getModel('Produto');

        //variaveis de páginação
        $total_registros_pagina = 16;
        //$descocamento = 0;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $produto->__set('categoria', $id_categoria);
        $produtos = $produto->getPorCategoria($total_registros_pagina, $deslocamento);
        $total_produtos = $produto->getTotalPorCategoria();

        $this->view->total_de_paginas = ceil($total_produtos['total'] / $total_registros_pagina);

        $this->view->produtos = $produtos;
        //Fim buscar produtos

        //Inicio buscar categorias
        $categoria = Container::getModel('Categoria');
        $this->view->categorias = $categoria->getAll();       
        //Fim buscar categorias

        //Inicio buscar subcategorias
        $subcategoria = Container::getModel('Subcategoria');
        $this->view->subcategorias = $subcategoria->getAll();
        //Fim buscar subcategorias

        $this->render('index');
    }

    public function filterSubcategoria() {
        session_start();

        $id_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
        $id_subcategoria = isset($_GET['subcategoria']) ? $_GET['subcategoria'] : '';

        //Inicio buscar produtos
        $produto = Container::getModel('Produto');

        //variaveis de páginação
        $total_registros_pagina = 16;
        //$descocamento = 0;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $produto->__set('categoria', $id_categoria);
        $produto->__set('subcategoria', $id_subcategoria);
        $produtos = $produto->getPorSubcategoria($total_registros_pagina, $deslocamento);
        $total_produtos = $produto->getTotalPorSubcategoria();

        $this->view->total_de_paginas = ceil($total_produtos['total'] / $total_registros_pagina);

        $this->view->produtos = $produtos;
        //Fim buscar produtos

        //Inicio buscar categorias
        $categoria = Container::getModel('Categoria');
        $this->view->categorias = $categoria->getAll();       
        //Fim buscar categorias

        //Inicio buscar subcategorias
        $subcategoria = Container::getModel('Subcategoria');
        $this->view->subcategorias = $subcategoria->getAll();
        //Fim buscar subcategorias

        $this->render('index');


    }

    public function search() {

        session_start();

        $pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';
        $categoria = isset($_GET['categoria']) && $_GET['categoria'] != ' ' ? $_GET['categoria'] : ' ';

        //Inicio buscar produtos
        $produto = Container::getModel('Produto');

        //variaveis de páginação
        $total_registros_pagina = 16;
        //$descocamento = 0;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $produto->__set('nome', $pesquisa);
        $produto->__set('categoria', $categoria);
        $produtos = $produto->pesquisaProduto($total_registros_pagina, $deslocamento);
        $total_produtos = $produto->getTotalPesquisa();

        $this->view->total_de_paginas = ceil($total_produtos['total'] / $total_registros_pagina);

        $this->view->produtos = $produtos;
        //Fim buscar produtos

        //Inicio buscar categorias
        $categoria = Container::getModel('Categoria');
        $this->view->categorias = $categoria->getAll();       
        //Fim buscar categorias

        //Inicio buscar subcategorias
        $subcategoria = Container::getModel('Subcategoria');
        $this->view->subcategorias = $subcategoria->getAll();
        //Fim buscar subcategorias

        $this->render('index');

    }

    public function inscreverse() {

        session_start();

        if(isset($_SESSION['id']) && isset($_SESSION['nome'])){
            header('Location: /');
        } else {
            $this->view->usuario = array(
                'nome' => '',
                'sobrenome' => '',
                'cpf' => '',
                'email' => ''
            );

            $this->view->erroCadastro = false;

            $this->render('inscreverse');
        }
    }

    public function registrar() {

        session_start();
        session_start();
        if(isset($_SESSION['id']) && isset($_SESSION['nome'])){
            header('Location: /');
        } else {
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

    public function login() {
        session_start();
        if(isset($_SESSION['id']) && isset($_SESSION['nome'])){
            header('Location: /');
        } else {
            $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
            
            $this->render('login');
        }
    }
}