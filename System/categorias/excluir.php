<?php 
    session_start();
    
    require_once '../includes/autoloader.php';
    
    define("TITLE", "Excluir - Categoria");
    define("ACTIVE", 2);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "index.php");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "../idiomas/");
    
    if(!isset($_GET['cod']) || !is_numeric($_GET['cod'])) {
        header("Location: index.php");
        exit;
    }

    $db = new Database();
    $Categoria = Categoria::SELECT_Categorias($db, "WHERE Cod = {$_GET['cod']}")[0];

    if(!$Categoria instanceof Categoria) {
        header("Location: index.php");
        exit;
    }

    require '../includes/header.php';

    $RESTRICT = false;
    foreach(Livro::SELECT_Livros($db) as $Livro) {
        foreach($Livro->getCategorizacoes() as $Categorizacao){
            if($Categoria->getCod() == $Categorizacao->getCategoria()->getCod()) {
                $RESTRICT = true;
                break;
            }

            if($RESTRICT == true)
                break;
        }
    }

    define("confirmDelete", "<p>Você deseja realmente excluir a categoria <strong>{$Categoria->getNome()}</strong>?</p>");
    include_once '../includes/formDelete.php';
?>
<?php if(isset($_POST['excluir'])) {
        if($RESTRICT)
            $_SESSION['mensagem'] = '<div class="alert alert-danger">' .
            'Não é possível deletar esta categoria pois está associada a um ou mais livros</div>';
        else {
            $Categoria->DELETE_Categoria($db);
            $_SESSION['mensagem'] = '<div class="alert alert-danger">Excluído com sucesso</div>';
        }
            
        header("location: index.php");
    } 
?></div></body>