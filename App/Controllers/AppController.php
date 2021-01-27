<?php

namespace App\Controllers;

//Recursos framework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function carrinho() {

        session_start();

        //Inicio buscar produtos
        $produto = Container::getModel('Produto');
        $categoria = Container::getModel('Categoria');
        $subcategoria = Container::getModel('Subcategoria');

        //Buscar categorias
        $this->view->categorias = $categoria->getAll();       
        //Buscar subcategorias
        $this->view->subcategorias = $subcategoria->getAll();

        $this->render('carrinho');
    }
}