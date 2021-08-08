<?php // editar
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';

    define("TITLE", "Editar - Idioma");
    define("ACTIVE", 5);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "index.php");
    
    require '../includes/header.php';

    if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: ../");
        exit;
    }
    
    $db = new Database();
    $Idioma = Idioma::SELECT_Idiomas($db, "WHERE ID = {$_GET['id']}")[0];

    if(!$Idioma instanceof Idioma) {
        header("Location: index.php");
        exit;
    }

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }

    $Livros = Livro::SELECT_Livros($db);
?>
    <form method="POST" class="form-group">
        <div class="form-floating">
            <input type="text" name="Nome" class="form-control" placeholder=" " maxlength="50" autofocus required 
            value="<?= $Idioma->getNome() ?>">
            <label class="form-label" for="Nome">Nome da editora</label>
        </div>

        <div class="input-group mt-3">
            <label class="input-group-text">Livros</label>
            <select name="Livros[]" class="form-control" multiple>
            <?php foreach($Livros as $Livro) { ?>
                <option value="<?= $Livro->getID() ?>"
                <?= $Livro->getIdioma()->getID() == $Idioma->getID() ? "selected" : null ?>>
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
        $WHERE = "WHERE Nome = '{$nome}' AND Nome <> '{$Idioma->getNome()}'";

        if(count(Idioma::SELECT_Idiomas($db, $WHERE)) > 0) {
            $mensagem = "Idioma já cadastrado";
            $nome = null;
        }

        if($nome == null) {
            $alerta = "<div class='alert alert-danger'>{$mensagem}</div>";
            $_SESSION['mensagem'] = $alerta;
            header("Location: editar.php?id={$Idioma->getID()}");
            exit;
        }

        $Idioma->setNome($nome);
        
        if(isset($_POST['Livros'])) {
            foreach($_POST['Livros'] as $ID_Livro) {
                $Livro = new Livro();
                $Livro->setID($ID_Livro);
                $Livro->setIdioma($Idioma);

                $Livro->UPDATE_Idioma($db);
            }
        }

        $Idioma->UPDATE_Idioma($db);

        $_SESSION['mensagem'] = '<div class="alert alert-success">Editado com sucesso</div>';
        header("location: index.php");
    }
?>