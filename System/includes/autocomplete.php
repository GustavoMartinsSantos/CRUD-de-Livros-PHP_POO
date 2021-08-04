<meta charset="UTF-8">
<?php
    function autocomplete (string $filter) {
        $data = [];
        $db = new Database();

        switch($filter) {
            case 'livro':
                $data = Livro::SELECT_Livros($db);
                break;
            case 'editora':
                $data = Editora::SELECT_Editoras($db);
                break;
            case 'autor':
                $data = Autor::SELECT_Autores($db);
                break;
            case 'categoria':
                $data = Categoria::SELECT_Categorias($db);
                break;
            case 'idioma':
                $data = Idioma::SELECT_Idiomas($db);
                break;
        }

        for($x = 0; $x < count($data); $x++) {
            if($filter == 'autor')
                $data[$x] = "{$data[$x]->getNome()} {$data[$x]->getSobrenome()}";
            else
                $data[$x] = $data[$x]->getNome();
        }
        
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
?>