<?php 
    session_start();
    
    require_once '../includes/autoloader.php';
    
    define("TITLE", "Excluir - Editora");
    define("ACTIVE", 4);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "index.php");
    define("DIR_Idiomas", "../idiomas/");

    $db = new Database();
    
    if(!isset($_GET['cod']) || !is_numeric($_GET['cod'])) {
        header("Location: index.php");
        exit;
    }

    $Editora = Editora::SELECT_Editoras($db, "WHERE Cod = {$_GET['cod']}")[0];

    if(!$Editora instanceof Editora) {
        header("Location: index.php");
        exit;
    }

    require '../includes/header.php';

    define("confirmDelete", "<p>Você deseja realmente excluir a editora <strong>{$Editora->getNome()}</strong>?</p>");
    include_once '../includes/formDelete.php';
?>
<?php if(isset($_POST['excluir'])) {
        $Editora->DELETE_Editora($db);
        $_SESSION['mensagem'] = '<div class="alert alert-danger">Excluído com sucesso</div>';
            
        header("location: index.php");
    } 
?></div></body>