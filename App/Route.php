<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

    protected function initRoutes() {

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

        

        $this->setRoutes($routes);
    }   
}