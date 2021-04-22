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
        $total_registros_pagina = 9;
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
        $produto->__set('categoria', str_replace('/admin/novo_produtos?id_categoria=', '', $_POST['categoria']));
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
        if(empty($_POST['id_produto']) ||empty($_POST['nome']) || empty($_POST['preco']) || empty($_POST['quantidade']) || empty($_POST['categoria']) || empty($_POST['subcategoria']) || empty($_FILES['arquivo'])) {
            header('Location: /admin/novo_produtos?campos_obrigatorios=true');
            exit();
        }
        //verifica se o valor é valido
        if($_POST['preco'] < 0) {
            header('Location: /admin/editar_produtos?campos_obrigatorios=true');
            exit();
        }

        print_r($_POST);

        echo str_replace('/admin/editar_produto?id_produto='.$_POST['id_produto'].'&id_categoria=', '', $_POST['categoria']);
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
        $produto->__set('categoria', str_replace('/admin/editar_produto?id_produto='.$_POST['id_produto'].'&id_categoria=', '', $_POST['categoria']));
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
        $total_registros_pagina = 9;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $categorias = $categoria->getAllPaginacao($total_registros_pagina, $deslocamento);
        $total_categorias = $categoria->getTotal();

        $this->view->total_de_paginas = ceil($total_categorias['total'] / $total_registros_pagina);
        $this->view->categorias = $categorias;
        
        if(isset($_POST['pesquisa'])){
            $categoria->__set('nome', $_POST['pesquisa']);
            $total_categorias = $categoria->getTotalPesquisa();
            $this->view->categorias = $categoria->pesquisaCategoria($total_registros_pagina, $deslocamento);
        }

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
        
        if(empty($_POST['nome']) || $_POST['nome'] == ''){
            header('Location: /admin/nova_subcategoria?campos_obrigatorios=true');
            exit();
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

    public function subcategoriasAdmin(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $subcategoria = Container::getModel('Subcategoria');

        //variaveis de páginação
        $total_registros_pagina = 9;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $subcategorias = $subcategoria->getAllPaginacao($total_registros_pagina, $deslocamento);
        $total_subcategorias = $subcategoria->getTotal();

        $this->view->total_de_paginas = ceil($total_subcategorias['total'] / $total_registros_pagina);
        $this->view->subcategorias = $subcategorias;
        
        if(isset($_POST['pesquisa'])){
            $subcategoria->__set('nome', $_POST['pesquisa']);
            $total_subcategorias = $subcategoria->getTotalPesquisa();
            $this->view->subcategorias = $subcategoria->pesquisaSubcategoria($total_registros_pagina, $deslocamento);
        }

        $this->render('subcategorias', 'layout_admin');
    }

    public function novaSubcategoria(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $categoria = Container::getModel('Categoria');

        if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'true'){
            $this->view->campos_obrigatorios = 'true';
        } else if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'false') {
            $this->view->campos_obrigatorios = 'false';
        } else {
            $this->view->campos_obrigatorios = '';
        }
        
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        $this->render('nova_subcategoria', 'layout_admin');
    }

    public function cadastraSubcategoria(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $subcategoria = Container::getModel('Subcategoria');

        $status = '';

        if(isset($_POST['ativado'])){
            $status = 'ativo';
        }

        if(empty($_POST['nome']) || $_POST['nome'] == '' || empty($_POST['categoria']) || $_POST['categoria'] == ''){
            header('Location: /admin/nova_subcategoria?campos_obrigatorios=true');
            exit();
        }

        $subcategoria->__set('nome', $_POST['nome']);
        $subcategoria->__set('id_categoria', $_POST['categoria']);
        $subcategoria->__set('status', $status);

        $subcategoria->cadastraSubcategoria();

        header('Location: /admin/nova_subcategoria?campos_obrigatorios=false');
    }

    public function editarSubcategoria(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        $subcategoria->__set('id', $_GET['id_subcategoria']);

        $this->view->getEditSubcategoria = $subcategoria->getSubcategoria();

        $this->view->edita_subcategoria = array(
            'id' => $this->view->getEditSubcategoria['id'],
            'nome' => $this->view->getEditSubcategoria['nome'],
            'id_categoria' => $this->view->getEditSubcategoria['id_categoria'],
            'categoria' => $this->view->getEditSubcategoria['categoria'],
            'status' => $this->view->getEditSubcategoria['status']
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

        $this->render('editar_subcategoria', 'layout_admin');
    }

    public function editaSubcategoria(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $subcategoria = Container::getModel('Subcategoria');

        $status = '';

        if(isset($_POST['ativado'])){
            $status = 'ativo';
        }

        if(empty($_POST['id']) || $_POST['id'] == '' ||empty($_POST['nome']) || $_POST['nome'] == '' || empty($_POST['categoria']) || $_POST['categoria'] == ''){
            header('Location: /admin/editar_subcategoria?campos_obrigatorios=true&id_subcategoria='.$_POST['id']);
            exit();
        }

        $subcategoria->__set('id', $_POST['id']);
        $subcategoria->__set('id_categoria', $_POST['categoria']);
        $subcategoria->__set('nome', $_POST['nome']);
        $subcategoria->__set('status', $status);

        $subcategoria->editaSubcategoria();
        
        header('Location: /admin/editar_subcategoria?campos_obrigatorios=false&id_subcategoria='.$_POST['id']);
    }

    public function pedidosAdmin(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $pedido = Container::getModel('Pedido');

        //variaveis de páginação
        $total_registros_pagina = 9;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $pedidos = $pedido->getPorPagina($total_registros_pagina, $deslocamento);
        $total_pedidos = $pedido->getTotalRegistros();

        //paginação
        $this->view->total_de_paginas = ceil($total_pedidos['total'] / $total_registros_pagina);
        $this->view->pedidos = $pedidos;
        //retorna os pedidos
        $this->view->pedidos = $pedidos;

        if(isset($_POST['pesquisa'])){
            $total_pedidos = $pedido->getTotalPesquisa($_POST['pesquisa']);
            $this->view->pedidos = $pedido->pesquisaPedido($total_registros_pagina, $deslocamento, $_POST['pesquisa']);
        }

        $this->render('pedidos', 'layout_admin');
    }

    public function itensPedidoAdmin(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();
        
        $itens_pedido = Container::getModel('ItensPedido');

        $itens_pedido->__set('id_pedido', $_GET['id_pedido']);
        $itens_pedido->__set('id_usuario', $_GET['id_usuario']);
        
        $this->view->iten_pedido = $itens_pedido->getAll();
        $this->view->email = $itens_pedido->getEmailUsuario();

        $this->render('itens_pedido', 'layout_admin');
    }

    public function editarPedido(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $pedido = Container::getModel('Pedido');

        $pedido->__set('id', $_GET['id_pedido']);
        $pedido->__set('id_usuario', $_GET['id_usuario']);

        $this->view->pedido = $pedido->getPedido();

        $this->render('editar_pedido', 'layout_admin');
    }

    public function editaPedido(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $pedido = Container::getModel('Pedido');

        $status = 'pago';

        if(isset($_POST['enviado'])){
            $status = 'enviado';
        }

        $pedido->__set('id', $_POST['id']);
        $pedido->__set('status', $status);
        
        $pedido->updatePedidoEnviado();

        header('Location: /admin/editar_pedido?id_pedido='.$_POST['id'].'&id_usuario='.$_POST['id_usuario']);
    }

    //ESTADOS
    public function estadosAdmin(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $estato = Container::getModel('Estado');

        //variaveis de páginação
        $total_registros_pagina = 9;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $estatos = $estato->getPorPagina($total_registros_pagina, $deslocamento);
        $total_estatos = $estato->getTotal();

        $this->view->total_de_paginas = ceil($total_estatos['total'] / $total_registros_pagina);
        $this->view->estatos = $estatos;

        if(isset($_POST['pesquisa'])){
            $estato->__set('nome', $_POST['pesquisa']);
            $total_estatos = $estato->getTotalPesquisa();
            $this->view->estatos = $estato->pesquisaEstado($total_registros_pagina, $deslocamento);
        }

        $this->render('estados', 'layout_admin');
    }

    public function novoEstado(){
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

        $this->render('novo_estado', 'layout_admin');
    }

    public function cadastraEstado(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $estado = Container::getModel('Estado');

        $status = '';

        if(isset($_POST['ativado'])){
            $status = 'ativo';
        }

        if(empty($_POST['nome']) || $_POST['nome'] == ''){
            header('Location: /admin/novo_estado?campos_obrigatorios=true');
            exit();
        }

        $estado->__set('nome', mb_strtoupper($_POST['nome']));
        $estado->__set('status', $status);

        $estado->cadastraEstado();

        header('Location: /admin/novo_estado?campos_obrigatorios=false');
    }

    public function editarEstado(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $estado = Container::getModel('Estado');

        $estado->__set('id', $_GET['id_estado']);

        $this->view->getEditEstado = $estado->getEstado();

        $this->view->edita_estado = array(
            'id' => $this->view->getEditEstado['id'],
            'nome' => $this->view->getEditEstado['nome'],
            'status' => $this->view->getEditEstado['status']
        );

        if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'true'){
            $this->view->campos_obrigatorios = 'true';
        } else if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'false') {
            $this->view->campos_obrigatorios = 'false';
        } else {
            $this->view->campos_obrigatorios = '';
        }

        $this->render('editar_estado', 'layout_admin');
    }

    public function editaEstado(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $estado = Container::getModel('Estado');

        $status = '';

        if(isset($_POST['ativado'])){
            $status = 'ativo';
        }

        if($_POST['nome'] == ''){
            header('Location: /admin/editar_estado?campos_obrigatorios=true&id_estado='.$_POST['id']);
            exit();
        }

        $estado->__set('id', $_POST['id']);
        $estado->__set('nome', $_POST['nome']);
        $estado->__set('status', $status);

        $estado->editaEstado();

        header('Location: /admin/editar_estado?campos_obrigatorios=false&id_estado='.$_POST['id']);
    }

    //PAISES
    public function paisesAdmin(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $pais = Container::getModel('Pais');

        //variaveis de páginação
        $total_registros_pagina = 9;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registros_pagina;
        $this->view->pagina_ativa = $pagina;

        $paises = $pais->getPorPagina($total_registros_pagina, $deslocamento);
        $total_paises = $pais->getTotal();

        $this->view->total_de_paginas = ceil($total_paises['total'] / $total_registros_pagina);
        $this->view->paises = $paises;

        if(isset($_POST['pesquisa'])){
            $pais->__set('nome', $_POST['pesquisa']);
            $total_paises = $pais->getTotalPesquisa();
            $this->view->paises = $pais->pesquisaPais($total_registros_pagina, $deslocamento);
        }

        $this->render('paises', 'layout_admin');
    }

    public function novoPais(){
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

        $this->render('novo_pais', 'layout_admin');
    }

    public function cadastraPais(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $pais = Container::getModel('Pais');

        $status = '';

        if(isset($_POST['ativado'])){
            $status = 'ativo';
        }

        if(empty($_POST['nome']) || $_POST['nome'] == ''){
            header('Location: /admin/novo_pais?campos_obrigatorios=true');
            exit();
        }

        $pais->__set('nome', $_POST['nome']);
        $pais->__set('status', $status);

        $pais->cadastraPais();

        header('Location: /admin/novo_pais?campos_obrigatorios=false');
    }

    public function editarPais(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $pais = Container::getModel('Pais');

        $pais->__set('id', $_GET['id_pais']);

        $this->view->getEditPais = $pais->getPais();

        $this->view->edita_pais = array(
            'id' => $this->view->getEditPais['id'],
            'nome' => $this->view->getEditPais['nome'],
            'status' => $this->view->getEditPais['status']
        );

        if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'true'){
            $this->view->campos_obrigatorios = 'true';
        } else if(isset($_GET['campos_obrigatorios']) && $_GET['campos_obrigatorios'] == 'false') {
            $this->view->campos_obrigatorios = 'false';
        } else {
            $this->view->campos_obrigatorios = '';
        }

        $this->render('editar_pais', 'layout_admin');
    }

    public function editaPais(){
        session_start();
        $usuario = Container::getModel('UsuarioAdmin');
        $usuario->authLogin();

        $pais = Container::getModel('Pais');

        $status = '';

        if(isset($_POST['ativado'])){
            $status = 'ativo';
        }

        if($_POST['nome'] == ''){
            header('Location: /admin/editar_pais?campos_obrigatorios=true&id_pais='.$_POST['id']);
            exit();
        }

        $pais->__set('id', $_POST['id']);
        $pais->__set('nome', $_POST['nome']);
        $pais->__set('status', $status);

        $pais->editaPais();

        header('Location: /admin/editar_pais?campos_obrigatorios=false&id_pais='.$_POST['id']);
    }
}
