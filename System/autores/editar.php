<?php
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';
    
    define("TITLE", "Editar - Autor");
    define("ACTIVE", 3);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "index.php");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "../idiomas/");
    
    require '../includes/header.php';

    if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: index.php");
        exit;
    }
    
    $db = new Database();
    $Autor = Autor::SELECT_Autores($db, "WHERE ID = {$_GET['id']}")[0];

    if(!$Autor instanceof Autor) {
        header("Location: index.php");
        exit;
    }

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }

    if($Autor->getSobrenome() == NULL)
        $LastName = "NULL";
    else
        $LastName = "'{$Autor->getSobrenome()}'";

    $Livros = Livro::SELECT_Livros($db);
?>
    <form method="POST" class="form-group">
        <div class="form-floating">
            <input type="text" name="Nome" class="form-control" placeholder=" " define maxlength="80" autofocus required 
            value="<?= "{$Autor->getNome()} {$Autor->getSobrenome()}" ?>">
            <label class="form-label" for="Nome">Nome completo do autor</label>
        </div>

        <div class="input-group mt-3">
            <label class="input-group-text">Escrituras</label>
            <select name="Livros[]" class="form-control" multiple <?= $Livros == false ? "" : "required"?>>
            <?php foreach($Livros as $Livro) { ?>
                <option value="<?= $Livro->getID() ?>"
                <?= $Livro->SELECTED_Autor($Autor->getID()) ? "selected" : null ?>>
                    <?= $Livro->getNome() ?>
                </option>
            <?php } ?>
            </select>
        </div>

        <div class="text-center">
            <input type="submit" class="btn bg-dark mt-3 w-50 text-light" value="Editar">
        </div>
    </form></div></body>
<?php
    if(isset($_POST['Nome'])) {
        $nome = filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_STRING);
        $nome = trim(preg_replace('/\s+/', " ", $nome));
        $mensagem = "Digite um nome válido!";
        $nome = explode(" ", $nome, 2);
        
        if($nome[0] == NULL)
            $nome = NULL;
        
        $WHERE = "WHERE Nome = '{$nome[0]}' AND Sobrenome ";

        if(isset($nome[1])) {
            $Autor->setSobrenome($nome[1]);
            $WHERE .= "= '{$nome[1]}' ";
        } else {
            $Autor->setSobrenome("");
            $WHERE .= "IS NULL ";
        }

        $WHERE .= "AND CONCAT(Nome, COALESCE(Sobrenome, ' ')) <> CONCAT('{$Autor->getNome()}', COALESCE({$LastName}, ' '))";
        // esta clausula existe para que a procura de autores já cadastrados 
        // sejam feito com base em dados diferentes do autor editado
        
        if(count(Autor::SELECT_Autores($db, $WHERE)) > 0) {
            $mensagem = "Autor já cadastrado";
            $nome = null;
        }

        if($nome == null) {
            $alerta = "<div class='alert alert-danger'>{$mensagem}</div>";
            $_SESSION['mensagem'] = $alerta;
            header("Location: editar.php?id={$Autor->getID()}");
            exit;
        }

        $Autor->setNome($nome[0]);
        $Autor->UPDATE_Autor($db);
        
        if(isset($_POST['Livros'])) {
            foreach($_POST['Livros'] as $ID_Livro) {
                $Livro = new Livro();
                $Livro->setID($ID_Livro);

                $Escrita = new Escrita($Autor, $Livro);
                $Escrita->INSERT_Escrita($db);
            }
        }

        $_SESSION['mensagem'] = '<div class="alert alert-success">Editado com sucesso</div>';
        header("location: index.php");
    }
?>