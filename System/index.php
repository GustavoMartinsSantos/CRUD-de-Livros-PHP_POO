<?php
    session_start();

    require_once 'includes/autoloader.php';
    
    define("TITLE", "Livros");
    define("ACTIVE", 0);
    define("DIR_Principal", "");
    define("DIR_CRLivro", "livros/cadastrar.php");
    define("DIR_Categorias", "categorias/");
    define("DIR_Autores", "autores/");
    define("DIR_Editoras", "editoras/");
    define("DIR_Idiomas", "idiomas/");
    $db = new Database();

    require 'includes/header.php';

    if(isset($_SESSION['mensagem'])) {
        echo $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
    }

    $search = filter_input(INPUT_GET, 'busca', FILTER_SANITIZE_STRING);
    $searchFilter = filter_input(INPUT_GET, 'By', FILTER_SANITIZE_STRING);
    $searchFilter = in_array($searchFilter, ['editora', 'autor', 'categoria', 'idioma']) ? $searchFilter : 'livro';
    $paginaAtual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? $_GET['pagina'] : 1;
    $LimitByBook = isset($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] >= 1 ? $_GET['limit'] : 5;

    include "includes/autocomplete.php";
    $autocomplete = autocomplete($searchFilter);

    $filtros = [
         "L.Nome LIKE '%".str_replace(' ', '%', $search)."%'"
        ,"CONCAT(A.Nome, Sobrenome) LIKE '%".str_replace(' ', '%', $search)."%'"
        ,"C.Nome LIKE '%".str_replace(' ', '%', $search)."%'"
        ,"I.Nome LIKE '%".str_replace(' ', '%', $search)."%'"
        ,"E.Nome LIKE '%".str_replace(' ', '%', $search)."%'"
    ];

    switch($searchFilter) {
        case 'editora':
            $filtros = [$filtros[4]];break;
        case 'autor':
            $filtros = [$filtros[1]];break;
        case 'categoria':
            $filtros = [$filtros[2]];break;
        case 'idioma':
            $filtros = [$filtros[3]];break;
    }

    $filtros = "WHERE " . implode(' OR ', $filtros);

    $COUNT_Results = Livro::SELECT_COUNT($db, $filtros);
    $Objects = Livro::SELECT_Livros($db, $filtros);
    $paginacao = new Paginacao($COUNT_Results, $LimitByBook, $paginaAtual);

    $linksPaginas = $paginacao->getLinkPages();

    $Livros = $paginacao->getResultsByPage($Objects);
?>
    <form method="GET" class="form-group mb-4">
        <div class="input-group">
            <label class="input-group-text">Pesquisar por
                <select name="By" class="mx-2 form-control bg-dark text-light">
                    <option value="livro">Livro
                    <option value="editora" <?= $searchFilter == 'editora' ? "selected" : '' ?>>Editora
                    <option value="autor" <?= $searchFilter == 'autor' ? "selected" : '' ?>>Autor
                    <option value="categoria" <?= $searchFilter == 'categoria' ? "selected" : '' ?>>Categoria
                    <option value="idioma" <?= $searchFilter == 'idioma' ? "selected" : '' ?>>Idioma
                </select>
            </label>
            <label class="input-group-text" style="width: 225px">Livros por página
                <input type="number" name="limit" value="<?=$LimitByBook?>" class="mx-2 form-control" min="1">
            </label>

            <input name="busca" id="searchField" placeholder="Buscar" 
            type="text" class="form-control" value="<?=$search?>">
            <button class="btn bg-dark text-light" type="submit"><i class="bi bi-search"></i></button>
        </div>
    </form>

<?php if($Livros == false) { ?>
    <div class="bg-dark text-light p-3 text-center" style="font-size: 13pt">Nenhum livro encontrado</div>
<?php } 

    echo $linksPaginas;

    foreach($Livros as $Livro) { ?>
        <div class="border bg-dark text-light p-3">
            <div class="row">
                <div class="col-md-auto">
                    <img class="rounded border border-warning" src="IMG/<?= $Livro->getArquivo()->getNome() .
                    $Livro->getArquivo()->getExtension()?>" width="182px" height="250px">
                </div>

                <div class="col">
                    <strong><h2><?= $Livro->getNome() ?></h2></strong><br>
                    Editora: <?= $Livro->getEditora()->getNome() ?><br>
                        
                    Data de Publicação: 
                    <?php if($Livro->getData_pub() != NULL) {
                        $data = new Datetime($Livro->getData_pub());
                        echo $data->format('d/m/Y');
                    } ?><br>

                    Preço: <?= "R$ " . number_format($Livro->getPreco(), 2, ',') ?><br>
                    Idioma: <?= $Livro->getIdioma()->getNome() ?><br>
                    Número de Páginas: <?= $Livro->getNum_paginas() ?><br>
                        
                    Categorias: <?php $Categorias = $Livro->getCategorizacoes();
                    for($x = 0; $x < count($Categorias); $x++) {
                        if((count($Categorias) - $x) > 2)
                            echo $Categorias[$x]->getCategoria()->getNome() . ", ";
                        else if((count($Categorias) - $x) > 1)
                            echo $Categorias[$x]->getCategoria()->getNome() . " e ";
                        else
                            echo $Categorias[$x]->getCategoria()->getNome();
                    } ?><br>
                        
                    Autores: <?php $Escritas = $Livro->getEscritas();
                    for($x = 0; $x < count($Escritas); $x++) {
                        if((count($Escritas) - $x) > 2)
                            echo $Escritas[$x]->getAutor()->getNome() . " " .
                            $Escritas[$x]->getAutor()->getSobrenome() . ", ";
                        else if((count($Escritas) - $x) > 1)
                            echo $Escritas[$x]->getAutor()->getNome() . " " .
                            $Escritas[$x]->getAutor()->getSobrenome() . " e ";
                        else
                            echo $Escritas[$x]->getAutor()->getNome() . " " .
                            $Escritas[$x]->getAutor()->getSobrenome();
                        } ?>
                </div>

                <div class="col-2">
                    <a href="livros/editar.php?id=<?= $Livro->getID() ?>">
                        <button type="button" class="btn btn-primary">Editar</button>
                    </a>
                    <a href="livros/excluir.php?id=<?= $Livro->getID() ?>">
                        <button type="button" class="btn btn-danger">Excluir</button>
                    </a>
                </div>

                <p class="p-3 text-justify">Sinopse: <?= $Livro->AddReadMoreSinopse() ?></p>
            </div>
        </div>
    <?php } ?></div>
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    function ReadMore (id) {
        var moreText = document.querySelector(".more-text"+id);
        var pontos   = document.querySelector(".pontos"+id);
        var LinkReadMore = document.querySelector(".read-more"+id);
        var LinkReadLess = document.querySelector(".read-less"+id);
                   
        if(pontos.style.display == "none") {
            pontos.style.display = "inline";
            moreText.style.display = "none";
            LinkReadMore.style.display = "inline";
            LinkReadLess.style.display = "none";
        } else {
            pontos.style.display = "none";
            moreText.style.display = "inline";
            LinkReadLess.style.display = "inline";
            LinkReadMore.style.display = "none";
        }
    }

    var autocomplete = <?= $autocomplete ?>;

    $(function(){
        $("#searchField").autocomplete({
            source: autocomplete
        });
    });
</script></body>