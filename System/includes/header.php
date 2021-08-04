<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <link type="image/x-icon" rel="shortcut icon" href="<?= TITLE == "Livros" ? "" : "../"?>IMG/logo-icone.ico">
        <title><?php echo TITLE ?></title>
        <style>
            label {
                font-size: 13pt;
            }

            a {
                text-decoration: none;
            }

            .text-justify {
                text-align: justify;
            }
        </style>
    </head>
    <body class="bg-warning">
        <ul class="nav nav-tabs bg-dark justify-content-center border-dark">
            <li class="nav-item mx-4 mt-4">
                <a class="nav-link <?= ACTIVE == 0 ? "active bg-warning border-warning" : null ?>" href="<?= DIR_Principal ?>">Livros</a>
            </li>
            <li class="nav-item mx-4 mt-4">
                <a class="nav-link <?= ACTIVE == 1 ? "active bg-warning border-warning" : null ?>" href="<?= DIR_CRLivro ?>">Cadastrar</a>
            </li>
            <li class="nav-item mx-4 mt-4">
                <a class="nav-link <?= ACTIVE == 2 ? "active bg-warning border-warning" : null ?>" href="<?= DIR_Categorias ?>">Categorias</a>
            </li>
            <li class="nav-item mx-4 mt-4">
                <a class="nav-link <?= ACTIVE == 3 ? "active bg-warning border-warning" : null ?>" href="<?= DIR_Autores ?>">Autores</a>
            </li>
            <li class="nav-item mx-4 mt-4">
                <a class="nav-link <?= ACTIVE == 4 ? "active bg-warning border-warning" : null ?>" href="<?= DIR_Editoras ?>">Editoras</a>
            </li>
            <li class="nav-item mx-4 mt-4">
                <a class="nav-link <?= ACTIVE == 5 ? "active bg-warning border-warning" : null ?>" href="<?= DIR_Idiomas ?>">Idiomas</a>
            </li>
        </ul>
        <div class="container bg-primary p-4">