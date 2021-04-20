<?php

namespace App\Controllers;

//Recursos framework
use MF\Controller\Action;
use MF\Model\Container;

class AdminController extends Action
{
    //ADMIN
    public function dashboard()
    {
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $this->render('dashboard', 'layout_admin');
    }

    public function loginAdmin(){
        session_start();
        if (isset($_SESSION['id'])) {
            header('Location: /admin/dashboard');
        } else {
            $this->render('login', 'layout_login_admin');
        }
        
    }

    public function produtosAdmin(){

        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();
        
        $produto = Container::getModel('Produto');

        //variaveis de páginação
        $total_registros_pagina = 16;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $produtos = $produto->getPorPagina($total_registros_pagina, $deslocamento);
        $total_produtos = $produto->getTotalRegistros();

        //paginação
        $this->view->total_de_paginas = ceil($total_produtos['total'] / $total_registros_pagina);
        $this->view->produtos = $produtos;
        //retorna os produtos
        $this->view->produtos = $produtos;

        if(isset($_POST['pesquisa'])){
            $produto->__set('nome', $_POST['pesquisa']);
            $total_produtos = $produto->getTotalPesquisa();
            $this->view->produtos = $produto->pesquisaProduto($total_registros_pagina, $deslocamento);
        }

        $this->render('produtos', 'layout_admin');

    }

    public function novoProdutoAdmin(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'true'){
            $this->view->campos_obrigatorios = 'true';
        } else if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'false') {
            $this->view->campos_obrigatorios = 'false';
        } else {
            $this->view->campos_obrigatorios = '';
        }

        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();
        
        $this->render('novo_produto', 'layout_admin');
    }

    public function cadastraProdutoAdmin(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $produto = Container::getModel('Produto');

        //Verifica se á dados
        if(empty($_POST['nome']) || empty($_POST['preco']) || empty($_POST['quantidade']) || empty($_POST['categoria']) || empty($_POST['subcategoria']) || empty($_FILES['arquivo'])) {
            header('Location: /admin/novo_produtos?campos_obrigatorios=true');
            exit();
        }

        //Verifica se á dados
        if($_POST['preco'] < 0) {
            header('Location: /admin/novo_produtos?campos_obrigatorios=true');
            exit();
        }

        if(isset($_POST['enviar-formulario'])){
            $formatosPermitidos = array("png", "jpeg", "jpg");
            $extensao = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
        
            if(in_array($extensao, $formatosPermitidos)){
                $pasta = "./img_produtos/";
                $temporario = $_FILES['arquivo']['tmp_name'];
                $novoNome = uniqid().".$extensao";
        
                if(move_uploaded_file($temporario, $pasta.$novoNome)){
                    $mensagem = "Upload feito com sucesso!";
                    echo $pasta.$novoNome;
                    echo $mensagem;
                }else{
                    header('Location: /admin/novo_produtos?campos_obrigatorios=true');
                    exit();
                }
            }else{
                header('Location: /admin/novo_produtos?campos_obrigatorios=true');
                exit();
            }
        }else{
            header('Location: /admin/novo_produtos?campos_obrigatorios=true');
            exit();
        }

        $produto->__set('nome', $_POST['nome']);
        $produto->__set('descricao', $_POST['descricao']);
        $produto->__set('valor', str_replace(",", ".", $_POST['preco']));
        $produto->__set('quantidade', $_POST['quantidade']);
        $produto->__set('categoria', $_POST['categoria']);
        $produto->__set('subcategoria', $_POST['subcategoria']);
        $produto->__set('imagem', $novoNome);

        $produto->cadastraProduto();

        header('Location: /admin/novo_produtos?campos_obrigatorios=false');
    }

    public function excluiProduto(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $produto = Container::getModel('Produto');

        $produto->__set('id', $_GET['id_produto']);

        $produto->excluiProduto();

        header('Location: /admin/produtos');
    }

    public function editarProduto(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $produto = Container::getModel('Produto');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        $produto->__set('id', $_GET['id_produto']);
        $this->view->getEditProduto = $produto->getProdutoPorId();

        $this->view->edita_produto = array(
            'id' => $this->view->getEditProduto['id'],
            'nome' => $this->view->getEditProduto['nome'],
            'descricao' => $this->view->getEditProduto['descricao'],
            'preco' => $this->view->getEditProduto['valor'],
            'quantidade' => $this->view->getEditProduto['quantidade'],
            'categoria' => $this->view->getEditProduto['id_categoria'],
            'subcategoria' => $this->view->getEditProduto['id_subcategoria'],
            'imagem' => $this->view->getEditProduto['imagem']
        );

        if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'true'){
            $this->view->campos_obrigatorios = 'true';
        } else if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'false') {
            $this->view->campos_obrigatorios = 'false';
        } else {
            $this->view->campos_obrigatorios = '';
        }
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();
        
        $this->render('editar_produto', 'layout_admin');
    }

    public function editaProduto(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        //Verifica se á dados
        if(empty($_POST['nome']) || empty($_POST['preco']) || empty($_POST['quantidade']) || empty($_POST['categoria']) || empty($_POST['subcategoria']) || empty($_FILES['arquivo'])) {
            header('Location: /admin/novo_produtos?campos_obrigatorios=true');
            exit();
        }
        //verifica se o valor é valido
        if($_POST['preco'] < 0) {
            header('Location: /admin/editar_produtos?campos_obrigatorios=true');
            exit();
        }

        $produto = Container::getModel('Produto');
        if(isset($_FILES['arquivo']['name']) && $_FILES['arquivo']['name'] != ''){
            if(isset($_POST['enviar-formulario'])){
                $formatosPermitidos = array("png", "jpeg", "jpg");
                $extensao = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
            
                if(in_array($extensao, $formatosPermitidos)){
                    $pasta = "./img_produtos/";
                    $temporario = $_FILES['arquivo']['tmp_name'];
                    $novoNome = uniqid().".$extensao";

                    //deleta a imagem antiga
                    $imagem = $_POST['img-name'];
                    unlink($pasta.$imagem);
                    if(move_uploaded_file($temporario, $pasta.$novoNome)){
                        $mensagem = "Upload feito com sucesso!";
                    }else{
                        header('Location: /admin/editar_produto?campos_obrigatorios=true');
                        exit();
                    }
                }else{
                    header('Location: /admin/editar_produto?campos_obrigatorios=true');
                    exit();
                }
            }else{
                header('Location: /admin/editar_produto?campos_obrigatorios=true');
                exit();
            }
        }else{

            $novoNome = $_POST['img-name'];
        }

        $produto->__set('id', $_POST['id_produto']);
        $produto->__set('nome', $_POST['nome']);
        $produto->__set('descricao', $_POST['descricao']);
        $produto->__set('valor', str_replace(",", ".", $_POST['preco']));
        $produto->__set('quantidade', $_POST['quantidade']);
        $produto->__set('categoria', $_POST['categoria']);
        $produto->__set('subcategoria', $_POST['subcategoria']);
        $produto->__set('imagem', $novoNome);

        $produto->editaProduto();

        header('Location: /admin/editar_produto?campos_obrigatorios=false&id_produto='.$_POST['id_produto']);
    }

    public function categoriasAdmin(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $categoria = Container::getModel('Categoria');

        //variaveis de páginação
        $total_registros_pagina = 16;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $categorias = $categoria->getAllPaginacao($total_registros_pagina, $deslocamento);
        $total_categorias = $categoria->getTotal();

        $this->view->total_de_paginas = ceil($total_categorias['total'] / $total_registros_pagina);
        $this->view->categorias = $categorias;
        
        $this->render('categorias', 'layout_admin');
    }

    public function novaCategoria(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'true'){
            $this->view->campos_obrigatorios = 'true';
        } else if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'false') {
            $this->view->campos_obrigatorios = 'false';
        } else {
            $this->view->campos_obrigatorios = '';
        }
        
        $this->render('nova_categoria', 'layout_admin');
    }

    public function cadastraCategoria(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $categoria = Container::getModel('Categoria');

        $status = '';

        if(isset($_POST['ativado'])){
            $status = 'ativo';
        }
        
        $categoria->__set('nome', $_POST['nome']);
        $categoria->__set('status', $status);

        $categoria->cadastraCategoria();

        header('Location: /admin/nova_categoria?campos_obrigatorios=false');
    }

    public function editarCategoria(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();
        
        $categoria = Container::getModel('Categoria');

        $categoria->__set('id', $_GET['id_categoria']);

        $this->view->getEditCategoria = $categoria->getCategoria();

        $this->view->edita_categoria = array(
            'id' => $this->view->getEditCategoria['id'],
            'nome' => $this->view->getEditCategoria['nome'],
            'status' => $this->view->getEditCategoria['status']
        );

        if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'true'){
            $this->view->campos_obrigatorios = 'true';
        } else if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'false') {
            $this->view->campos_obrigatorios = 'false';
        } else {
            $this->view->campos_obrigatorios = '';
        }

        $this->render('editar_categoria', 'layout_admin');
    }

    public function editaCategoria(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $categoria = Container::getModel('Categoria');

        $status = '';

        if(isset($_POST['ativado'])){
            $status = 'ativo';
        }

        if($_POST['nome'] == ''){
            header('Location: /admin/editar_categoria?campos_obrigatorios=true&id_categoria='.$_POST['id']);
            exit();
        }

        $categoria->__set('id', $_POST['id']);
        $categoria->__set('nome', $_POST['nome']);
        $categoria->__set('status', $status);

        $categoria->editaCategoria();

        header('Location: /admin/editar_categoria?campos_obrigatorios=false&id_categoria='.$_POST['id']);
    }
}
