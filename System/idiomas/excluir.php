<?php 
    session_start();
    
    require_once '../includes/autoloader.php';
    
    define("TITLE", "Excluir");
    define("ACTIVE", 5);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "index.php");

    $db = new Database();
    
    if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: index.php");
        exit;
    }

    $Idioma = Idioma::SELECT_Idiomas($db, "WHERE ID = {$_GET['id']}")[0];

    if(!$Idioma instanceof Idioma) {
        header("Location: index.php");
        exit;
    }

    $RESTRICT = false;
    foreach(Livro::SELECT_Livros($db) as $Livro) {
        if($Livro->getIdioma()->getID() == $Idioma->getID()) {
            $RESTRICT = true;
            break;
        }
    }

    require '../includes/header.php';

    define("confirmDelete", "<p>Você deseja realmente excluir o idioma <strong>{$Idioma->getNome()}</strong>?</p>");
    include_once '../includes/formDelete.php';
?>
<?php if(isset($_POST['excluir'])) {
        if($RESTRICT)
            $_SESSION['mensagem'] = '<div class="alert alert-danger">' .
            'Não é possível deletar este idioma porque ele está relacionado a um ou mais livros</div>';
        else {
            $Idioma->DELETE_Idioma($db);
            $_SESSION['mensagem'] = '<div class="alert alert-danger">Excluído com sucesso</div>';
        }
            
        header("location: index.php");
    } 
?></div></body>