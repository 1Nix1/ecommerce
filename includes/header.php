<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">

    <title>Pay to on</title>
  </head>
  <body>
    <div class="container">
        <!-- Topo do site -->
        <header class="pt-2 topo">
            <!-- Logo -->
            <h1 class="mb-5 w-25 d-inline m-2"><a href="index.php"><img class="img-noticia" src="img/logo2.jpg"></a></h1>
            <!-- fim logo -->
            <?php 
                if(isset($_SESSION['usuario'])) {
            ?>
            <input type="submit" class="btn btn-primary float-right mr-2" onclick="location.href='operacoes/logout.php'" value="Sair">
            <input type="submit" class="btn btn-primary float-right mr-2" onclick="location.href='carrinho.php'" value="Carrinho de compras">
            <?php 
                } else {
            ?>
            <input type="submit" class="btn btn-primary float-right mr-2" onclick="location.href='logar.php'" value="Login">
            <input type="submit" class="btn btn-primary float-right mr-2" onclick="location.href='novo_registro_usuario.php'" value="Cadastrar">
            <?php 
                }
            ?>
            <!-- Barra de navegação -->
            <nav class="navbar navbar-expand-sm navbar-light bg-light navegacao-topo">
                <div class="navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Todas as categorias</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            Camisetas
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?categoria=1">Camisetas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?categoria=6">
                        Livros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?categoria=9" tabindex="-1">Decoração</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?categoria=10" tabindex="-1">Games</a>
                    </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0" id="form-pesquisa" method="POST" action="index.php">
                        <input name="search" id="search" class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
            </nav>
            <!-- fim da barra de navegação -->
        </header>
        <!-- fim do topo -->
    

    