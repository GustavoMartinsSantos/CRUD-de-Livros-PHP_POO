<?php 
    session_start();
    
    require_once '../includes/autoloader.php';
    
    define("TITLE", "Excluir - Autor");
    define("ACTIVE", 3);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "index.php");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "../idiomas/");

    $db = new Database();
    
    if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: index.php");
        exit;
    }

    $Autor = Autor::SELECT_Autores($db, "WHERE ID = {$_GET['id']}")[0];

    if(!$Autor instanceof Autor) {
        header("Location: index.php");
        exit;
    }

    require '../includes/header.php';

    $RESTRICT = false;
    foreach(Livro::SELECT_Livros($db) as $Livro) {
        foreach($Livro->getEscritas() as $Escrita){
            if($Autor->getID() == $Escrita->getAutor()->getID()) {
                $RESTRICT = true;
                break;
            }

            if($RESTRICT == true)
                break;
        }
    }

    define("confirmDelete", "<p>Você deseja realmente excluir o autor <strong>{$Autor->getNome()} {$Autor->getSobrenome()}</strong>?</p>");
    include_once '../includes/formDelete.php';
?>
<?php if(isset($_POST['excluir'])) {
        if($RESTRICT)
            $_SESSION['mensagem'] = '<div class="alert alert-danger">' .
            'Não é possível deletar este autor pois está associado a um ou mais livros</div>';
        else {
            $Autor->DELETE_Autor($db);
            $_SESSION['mensagem'] = '<div class="alert alert-danger">Excluído com sucesso</div>';
        }
            
        header("location: index.php");
    } 
?></div></body>