<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

    protected function initRoutes() {

        $routes['home'] = array(
            'route' => '/',
            'controller' => 'indexController',
            'action' => 'index'
        );

        $routes['inscreverse'] = array(
            'route' => '/inscreverse',
            'controller' => 'indexController',
            'action' => 'inscreverse'
        );

        $routes['registrar'] = array(
            'route' => '/registrar',
            'controller' => 'indexController',
            'action' => 'registrar'
        );

        $routes['login'] = array(
            'route' => '/login',
            'controller' => 'IndexController',
            'action' => 'login'
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

        $routes['categoria'] = array(
            'route' => '/categoria',
            'controller' => 'IndexController',
            'action' => 'filterCategoria'
        );

        $routes['subcategoria'] = array(
            'route' => '/subcategoria',
            'controller' => 'IndexController',
            'action' => 'filterSubcategoria'
        );

        $routes['search'] = array(
            'route' => '/search',
            'controller' => 'IndexController',
            'action' => 'search'
        );

        $this->setRoutes($routes);
    }   
}