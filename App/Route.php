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

        

        $this->setRoutes($routes);
    }   
}