<?php
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';
    
    define("TITLE", "Cadastrar - Autor");
    define("ACTIVE", 3);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "index.php");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "../idiomas/");
    
    require '../includes/header.php';

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }
    
    $db = new Database();
    $Livros = Livro::SELECT_Livros($db);

    define("FieldName", "Nome completo do autor");
    define("requiredBook", $Livros == false ? "" : "required");
    define("maxLength", 80);
    include_once '../includes/form.php';

    if(isset($_POST['Nome'])) {
        $nome = filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_STRING);
        $nome = trim(preg_replace('/\s+/', " ", $nome));
        $mensagem = "Digite um nome válido!";
        $nome = explode(" ", $nome, 2);
        
        if($nome[0] == NULL)
            $nome = NULL;

        $Autor = new Autor();
        
        $WHERE = "WHERE Nome = '{$nome[0]}' AND Sobrenome ";

        if(isset($nome[1])) {
            $Autor->setSobrenome($nome[1]);
            $WHERE .= " = '{$nome[1]}'";
        } else {
            $Autor->setSobrenome("");
            $WHERE .= "IS NULL";
        }

        if(count(Autor::SELECT_Autores($db, $WHERE)) > 0) {
            $mensagem = "Autor já cadastrado";
            $nome = null;
        }

        if($nome == null) {
            $alerta = "<div class='alert alert-danger'>{$mensagem}</div>";
            $_SESSION['mensagem'] = $alerta;
            header('Location: cadastrar.php');
            exit;
        }

        $Autor->setNome($nome[0]);
        $Autor->INSERT_Autor($db);
        
        if(isset($_POST['Livros'])) {
            foreach($_POST['Livros'] as $ID_Livro) {
                $Livro = new Livro();
                $Livro->setID($ID_Livro);

                $Escrita = new Escrita($Autor, $Livro);
                $Escrita->INSERT_Escrita($db);
            }
        }

        $_SESSION['mensagem'] = '<div class="alert alert-success">Cadastro efetuado com sucesso</div>';
        header("location: index.php");
    }
?>