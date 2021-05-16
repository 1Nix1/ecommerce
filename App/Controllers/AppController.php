<?php

namespace App\Controllers;

//Recursos framework

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{

    public function carrinho()
    {

        session_start();
        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');
        $endereco = Container::getModel('Endereco');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');
        $transportadora = Container::getModel('Transportadora');



        $carrinho->__set('id_usuario', $_SESSION['id']);
        $endereco->__set('id_usuario', $_SESSION['id']);

        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();
        //Busca itens carrinho
        $this->view->itens = $carrinho->getFormatado();
        //Total carrinho
        $this->view->total_carrinho = $carrinho->totalCarrinho();
        //Busca transportadora
        $this->view->transportadoras = $transportadora->getTransportadora();
        //Busca endereco
        $this->view->enderecos = $endereco->getAll();


        $this->render('carrinho');
    }

    public function addCarrinho()
    {
        session_start();
        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');
        $produto = Container::getModel('Produto');

        $produto->__set('id', $_POST['id_produto']);
        $quantidade = $produto->getQuantidade();

        if (isset($_POST['tamanho-camisa']) && isset($_POST['quantidade']) && $_POST['quantidade'] >= 1 && $_POST['quantidade'] <= $quantidade['quantidade']) {
            $carrinho->__set('id_produto', $_POST['id_produto']);
            $carrinho->__set('id_usuario', $_SESSION['id']);
            $carrinho->__set('tamanho', $_POST['tamanho-camisa']);
            $carrinho->__set('quantidade', $_POST['quantidade']);
            $carrinho->__set('valor_unit', $_POST['valor_unit']);

            $carrinho->insertCarrinho();

            header('Location: /carrinho');
        } else if (!isset($_POST['tamanho-camisa']) || !isset($_POST['quantidade']) || $_POST['quantidade'] <= 0 || !isset($_POST['tamanho-camisa'])) {
            header('Location: /produto?id=' . $_POST['id_produto'] . '&errorCampos=true');
        } else if ($_POST['quantidade'] > $quantidade['quantidade']){
            header('Location: /produto?id=' . $_POST['id_produto'] . '&quantidade=true');
        } else {
            header('Location: /produto?id=' . $_POST['id_produto']);
        }
    }

    public function removeItem()
    {
        session_start();
        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');

        if (isset($_GET['id']) && $_GET['id'] != '') {
            $carrinho->__set('id', $_GET['id']);
            $carrinho->__set('id_usuario', $_SESSION['id']);

            $carrinho->removeItem();
            header('Location: /carrinho');
        }
    }

    public function addFrete()
    {
        session_start();
        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');

        $carrinho->__set('id_usuario', $_SESSION['id']);
        $carrinho->__set('transportadora', $_GET['id']);

        $carrinho->updateFrete();

        header('Location: /carrinho');
    }

    public function addEnderecoCarrinho()
    {
        session_start();
        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');

        $carrinho->__set('id_usuario', $_SESSION['id']);
        $carrinho->__set('endereco', $_GET['id']);

        $carrinho->updateEndereco();

        header('Location: /carrinho');
    }

    public function paginaUsuario()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();
        //Inicio buscar produtos
        $carrinho = Container::getModel('Carrinho');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        $carrinho->__set('id_usuario', $_SESSION['id']);

        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();

        $this->render('pagina_usuario');
    }

    public function pedidoUsuario()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');
        $pedido = Container::getModel('Pedido');
        //Inicio buscar produtos
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        $carrinho->__set('id_usuario', $_SESSION['id']);
        $pedido->__set('id_usuario', $_SESSION['id']);

        $this->view->getPedido = $pedido->getAll();
        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();

        $this->render('pedido_usuario');
    }

    public function itensPedido()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');
        $itens_pedido = Container::getModel('ItensPedido');
        //Inicio buscar produtos
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        $carrinho->__set('id_usuario', $_SESSION['id']);
        $itens_pedido->__set('id_usuario', $_SESSION['id']);
        $itens_pedido->__set('id_pedido', $_GET['pedido']);

        //itens do pedido
        $this->view->itens_pedido = $itens_pedido->getAll();
        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();

        $this->render('itens_pedido');
    }

    public function enderecoUsuario()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');
        $endereco = Container::getModel('Endereco');

        $endereco->__set('id_usuario', $_SESSION['id']);
        $carrinho->__set('id_usuario', $_SESSION['id']);

        $this->view->enderecos = $endereco->getAll();

        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();
        $this->render('endereco_usuario');
    }

    public function editaUsuario()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');
        $carrinho = Container::getModel('Carrinho');
        $carrinho->__set('id_usuario', $_SESSION['id']);
        $usuario->__set('id', $_SESSION['id']);

        $this->view->getEditUser = $usuario->getEditUser();

        $this->view->usuario = array(
            'nome' => $this->view->getEditUser['nome'],
            'sobrenome' => $this->view->getEditUser['sobrenome'],
            'email' => $this->view->getEditUser['email'],
            'cpf' => $this->view->getEditUser['cpf']
        );

        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();
        if (isset($_GET['erro']) && $_GET['erro'] = 'true') {
            //Verifica se á algum erro no cadastro
            $this->view->erroCadastro = true;
        }
        $this->render('edita_usuario');
    }

    public function editarUsuario()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        if ($_POST['nome'] == '' || $_POST['sobrenome'] == '' || $_POST['email'] == '' || $_POST['cpf'] == '') {
            header('Location: /edita_usuario?erro=true');
        }
        $usuario->__set('id', $_SESSION['id']);
        $usuario->__set('nome', $_POST['nome']);
        $usuario->__set('sobrenome', $_POST['sobrenome']);
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('cpf', $_POST['cpf']);

        $retorno = $usuario->updateUsuario();

        $_SESSION['nome'] = $usuario->__get('nome');
        $_SESSION['sobrenome'] = $usuario->__get('sobrenome');
        $_SESSION['email'] = $usuario->__get('email');

        header('Location: /pagina_usuario');
    }

    public function mudarSenha()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');
        $carrinho = Container::getModel('Carrinho');
        $carrinho->__set('id_usuario', $_SESSION['id']);
        $usuario->__set('id', $_SESSION['id']);

        if (isset($_GET['erro']) && $_GET['erro'] == 'true') {
            $this->view->alteraSenha = 'erro';
        } else if (isset($_GET['erro']) && $_GET['erro'] == 'conf-senha') {
            $this->view->alteraSenha = 'conf-senha';
        } else {
            $this->view->alteraSenha = '';
        }

        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();
        $this->render('mudar_senha');
    }

    public function mudaSenha()
    {

        session_start();

        $usuario = Container::getModel('Usuario');

        $usuario->__set('id', $_SESSION['id']);
        $usuario->__set('senha', $_POST['senha']);
        $usuario->__set('conf_senha', $_POST['conf-senha']);

        echo $usuario->validaSenha();
        if ($usuario->validaSenha() == 'senha-invalida') {

            header('Location: /mudar_senha?erro=true');
        } else if ($usuario->validaSenha() == 'senhas-diferentes') {

            header('Location: /mudar_senha?erro=conf-senha');
        } else {
            $usuario->updateSenha();
            echo 'alert("Senha alterada com sucesso!!")';
            header('Location: /pagina_usuario');
        }

        /*if($_POST['senha'] != $_POST['conf-senha']){
            header('Location: /mudar_senha?erro=conf-senha');
        }

        if(strlen($_POST['senha']) < 4){
            header('Location: /mudar_senha?erro=true');
        }*/
    }

    public function addNewEndereco()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');
        $estado = Container::getModel('Estado');
        $pais = Container::getModel('Pais');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        $this->view->endereco = array(
            'nome' => '',
            'sobrenome' => '',
            'telefone' => '',
            'cep' => '',
            'cidade' => '',
            'estado' => '',
            'rua' => '',
            'bairro' => '',
            'numero' => '',
            'complemento' => '',
            'pais' => ''
        );

        $carrinho->__set('id_usuario', $_SESSION['id']);

        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();
        //Busca estado
        $this->view->estado = $estado->getAll();
        //Busca pais
        $this->view->pais = $pais->getAll();

        $this->view->erroCadastro = false;
        $this->render('add_new_endereco');
    }

    public function cadastrarEndereco()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');
        $estado = Container::getModel('Estado');
        $pais = Container::getModel('Pais');
        $endereco = Container::getModel('Endereco');

        if ($_POST['nome'] == '' || $_POST['sobrenome'] == '' || $_POST['telefone'] == '' || $_POST['cep'] == '' || $_POST['cidade'] == '' || $_POST['estado'] == '' || $_POST['rua'] == '' || $_POST['bairro'] == '' || $_POST['numero'] == '' || $_POST['pais'] == '') {
            $this->view->endereco = array(
                'nome' => $_POST['nome'],
                'sobrenome' => $_POST['sobrenome'],
                'telefone' => $_POST['telefone'],
                'cep' => $_POST['cep'],
                'cidade' => $_POST['cidade'],
                'estado' => $_POST['estado'],
                'rua' => $_POST['rua'],
                'bairro' => $_POST['bairro'],
                'numero' => $_POST['numero'],
                'complemento' => $_POST['complemento'],
                'pais' => $_POST['pais']
            );

            $carrinho->__set('id_usuario', $_SESSION['id']);

            //quantidades de itens no carrinho
            $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
            //Busca estado
            $this->view->estado = $estado->getAll();
            //Busca pais
            $this->view->pais = $pais->getAll();

            $this->view->erroCadastro = true;

            $this->render('add_new_endereco');
        } else {

            $endereco->__set('id_usuario', $_SESSION['id']);
            $endereco->__set('nome', $_POST['nome']);
            $endereco->__set('sobrenome', $_POST['sobrenome']);
            $endereco->__set('telefone', $_POST['telefone']);
            $endereco->__set('cep', $_POST['cep']);
            $endereco->__set('cidade', $_POST['cidade']);
            $endereco->__set('id_estado', $_POST['estado']);
            $endereco->__set('rua', $_POST['rua']);
            $endereco->__set('bairro', $_POST['bairro']);
            $endereco->__set('numero', $_POST['numero']);
            $endereco->__set('complemento', $_POST['complemento']);
            $endereco->__set('id_pais', $_POST['pais']);

            $endereco->salvar();
            header('Location: /endereco_usuario');
        }
    }

    public function excluirEndereco()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $endereco = Container::getModel('Endereco');

        $endereco->__set('id', $_GET['id_endereco']);
        $endereco->__set('id_usuario', $_SESSION['id']);

        $endereco->excluir();

        header('Location: /endereco_usuario');
    }

    public function editarEndereco()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');
        $estado = Container::getModel('Estado');
        $pais = Container::getModel('Pais');

        $endereco = Container::getModel('Endereco');

        $carrinho->__set('id_usuario', $_SESSION['id']);
        $endereco->__set('id', $_GET['id_endereco']);
        $endereco->__set('id_usuario', $_SESSION['id']);


        $this->view->enderecos = $endereco->getEdit();

        $this->view->valueEndereco = array(
            'id' => $this->view->enderecos['id'],
            'nome' => $this->view->enderecos['nome'],
            'sobrenome' => $this->view->enderecos['sobrenome'],
            'telefone' => $this->view->enderecos['telefone'],
            'cep' => $this->view->enderecos['cep'],
            'cidade' => $this->view->enderecos['cidade'],
            'estado' => $this->view->enderecos['estado'],
            'id_estado' => $this->view->enderecos['id_estado'],
            'rua' => $this->view->enderecos['rua'],
            'bairro' => $this->view->enderecos['bairro'],
            'numero' => $this->view->enderecos['numero'],
            'complemento' => $this->view->enderecos['complemento'],
            'pais' => $this->view->enderecos['pais'],
            'id_pais' => $this->view->enderecos['id_pais']
        );
        $this->view->erroCadastro = false;

        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();
        //Busca estado
        $this->view->estado = $estado->getAll();
        //Busca pais
        $this->view->pais = $pais->getAll();

        $this->render('editar_endereco');
    }

    public function editaEndereco()
    {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        $carrinho = Container::getModel('Carrinho');
        $endereco = Container::getModel('Endereco');

        if ($_POST['id'] == '' || $_POST['nome'] == '' || $_POST['sobrenome'] == '' || $_POST['telefone'] == '' || $_POST['cep'] == '' || $_POST['cidade'] == '' || $_POST['estado'] == '' || $_POST['rua'] == '' || $_POST['bairro'] == '' || $_POST['numero'] == '' || $_POST['pais'] == '') {
            $this->view->endereco = array(
                'id' => $_POST['id'],
                'nome' => $_POST['nome'],
                'sobrenome' => $_POST['sobrenome'],
                'telefone' => $_POST['telefone'],
                'cep' => $_POST['cep'],
                'cidade' => $_POST['cidade'],
                'estado' => $_POST['estado'],
                'rua' => $_POST['rua'],
                'bairro' => $_POST['bairro'],
                'numero' => $_POST['numero'],
                'complemento' => $_POST['complemento'],
                'pais' => $_POST['pais']
            );

            $estado = Container::getModel('Estado');
            $pais = Container::getModel('Pais');

            //quantidades de itens no carrinho
            $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
            //Busca estado
            $this->view->estado = $estado->getAll();
            //Busca pais
            $this->view->pais = $pais->getAll();

            $this->view->erroCadastro = true;

            $this->render('editar_endereco');
        } else {

            $endereco->__set('id', $_POST['id']);
            $endereco->__set('id_usuario', $_SESSION['id']);
            $endereco->__set('nome', $_POST['nome']);
            $endereco->__set('sobrenome', $_POST['sobrenome']);
            $endereco->__set('telefone', $_POST['telefone']);
            $endereco->__set('cep', $_POST['cep']);
            $endereco->__set('cidade', $_POST['cidade']);
            $endereco->__set('id_estado', $_POST['estado']);
            $endereco->__set('rua', $_POST['rua']);
            $endereco->__set('bairro', $_POST['bairro']);
            $endereco->__set('numero', $_POST['numero']);
            $endereco->__set('complemento', $_POST['complemento']);
            $endereco->__set('id_pais', $_POST['pais']);

            $endereco->editar();

            header('Location: /endereco_usuario');
        }
    }

    public function confirmaEndereco()
    {
        session_start();
        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        print_r($_GET);

        if ($_GET['id_endereco'] == '') {
            header('Location: /carrinho?empty_endereco=true');
        } else {
            header("Location: /dados_cartao?endereco=" . $_GET['id_endereco'] . "");
        }
    }

    public function dadosCartao()
    {
        session_start();
        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        if (!$_GET['endereco'] || $_GET['endereco'] == '') {
            header('Location: /carrinho?empty_endereco=true');
        }

        $carrinho = Container::getModel('Carrinho');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');
        $transportadora = Container::getModel('Transportadora');

        $carrinho->__set('id_usuario', $_SESSION['id']);

        //quantidades de itens no carrinho
        $this->view->quantidadeItensCarrinho = $carrinho->contaItensCarrinho();
        //Buscar categorias
        $this->view->categorias = $categoria->getAll();
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();
        //Busca itens carrinho
        $this->view->itens = $carrinho->getFormatado();
        //Total carrinho
        $this->view->total_carrinho = $carrinho->totalCarrinho();
        //Busca transportadora
        $this->view->transportadoras = $transportadora->getTransportadora();


        $this->render('dados_cartao');
    }

    public function geraPedido()
    {
        session_start();
        $usuario = Container::getModel('Usuario');
        $usuario->authLogin();

        //$itensPedido = Container::getModel('ItensPedido');
        $carrinho = Container::getModel('Carrinho');
        $pedido = Container::getModel('Pedido');

        $carrinho->__set('id_usuario', $_SESSION['id']);

        //Busca itens carrinho
        $this->view->itens = $carrinho->getForPedido();
        $this->view->itens_pedido = $carrinho->getForItensPedido();

        foreach ($this->view->itens as $id_item => $item) {
            $pedido->__set('id_usuario', $_SESSION['id']);
            $pedido->__set('id_endereco', $item['endereco']);
            $pedido->__set('id_transportadora', $item['transportadora']);
            $pedido->__set('total', $item['total']);
        }
        print_r($this->view->itens);
        //$pedido->geraPedido();

        //Gera o itens pedido
        //$id_pedido = $this->geraItensPedido();

        //altera o status do pedido para pago
        //$pedido->__set('id', $id_pedido);
        //$pedido->alterStatusPago();

        //Após a compra, remove os itens do carrinho do usuario
        //$this->removeItensCarrinho($_SESSION['id']);

        
        //header('Location: /');
    }

    public function geraItensPedido()
    {

        $itensPedido = Container::getModel('ItensPedido');
        $carrinho = Container::getModel('Carrinho');


        $carrinho->__set('id_usuario', $_SESSION['id']);

        //Busca itens carrinho
        $this->view->itens = $carrinho->getForPedido();
        $this->view->itens_pedido = $carrinho->getForItensPedido();

        foreach ($this->view->itens_pedido as $id_item => $item) {
            $itensPedido->__set('id_pedido', $item['id_pedido']);
            $itensPedido->__set('id_produto', $item['id_produtos']);
            $itensPedido->__set('id_usuario', $_SESSION['id']);
            $itensPedido->__set('tamanho', $item['tamanho']);
            $itensPedido->__set('quantidade', $item['quantidade']);
            $itensPedido->__set('valor_unit', $item['valor_unit']);
            $itensPedido->__set('total', $item['sub_total']);

            $itensPedido->geraItensPedido();
        }

        return $item['id_pedido'];
    }

    public function removeItensCarrinho($id_usuario)
    {
        $carrinho = Container::getModel('Carrinho');
        $carrinho->__set('id_usuario', $id_usuario);
        $carrinho->removeAllItens();
    }
}
