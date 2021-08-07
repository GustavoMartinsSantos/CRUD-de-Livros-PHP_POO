<?php
    ob_start();
    session_start();

    require_once '../includes/autoloader.php';

    define("TITLE", "Categorias");
    define("ACTIVE", 2);
    define("DIR_Principal", "../");
    define("DIR_CRLivro", "../livros/cadastrar.php");
    define("DIR_Categorias", "");
    define("DIR_Autores", "../autores/");
    define("DIR_Editoras", "../editoras/");
    define("DIR_Idiomas", "../idiomas/");
    $db = new Database();
    $paginaAtual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? $_GET['pagina'] : 1;
    
    require '../includes/header.php';

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }

    $COUNT_Results = Categoria::SELECT_COUNT($db);
    $Objects = Categoria::SELECT_Categorias($db);
    $paginacao = new Paginacao($COUNT_Results, 5, $paginaAtual);

    $linksPaginas = $paginacao->getLinkPages();
    
    $Categorias = $paginacao->getResultsByPage($Objects);
?>
<div class="bg-dark p-3" style="font-size: 13pt">
    <?php if($Categorias == false) { ?>
        <div class="text-center text-light">Nenhuma categoria encontrada - <a href="cadastrar.php">Cadastrar</a></div>
    <?php die();
        } echo $linksPaginas; ?>
    
    <table class="table text-light align-middle">
        <thead class="border">
            <tr>
                <th class="col px-4">Código</th>
                <th class="col">Categoria</th>
                <th class="col-4">Ações</th>
            </tr>
        </thead>
        <tbody class="border">
            <?php foreach($Categorias as $Categoria) { ?>
                <tr>
                    <td class="px-5"><?php echo $Categoria->getCod() ?></td>
                    <td><?php echo $Categoria->getNome() ?></td>
                    
                    <td>
                        <a class="px-3 text-info" href="../?By=categoria&busca=<?php echo $Categoria->getNome() ?>">Pesquisar Livros</a>
                        <a href="editar.php?cod=<?php echo $Categoria->getCod() ?>">
                            <button type="button" class="btn btn-primary">Editar</button>
                        </a>
                        <a href="excluir.php?cod=<?php echo $Categoria->getCod() ?>">
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