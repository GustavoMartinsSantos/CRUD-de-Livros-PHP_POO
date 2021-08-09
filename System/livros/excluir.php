<?php 
    session_start();
    
    require_once '../includes/autoloader.php';
    
    define("TITLE", "Excluir");
    define("ACTIVE", 0);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "../idiomas/");

    $db = new Database();
    
    if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: ../");
        exit;
    }

    $Livro = Livro::SELECT_Livros($db, "WHERE L.ID = {$_GET['id']}")[0];

    if(!$Livro instanceof livro) {
        header("Location: ../");
        exit;
    }

    require '../includes/header.php';

    define("confirmDelete", "<p>Você deseja realmente excluir o livro <strong>{$Livro->getNome()}</strong>?</p>");
    include_once '../includes/formDelete.php';
?>
<?php if(isset($_POST['excluir'])) {
        $Livro->DELETE_Livro($db);
        $_SESSION['mensagem'] = '<div class="alert alert-danger">Excluído com sucesso</div>';
        header("location: ../");
    } 
?></div></body>