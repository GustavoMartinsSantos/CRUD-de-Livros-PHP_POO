<form method="POST" enctype="multipart/form-data" class="form-group">
    <input type="hidden" name="ID" value=<?= $Livro->getID() ?>>

    <div class="form-floating">
        <input type="text" name="Nome" id="Nome_Livro" class="form-control"  maxlength="100" value="<?= $Livro->getNome() ?>" placeholder=" " autofocus required>
        <label class="form-label" for="Nome">Nome do livro</label>
    </div>

    <div class="row g-2 mt-3 text-light">
        <div class="col-md">
            <label class="form-label">Preço</label>
            <input type="number" name="Preco" min=0 step=0.01 class="form-control" value="<?= number_format($Livro->getPreco(), 2, '.') ?>">
        </div>

        <div class="col-md">
            <label class="form-label">Data de Publicação</label>
            <?php date_default_timezone_set('America/Sao_Paulo');  
                if($Livro->getData_pub() != NULL) {
                    $data = new Datetime($Livro->getData_pub());
                    $data = $data->format("Y-m-d");
                } else
                    $data = NULL ?>
            <input type="date" name="Data_Pub" class="form-control" max="<?= date('Y-m-d') ?>" value="<?= $data ?>">
        </div>
    </div>

    <div class="row g-2 mt-3 text-light">
        <div class="col-md">
            <label class="form-label">Número de Páginas</label>
            <input type="number" name="Num_paginas" min=1 class="form-control" value="<?= $Livro->getNum_paginas() ?>" required>
        </div>

        <div class="col-md">
            <label class="form-label">Imagem</label>
            <input type="file" name="arquivo" class="form-control" accept="image/*">

            <input type="hidden" name="ID_Img" value=<?= $Livro->getArquivo()->getID() ?>>
        </div>
    </div>

    <label class="form-label mt-3 text-light">Sinopse</label>
    <textarea name="Sinopse" rows="4" class="form-control" maxlength="60000" style="resize: none"><?= $Livro->getSinopse() ?></textarea>

    <div class="input-group mt-3">
        <label class="input-group-text">Editora</label>
        <select name="Editora" class="form-control">
        <?php foreach(Editora::SELECT_Editoras($db) as $Editora) { ?>
            <option value="<?= $Editora->getCod() ?>"
            <?= $Livro->getEditora()->getCod() == $Editora->getCod() ? "selected" : null ?>>
                <?= $Editora->getNome() ?>
            </option>
        <?php } ?>
            <option value=0 <?= $Livro->getEditora()->getCod() == NULL ? "selected" : null ?>>Nenhuma das anteriores</option>
        </select>

        <label class="input-group-text">Idioma</label>
        <select name="Idioma" class="form-control">
        <?php foreach(Idioma::SELECT_Idiomas($db) as $Idioma) { ?>
            <option value="<?= $Idioma->getID() ?>"
            <?= $Livro->getIdioma()->getID() == $Idioma->getID() ? "selected" : null?>>
                <?= $Idioma->getNome() ?>
            </option>
        <?php } ?>
        </select>
    </div>

    <div class="input-group mt-3">
        <label class="input-group-text">Autores</label>
        <select name="Autores[]" class="form-control" multiple required>
        <?php foreach(Autor::SELECT_Autores($db) as $Autor) { ?>
            <option value="<?= $Autor->getID() ?>"
            <?= $Livro->SELECTED_Autor($Autor->getID()) ? "selected" : null ?>>
                <?= "{$Autor->getNome()} {$Autor->getSobrenome()}" ?>
            </option>
        <?php } ?>
        </select>

        <label class="input-group-text">Categorias</label>
        <select name="Categorias[]" class="form-control" multiple required>
        <?php foreach(Categoria::SELECT_Categorias($db) as $Categoria) { ?>
            <option value="<?= $Categoria->getCod() ?>"
            <?= $Livro->SELECTED_Categoria($Categoria->getCod()) ? "selected" : null ?>>
                <?= $Categoria->getNome() ?>
            </option>
        <?php } ?>
        </select>
    </div>
        
    <div class="text-center">
        <input type="submit" class="btn bg-dark mt-3 w-50 text-light" value="Editar">
    </div>
</form></div></body>