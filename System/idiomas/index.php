<?php
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';

    define("TITLE", "Idiomas");
    define("ACTIVE", 5);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "../categorias/");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "");
    $db = new Database();
    $paginaAtual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? $_GET['pagina'] : 1;
    
    require '../includes/header.php';

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }
    
    $COUNT_Results = Idioma::SELECT_COUNT($db);
    $Objects = Idioma::SELECT_Idiomas($db);
    $paginacao = new Paginacao($COUNT_Results, 5, $paginaAtual);

    $linksPaginas = $paginacao->getLinkPages();

    $Idiomas = $paginacao->getResultsByPage($Objects);
?>
<div class="bg-dark p-3" style="font-size: 13pt">
        <?php if($Idiomas == false) { ?>
                <div class="text-center text-light">Nenhum idioma cadastrado - <a href="cadastrar.php">Cadastrar</a></div>
        <?php die();
            } echo $linksPaginas; ?>
        
    <table class="table text-light align-middle">
        <thead class="border">
            <tr>
                <th class="col px-4">ID</th>
                <th class="col">Idioma</th>
                <th class="col-4">Ações</th>
            </tr>
        </thead>
        <tbody  class="border">
            <?php foreach($Idiomas as $Idioma) { ?>
                <tr>
                    <td class="px-5"><?php echo $Idioma->getID() ?></td>
                    <td><?php echo $Idioma->getNome() ?></td>
                    
                    <td>
                        <a class="px-3 text-info" href="../?By=idioma&busca=<?php echo $Idioma->getNome() ?>">Pesquisar Livros</a>
                        <a href="editar.php?id=<?php echo $Idioma->getID() ?>">
                            <button type="button" class="btn btn-primary">Editar</button>
                        </a>
                        <a href="excluir.php?id=<?php echo $Idioma->getID() ?>">
                            <button type="button" class="btn btn-danger">Excluir</button>
                        </a>
                    </td>
                <tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="text-center">
        <a href="cadastrar.php">
            <button type="button" class="btn bg-success text-light mt-3 w-50">Adicionar</button>
        </a>
    </div>
</div></div></body>