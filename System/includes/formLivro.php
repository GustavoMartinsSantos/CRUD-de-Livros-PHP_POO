<?php 
    require_once '../Classes/Database.class.php';
    require_once '../Classes/Autor.class.php';
    require_once '../Classes/Idioma.class.php';
    require_once '../Classes/Editora.class.php';
    require_once '../Classes/Categoria.class.php';
    
    $db = new Database();
    date_default_timezone_set('America/Sao_Paulo');

    $Idiomas = Idioma::SELECT_Idiomas($db);
    $Editoras = Editora::SELECT_Editoras($db);
    $Autores = Autor::SELECT_Autores($db);
?>
<form method="POST" enctype="multipart/form-data" class="form-group">
    <div class="form-floating">
        <input type="text" name="Nome" id="Nome_Livro" class="form-control" placeholder=" " maxlength="100" autofocus required>
        <label class="form-label" for="Nome">Nome do livro</label>
    </div>

    <div class="row g-2 mt-3 text-light">
        <div class="col-md">
            <label class="form-label">Preço</label>
            <input type="number" name="Preco" min=0 step=0.01 class="form-control" value=0>
        </div>

        <div class="col-md">
            <label class="form-label">Data de Publicação</label>
            <input type="date" name="Data_Pub" class="form-control" max="<?= date('Y-m-d') ?>">
        </div>
    </div>

    <div class="row g-2 mt-3 text-light">
        <div class="col-md">
            <label class="form-label">Número de Páginas</label>
            <input type="number" name="Num_paginas" min=1 class="form-control" required>
        </div>

        <div class="col-md">
            <label class="form-label">Imagem</label>
            <input type="file" name="arquivo" class="form-control" accept="image/*" required>
        </div>
    </div>

    <label class="form-label mt-3 text-light">Sinopse</label>
    <textarea name="Sinopse" rows="4" class="form-control" style="resize: none" maxlength="60000"></textarea>

    <div class="input-group mt-3">
        <label class="input-group-text">Editora</label>
        <select name="Editora" class="form-control">
        <?php foreach($Editoras as $Editora) {?>
            <option value="<?= $Editora->getCod() ?>">
                <?= $Editora->getNome() ?>
            </option>
            <?php } ?>
            <option value=0 selected>Nenhuma das anteriores</option>
        </select>

        <label class="input-group-text">Idioma</label>
        <select name="Idioma" class="form-control" required>
        <?php foreach($Idiomas as $Idioma) { ?>
            <option value="<?= $Idioma->getID() ?>">
                <?= $Idioma->getNome() ?>
            </option>
        <?php } ?> 
        </select>
    </div>

    <div class="input-group mt-3">
        <label class="input-group-text">Autores</label>
        <select name="Autores[]" class="form-control" multiple required>
        <?php foreach($Autores as $Autor) { ?>
            <option value="<?= $Autor->getID() ?>">
                <?= "{$Autor->getNome()} {$Autor->getSobrenome()}" ?>
            </option>
        <?php } ?>
        </select>

        <label class="input-group-text">Categorias</label>
        <select name="Categorias[]" class="form-control" multiple required>
        <?php foreach(Categoria::SELECT_Categorias($db) as $Categoria) { ?>
            <option value="<?= $Categoria->getCod() ?>">
                <?= $Categoria->getNome() ?>
            </option>
        <?php } ?>
        </select>
    </div>
        
    <div class="text-center">
        <input type="submit" class="btn bg-dark mt-3 w-50 text-light" value="Cadastrar">
    </div>
</form></div></body>