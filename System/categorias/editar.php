<?php // editar
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';

    define("TITLE", "Editar - Categoria");
    define("ACTIVE", 2);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "index.php");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "../idiomas/");
    
    require '../includes/header.php';

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

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }

    $Livros = Livro::SELECT_Livros($db);
?>
    <form method="POST" class="form-group">
        <div class="form-floating">
            <input type="text" name="Nome" class="form-control" placeholder=" " define maxlength="30" autofocus required 
            value="<?= $Categoria->getNome() ?>">
            <label class="form-label" for="Nome">Nome da categoria</label>
        </div>

        <div class="input-group mt-3">
            <label class="input-group-text">Livros</label>
            <select name="Livros[]" class="form-control" multiple <?= $Livros == false ? "" : "required"?>>
            <?php foreach($Livros as $Livro) { ?>
                <option value="<?= $Livro->getID() ?>"
                <?= $Livro->SELECTED_Categoria($Categoria->getCod()) ? "selected" : null ?>>
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
        $WHERE = "WHERE Nome = '{$nome}' AND Nome <> '{$Categoria->getNome()}'";

        if(count(Categoria::SELECT_Categorias($db, $WHERE)) > 0) {
            $mensagem = "Categoria já cadastrada";
            $nome = null;
        }

        if($nome == null) {
            $alerta = "<div class='alert alert-danger'>{$mensagem}</div>";
            $_SESSION['mensagem'] = $alerta;
            header("Location: editar.php?cod={$Categoria->getCod()}");
            exit;
        }

        $Categoria->setNome($nome);
        
        if(isset($_POST['Livros'])) {
            foreach($_POST['Livros'] as $ID_Livro) {
                $Livro = new Livro();
                $Livro->setID($ID_Livro);

                $Categorizacao = new Categoriza($Categoria, $Livro);
                $Categoria->addCategorizacao($Categorizacao);
            }
        }

        $Categoria->UPDATE_Categoria($db);

        $_SESSION['mensagem'] = '<div class="alert alert-success">Editado com sucesso</div>';
        header("location: index.php");
    }
?>