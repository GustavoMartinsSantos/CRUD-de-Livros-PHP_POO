<?php
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';
    
    define("TITLE", "Cadastrar - Categoria");
    define("ACTIVE", 2);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "index.php");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "../idiomas/");
    
    require '../includes/header.php';

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }
    
    $db = new Database();
    $Livros = Livro::SELECT_Livros($db);

    define("FieldName", "Nome da categoria");
    define("requiredBook", $Livros == false ? "" : "required");
    define("maxLength", 30);
    include_once '../includes/form.php';

    if(isset($_POST['Nome'])) {
        $nome = filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_STRING);
        $nome = trim(preg_replace('/\s+/', " ", $nome));
        $mensagem = "Digite um nome válido!";

        if(count(Categoria::SELECT_Categorias($db, "WHERE Nome = '{$nome}'")) > 0) {
            $mensagem = "Categoria já cadastrada";
            $nome = null;
        }
            
        if($nome == null) {
            $alerta = "<div class='alert alert-danger'>{$mensagem}</div>";
            $_SESSION['mensagem'] = $alerta;
            header('Location: cadastrar.php');
            exit;
        }

        $Categoria = new Categoria();
        $Categoria->setNome($nome);

        $Categoria->INSERT_Categoria($db);

        if(isset($_POST['Livros'])) {
            foreach($_POST['Livros'] as $ID_Livro) {
                $Livro = new Livro();
                $Livro->setID($ID_Livro);

                $Categoriza = new Categoriza($Categoria, $Livro);
                $Categoriza->INSERT_Categoriza($db);
            }
        }

        $_SESSION['mensagem'] = '<div class="alert alert-success">Cadastro efetuado com sucesso</div>';
        header("location: index.php");
    }
?>