<?php
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';

    define("TITLE", "Cadastrar - Idioma");
    define("ACTIVE", 5);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "index.php");
    
    require '../includes/header.php';

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }
    
    $db = new Database();
    $Livros = Livro::SELECT_Livros($db);

    define("FieldName", "Nome do idioma");
    define("requiredBook", NULL);
    define("maxLength", 50);
    include_once '../includes/form.php';

    if(isset($_POST['Nome'])) {
        $nome = filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_STRING);
        $nome = trim(preg_replace('/\s+/', " ", $nome));
        $mensagem = "Digite um nome válido!";

        if(count(Idioma::SELECT_Idiomas($db, "WHERE Nome = '{$nome}'")) > 0) {
            $mensagem = "Idioma já cadastrado";
            $nome = null;
        }
            
        if($nome == null) {
            $alerta = "<div class='alert alert-danger'>{$mensagem}</div>";
            $_SESSION['mensagem'] = $alerta;
            header('Location: cadastrar.php');
            exit;
        }

        $Idioma = new Idioma();
        $Idioma->setNome($nome);

        $Idioma->INSERT_Idioma($db);
        
        if(isset($_POST['Livros'])) {
            foreach($_POST['Livros'] as $ID_Livro) {
                $Livro = new Livro();
                $Livro->setID($ID_Livro);
                $Livro->setIdioma($Idioma);

                $Livro->UPDATE_Idioma($db);
            }
        }

        $_SESSION['mensagem'] = '<div class="alert alert-success">Cadastro efetuado com sucesso</div>';
        header("location: index.php");
    }
?>