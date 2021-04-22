<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{

    protected function initRoutes()
    {

        $routes['home'] = array(
            'route' => '/',
            'controller' => 'IndexController',
            'action' => 'index'
        );

        $routes['inscreverse'] = array(
            'route' => '/inscreverse',
            'controller' => 'IndexController',
            'action' => 'inscreverse'
        );

        $routes['registrar'] = array(
            'route' => '/registrar',
            'controller' => 'IndexController',
            'action' => 'registrar'
        );

        $routes['login'] = array(
            'route' => '/login',
            'controller' => 'IndexController',
            'action' => 'login'
        );

        $routes['edita_usuario'] = array(
            'route' => '/edita_usuario',
            'controller' => 'AppController',
            'action' => 'editaUsuario'
        );

        $routes['editar_usuario'] = array(
            'route' => '/editar_usuario',
            'controller' => 'AppController',
            'action' => 'editarUsuario'
        );

        $routes['mudar_senha'] = array(
            'route' => '/mudar_senha',
            'controller' => 'AppController',
            'action' => 'mudarSenha'
        );

        $routes['muda_senha'] = array(
            'route' => '/muda_senha',
            'controller' => 'AppController',
            'action' => 'mudaSenha'
        );

        $routes['categoria'] = array(
            'route' => '/categoria',
            'controller' => 'IndexController',
            'action' => 'categoria'
        );

        $routes['subcategoria'] = array(
            'route' => '/subcategoria',
            'controller' => 'IndexController',
            'action' => 'categoria'
        );

        $routes['search'] = array(
            'route' => '/search',
            'controller' => 'IndexController',
            'action' => 'search'
        );

        $routes['produto'] = array(
            'route' => '/produto',
            'controller' => 'IndexController',
            'action' => 'produto'
        );

        $routes['add_carrinho'] = array(
            'route' => '/add_carrinho',
            'controller' => 'AppController',
            'action' => 'addCarrinho'
        );

        $routes['logar'] = array(
            'route' => '/logar',
            'controller' => 'AuthController',
            'action' => 'logar'
        );

        $routes['sair'] = array(
            'route' => '/sair',
            'controller' => 'AuthController',
            'action' => 'sair'
        );

        $routes['carrinho'] = array(
            'route' => '/carrinho',
            'controller' => 'AppController',
            'action' => 'carrinho'
        );

        $routes['remove-item'] = array(
            'route' => '/remove-item',
            'controller' => 'AppController',
            'action' => 'removeItem'
        );

        $routes['transportadora'] = array(
            'route' => '/transportadora',
            'controller' => 'AppController',
            'action' => 'addFrete'
        );

        $routes['endereco'] = array(
            'route' => '/endereco',
            'controller' => 'AppController',
            'action' => 'addEnderecoCarrinho'
        );

        $routes['pagina_usuario'] = array(
            'route' => '/pagina_usuario',
            'controller' => 'AppController',
            'action' => 'paginaUsuario'
        );

        $routes['pedido_usuario'] = array(
            'route' => '/pedido_usuario',
            'controller' => 'AppController',
            'action' => 'pedidoUsuario'
        );

        $routes['itens_pedido'] = array(
            'route' => '/itens_pedido',
            'controller' => 'AppController',
            'action' => 'itensPedido'
        );

        $routes['endereco_usuario'] = array(
            'route' => '/endereco_usuario',
            'controller' => 'AppController',
            'action' => 'enderecoUsuario'
        );

        $routes['add_new_endereco'] = array(
            'route' => '/add_new_endereco',
            'controller' => 'AppController',
            'action' => 'addNewEndereco'
        );

        $routes['cadastrar_endereco'] = array(
            'route' => '/cadastrar_endereco',
            'controller' => 'AppController',
            'action' => 'cadastrarEndereco'
        );

        $routes['excluir_endereco'] = array(
            'route' => '/excluir_endereco',
            'controller' => 'AppController',
            'action' => 'excluirEndereco'
        );

        $routes['editar_endereco'] = array(
            'route' => '/editar_endereco',
            'controller' => 'AppController',
            'action' => 'editarEndereco'
        );

        $routes['edita_endereco'] = array(
            'route' => '/edita_endereco',
            'controller' => 'AppController',
            'action' => 'editaEndereco'
        );

        $routes['confirma_endereco'] = array(
            'route' => '/confirma_endereco',
            'controller' => 'AppController',
            'action' => 'confirmaEndereco'
        );

        $routes['dados_cartao'] = array(
            'route' => '/dados_cartao',
            'controller' => 'AppController',
            'action' => 'dadosCartao'
        );

        $routes['gera_pedido'] = array(
            'route' => '/gera_pedido',
            'controller' => 'AppController',
            'action' => 'geraPedido'
        );

        //Admin
        $routes['dashboard'] = array(
            'route' => '/admin/dashboard',
            'controller' => 'AdminController',
            'action' => 'dashboard'
        );
        
        $routes['login_admin'] = array(
            'route' => '/admin/login_admin',
            'controller' => 'AdminController',
            'action' => 'loginAdmin'
        );

        $routes['logar_admin'] = array(
            'route' => '/admin/logar_admin',
            'controller' => 'AuthController',
            'action' => 'logarAdmin'
        );

        $routes['sair_admin'] = array(
            'route' => '/admin/sair',
            'controller' => 'AuthController',
            'action' => 'sairAdmin'
        );
        //PRODUTO
        $routes['produtos_admin'] = array(
            'route' => '/admin/produtos',
            'controller' => 'AdminController',
            'action' => 'produtosAdmin'
        );

        $routes['novo_produtos_admin'] = array(
            'route' => '/admin/novo_produtos',
            'controller' => 'AdminController',
            'action' => 'novoProdutoAdmin'
        );
        
        $routes['cadastra_produto_admin'] = array(
            'route' => '/admin/cadastra_produto',
            'controller' => 'AdminController',
            'action' => 'cadastraProdutoAdmin'
        );
        
        $routes['excluir_produto_admin'] = array(
            'route' => '/admin/excluir_produto',
            'controller' => 'AdminController',
            'action' => 'excluiProduto'
        );
        
        $routes['editar_produto_admin'] = array(
            'route' => '/admin/editar_produto',
            'controller' => 'AdminController',
            'action' => 'editarProduto'
        );

        $routes['edita_produto_admin'] = array(
            'route' => '/admin/edita_produto',
            'controller' => 'AdminController',
            'action' => 'editaProduto'
        );
        //CATEGORIA
        $routes['categorias_admin'] = array(
            'route' => '/admin/categorias',
            'controller' => 'AdminController',
            'action' => 'categoriasAdmin'
        );
        
        $routes['nova_categoria_admin'] = array(
            'route' => '/admin/nova_categoria',
            'controller' => 'AdminController',
            'action' => 'novaCategoria'
        );
        
        $routes['cadastra_categoria_admin'] = array(
            'route' => '/admin/cadastra_categoria',
            'controller' => 'AdminController',
            'action' => 'cadastraCategoria'
        );

        $routes['editar_categoria_admin'] = array(
            'route' => '/admin/editar_categoria',
            'controller' => 'AdminController',
            'action' => 'editarCategoria'
        );

        $routes['edita_categoria_admin'] = array(
            'route' => '/admin/edita_categoria',
            'controller' => 'AdminController',
            'action' => 'editaCategoria'
        );
        //SUBCATEGORIA
        $routes['subcategorias_admin'] = array(
            'route' => '/admin/subcategorias',
            'controller' => 'AdminController',
            'action' => 'subcategoriasAdmin'
        );

        $routes['nova_subcategoria_admin'] = array(
            'route' => '/admin/nova_subcategoria',
            'controller' => 'AdminController',
            'action' => 'novaSubcategoria'
        );

        $routes['cadastra_subcategoria_admin'] = array(
            'route' => '/admin/cadastra_subcategoria',
            'controller' => 'AdminController',
            'action' => 'cadastraSubcategoria'
        );

        $routes['editar_subcategoria_admin'] = array(
            'route' => '/admin/editar_subcategoria',
            'controller' => 'AdminController',
            'action' => 'editarSubcategoria'
        );

        $routes['edita_subcategoria_admin'] = array(
            'route' => '/admin/edita_subcategoria',
            'controller' => 'AdminController',
            'action' => 'editaSubcategoria'
        );
        //PEDIDO
        $routes['pedidos_admin'] = array(
            'route' => '/admin/pedidos',
            'controller' => 'AdminController',
            'action' => 'pedidosAdmin'
        );

        $routes['itens_pedido_admin'] = array(
            'route' => '/admin/itens_pedido',
            'controller' => 'AdminController',
            'action' => 'itensPedidoAdmin'
        );

        $routes['editar_pedido_admin'] = array(
            'route' => '/admin/editar_pedido',
            'controller' => 'AdminController',
            'action' => 'editarPedido'
        );

        $routes['edita_pedido_admin'] = array(
            'route' => '/admin/edita_pedido',
            'controller' => 'AdminController',
            'action' => 'editaPedido'
        );
        //ESTADO
        $routes['estados_admin'] = array(
            'route' => '/admin/estados',
            'controller' => 'AdminController',
            'action' => 'estadosAdmin'
        );
        
        $routes['novo_estado_admin'] = array(
            'route' => '/admin/novo_estado',
            'controller' => 'AdminController',
            'action' => 'novoEstado'
        );
        
        $routes['cadastra_estado_admin'] = array(
            'route' => '/admin/cadastra_estado',
            'controller' => 'AdminController',
            'action' => 'cadastraEstado'
        );
        
        $routes['editar_estado_admin'] = array(
            'route' => '/admin/editar_estado',
            'controller' => 'AdminController',
            'action' => 'editarEstado'
        );

        $routes['edita_estado_admin'] = array(
            'route' => '/admin/edita_estado',
            'controller' => 'AdminController',
            'action' => 'editaEstado'
        );
        //PAIS
        $routes['paises_admin'] = array(
            'route' => '/admin/paises',
            'controller' => 'AdminController',
            'action' => 'paisesAdmin'
        );
        
        $routes['novo_pais_admin'] = array(
            'route' => '/admin/novo_pais',
            'controller' => 'AdminController',
            'action' => 'novoPais'
        );
        
        $routes['cadastra_pais_admin'] = array(
            'route' => '/admin/cadastra_pais',
            'controller' => 'AdminController',
            'action' => 'cadastraPais'
        );
        
        $routes['editar_pais_admin'] = array(
            'route' => '/admin/editar_pais',
            'controller' => 'AdminController',
            'action' => 'editarPais'
        );

        $routes['edita_pais_admin'] = array(
            'route' => '/admin/edita_pais',
            'controller' => 'AdminController',
            'action' => 'editaPais'
        );

        $this->setRoutes($routes);
    }
}
