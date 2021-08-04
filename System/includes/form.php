<form method="POST" class="form-group">
    <div class="form-floating">
        <input type="text" name="Nome" class="form-control" placeholder=" "  maxlength="<?= maxLength?>" autofocus required>
        <label class="form-label" for="Nome"><?= FieldName ?></label>
    </div>

    <div class="input-group mt-3">
        <label class="input-group-text">Traduções</label>
        <select name="Livros[]" class="form-control" multiple <?= requiredBook ?>>
        <?php foreach($Livros as $Livro) { ?>
            <option value="<?= $Livro->getID() ?>">
            <?= $Livro->getNome() ?>
            </option>
        <?php } ?>
        </select>
    </div>

    <div class="text-center">
        <input type="submit" class="btn bg-dark mt-3 w-50 text-light" value="Cadastrar">
    </div>
</form></div></body>