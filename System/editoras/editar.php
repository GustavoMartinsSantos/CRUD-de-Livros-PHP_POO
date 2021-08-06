<?php // editar
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';
    
    define("TITLE", "Editar - Editora");
    define("ACTIVE", 4);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "index.php");
    define("DIR_Idiomas", "../idiomas/");
    
    require '../includes/header.php';

    if(!isset($_GET['cod']) || !is_numeric($_GET['cod'])) {
        header("Location: index.php");
        exit;
    }
    
    $db = new Database();
    $Editora = Editora::SELECT_Editoras($db, "WHERE Cod = {$_GET['cod']}")[0];

    if(!$Editora instanceof Editora) {
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
            <input type="text" name="Nome" class="form-control" placeholder=" " define maxlength="50" autofocus required 
            value="<?= $Editora->getNome() ?>">
            <label class="form-label" for="Nome">Nome da editora</label>
        </div>

        <div class="input-group mt-3">
            <label class="input-group-text">Livros</label>
            <select name="Livros[]" class="form-control" multiple <?= $Livros == false ? "" : "required"?>>
            <?php foreach($Livros as $Livro) { ?>
                <option value="<?= $Livro->getID() ?>"
                <?= $Livro->getEditora()->getCod() == $Editora->getCod() ? "selected" : null ?>>
                    <?= $Livro->getNome() ?>
                    </option>
                <?php } ?>
                </select>
            </div>

            <div class="text-center">
                <input type="submit" class="btn bg-dark mt-3 w-50 text-light" value="Editar">
            </div>
        </form>
    </div>
</body>
<?php
    if(isset($_POST['Nome'])) {
        $nome = filter_input(INPUT_POST, 'Nome', FILTER_SANITIZE_STRING);
        $nome = trim(preg_replace('/\s+/', " ", $nome));
        $mensagem = "Digite um nome válido!";
        $WHERE = "WHERE Nome = '{$nome}' AND Nome <> '{$Editora->getNome()}'";

        if(count(Editora::SELECT_Editoras($db, $WHERE)) > 0) {
            $mensagem = "Editora já cadastrada";
            $nome = null;
        }

        if($nome == null) {
            $alerta = "<div class='alert alert-danger'>{$mensagem}</div>";
            $_SESSION['mensagem'] = $alerta;
            header("Location: editar.php?cod={$Editora->getCod()}");
            exit;
        }

        if(isset($_POST['Livros'])) {
            foreach($_POST['Livros'] as $ID_Livro) {
                $Livro = new Livro();
                $Livro->setID($ID_Livro);
                $Livro->setEditora($Editora);

                $Livro->UPDATE_Editora($db);
            }
        }

        $Editora->setNome($nome);
        $Editora->UPDATE_Editora($db);

        $_SESSION['mensagem'] = '<div class="alert alert-success">Editado com sucesso</div>';
        header("location: index.php");
    }
?>