<?php
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';

    define("TITLE", "Editar");
    define("ACTIVE", 0);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "../idiomas/");

    $db = new Database();

    require '../includes/header.php';

    if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: ../");
        exit;
    }

    $Livro = Livro::SELECT_Livros($db, "WHERE L.ID = {$_GET['id']}")[0];
    
    if(!$Livro instanceof livro) {
        header("Location: ../");
        exit;
    }

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }

    require '../includes/formLivroUpdate.php';

    if(isset($_POST['Nome'])) {
        $nome = filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_STRING);
        $nome = trim(preg_replace('/\s+/', " ", $nome));
        $descricao = filter_input(INPUT_POST, 'Sinopse', FILTER_SANITIZE_STRING);

        if($nome == null) {
            $mensagem = '<div class="alert alert-danger">Digite um nome válido!</div>';
            $_SESSION['mensagem'] = $mensagem;
            header("Location: editar.php?id={$Livro->getID()}");
            exit;
        }
        
        if($_FILES['arquivo']['error'] != 0 && $_FILES['arquivo']['error'] != 4) {
            $mensagem = '<div class="alert alert-danger">Erro no arquivo enviado</div>';
            $_SESSION['mensagem'] = $mensagem;
            header("Location: editar.php?id={$Livro->getID()}");
            exit;
        }
        
        $Editora = new Editora();
        $Editora->setCod($_POST['Editora']);

        $Idioma = new Idioma();
        $Idioma->setID($_POST['Idioma']);

        $Livro = new Livro();
        $Livro->setID($_POST['ID']);
        $Livro->setNome($nome);
        $Livro->setPreco($_POST['Preco']);
        $Livro->setData_pub($_POST['Data_Pub']);
        $Livro->setNum_paginas($_POST['Num_paginas']);
        $Livro->setSinopse($descricao);
        $Livro->setEditora($Editora);
        $Livro->setIdioma($Idioma);

        $file = $_FILES['arquivo'];
        $Arquivo = new Arquivo();
        $Arquivo->setID($_POST['ID_Img']);
        $Arquivo->setError($file['error']);

        if($Arquivo->getError() != 4) {
            $extension = pathinfo($file['name'])['extension'];
            //$basename = pathinfo($file['name'])['basename'];
            $Arquivo->setNome(md5(time()));
            $Arquivo->setExtension($extension);
            $Arquivo->setTamanho($file['size']);
            $Arquivo->setTmpName($file['tmp_name']);
        }

        $Livro->setArquivo($Arquivo);
        
        foreach($_POST['Autores'] as $ID_Autor) {
            $Autor = new Autor();
            $Autor->setID($ID_Autor);

            $Escrita = new Escrita($Autor, $Livro);

            $Livro->addEscrita($Escrita);
        }
        
        foreach($_POST['Categorias'] as $Cod_Categoria) {
            $Categoria = new Categoria();
            $Categoria->setCod($Cod_Categoria);

            $Categoriza = new Categoriza($Categoria, $Livro);

            $Livro->addCategorizacao($Categoriza);
        }
        
        $Livro->UPDATE_Livro($db);
        $_SESSION['mensagem'] = '<div class="alert alert-success">Editado com sucesso</div>';
        header("location: ../");
    }
?>