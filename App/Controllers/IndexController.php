<?php

namespace App\Controllers;

//Recursos framework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action
{

    public function index()
    {

        session_start();

        //Inicio buscar produtos
        $produto = Container::getModel('Produto');
        $carrinho = Container::getModel('Carrinho');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        //variaveis de páginação
        $total_registros_pagina = 16;
        //$descocamento = 0;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $produtos = $produto->getPorPagina($total_registros_pagina, $deslocamento);
        $total_produtos = $produto->getTotalRegistros();

        $this->view->total_de_paginas = ceil($total_produtos['total'] / $total_registros_pagina);

        if (isset($_SESSION['id'])) {
            $carrinho->__set('id_usuario', $_SESSION['id']);
        }
        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //carrega produtos
        $this->view->produtos = $produtos;
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();

        $this->render('index');
    }

    public function categoria()
    {

        session_start();
        $id_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
        $id_subcategoria = isset($_GET['subcategoria']) ? $_GET['subcategoria'] : '';

        //Inicio buscar produtos
        $produto = Container::getModel('Produto');
        $carrinho = Container::getModel('Carrinho');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        //variaveis de páginação
        $total_registros_pagina = 16;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        if (isset($_GET['categoria']) && isset($_GET['subcategoria'])) {

            $produto->__set('categoria', $id_categoria);
            $produto->__set('subcategoria', $id_subcategoria);
            $produtos = $produto->getPorSubcategoria($total_registros_pagina, $deslocamento);
            $total_produtos = $produto->getTotalPorSubcategoria();
        } else if (isset($_GET['categoria'])) {

            $produto->__set('categoria', $id_categoria);
            $produtos = $produto->getPorCategoria($total_registros_pagina, $deslocamento);
            $total_produtos = $produto->getTotalPorCategoria();
        }


        $this->view->total_de_paginas = ceil($total_produtos['total'] / $total_registros_pagina);

        $carrinho->__set('id_usuario', $_SESSION['id']);
        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        
        $this->view->produtos = $produtos;
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();

        $this->render('index');
    }

    public function search()
    {

        session_start();

        $pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';
        $pesquisa_categorias = isset($_GET['categoria']) && $_GET['categoria'] != ' ' ? $_GET['categoria'] : '';

        //Inicio buscar produtos
        $produto = Container::getModel('Produto');
        $carrinho = Container::getModel('Carrinho');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        //variaveis de páginação
        $total_registros_pagina = 16;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $produto->__set('nome', $pesquisa);
        $produto->__set('categoria', $pesquisa_categorias);

        $produtos = $produto->pesquisaProduto($total_registros_pagina, $deslocamento);
        $total_produtos = $produto->getTotalPesquisa();

        $this->view->total_de_paginas = ceil($total_produtos['total'] / $total_registros_pagina);

        $carrinho->__set('id_usuario', $_SESSION['id']);
        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //carrega produtos
        $this->view->produtos = $produtos;
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();

        $this->render('index');
    }

    public function inscreverse()
    {

        session_start();

        $carrinho = Container::getModel('Carrinho');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        if (isset($_SESSION['id']) && isset($_SESSION['nome'])) {
            header('Location: /');
        } else {
            $this->view->usuario = array(
                'nome' => '',
                'sobrenome' => '',
                'cpf' => '',
                'email' => ''
            );

            $this->view->erroCadastro = false;

            if (isset($_SESSION['id'])) {
                $carrinho->__set('id_usuario', $_SESSION['id']);
            }
            //quantidades de itens no carrinho
            $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
            //Buscar categorias
            $this->view->categorias = $categoria->getAll();
            //Buscar subcategorias
            $this->view->subcategorias = $subcategoria->getAll();

            $this->render('inscreverse');
        }
    }

    public function registrar()
    {

        session_start();

        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        if (isset($_SESSION['id']) && isset($_SESSION['nome'])) {
            header('Location: /');
        } else {
            //receber os dados do formulario
            $usuario = Container::getModel('Usuario');

            //seta os vslores do objeto
            $usuario->__set('nome', $_POST['nome']);
            $usuario->__set('nome', $_POST['sobrenome']);
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('cpf', $_POST['cpf']);
            $usuario->__set('senha', md5($_POST['senha']));
            $usuario->__set('conf_senha', md5($_POST['conf-senha']));

            //Buscar categorias
            $this->view->categorias = $categoria->getAll();
            //Buscar subcategorias
            $this->view->subcategorias = $subcategoria->getAll();

            if ($usuario->validaCadastro() && count($usuario->getUsuarioPorEmail()) == 0) {

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

    public function login()
    {
        session_start();

        $carrinho = Container::getModel('Carrinho');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        if (isset($_SESSION['id'])) {
            $carrinho->__set('id_usuario', $_SESSION['id']);
        }
        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();

        if (isset($_SESSION['id']) && isset($_SESSION['nome'])) {
            header('Location: /');
        } else {
            $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';

            $this->render('login');
        }
    }

    public function produto()
    {
        session_start();

        $categoria = Container::getModel('Categoria');
        $carrinho = Container::getModel('Carrinho');
        $subcategoria = Container::getModel('Subcategoria');
        $produto = Container::getModel('Produto');

        if (!$_GET['id']) {
            header('Location: /');
        }

        $produto->__set('id', $_GET['id']);


        $this->view->produtos = $produto->getProdutoPorId();

        $categoria->__set('id', $this->view->produtos['id_categoria']);
        $carrinho->__set('id_usuario', $_SESSION['id']);

        //retorna a categoria do produto
        $this->view->categorias_especifica = $categoria->getCategoria();

        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();

        $this->render('produto');
    }

}
