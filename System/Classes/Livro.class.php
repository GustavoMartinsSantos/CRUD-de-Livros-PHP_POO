<?php
    class Livro {
        private static string $table = "tbl_Livro";
        private int $ID;
        private string $Nome;
        private float $Preco;
        private string $Data_Pub;
        private int $Num_Paginas;
        private string $sinopse;
        private Editora $Editora;
        private Idioma $Idioma;
        private Arquivo $Arquivo;
        private array $Escritas = array();
        private array $Categorizacoes = array();

        public function setID (int $ID) {
            $this->ID = $ID;
        }

        public function getID (): int {
            return $this->ID;
        }

        public function setNome (string $nome) {
            $this->nome = $nome;
        }

        public function getNome (): string {
            return $this->nome;
        }

        public function setPreco (float $preco) {
            $this->preco = $preco;
        }

        public function getPreco (): float {
            return $this->preco;
        }

        public function setData_pub(string $data) {
            $this->data_pub = $data;
        }

        public function getData_pub (): string {
            return $this->data_pub;
        }

        public function setNum_paginas (int $num_paginas) {
            $this->num_paginas = $num_paginas;
        }

        public function getNum_paginas (): int {
            return $this->num_paginas;
        }

        public function setSinopse (string $sinopse) {
            $this->sinopse = $sinopse;
        }

        public function getSinopse (): string {
            return $this->sinopse;
        }

        public function setEditora (Editora $editora) {
            $this->Editora = $editora;
        }

        public function getEditora (): Editora {
            return $this->Editora;
        }

        public function setIdioma (Idioma $idioma) {
            $this->Idioma = $idioma;
        }

        public function getIdioma (): Idioma {
            return $this->Idioma;
        }

        public function addEscrita (Escrita $Escrita) {
            $this->Escritas[] = $Escrita;
        }

        public function getEscritas(): array {
            return $this->Escritas;
        }

        public function setArquivo (Arquivo $file) {
            $this->Arquivo = $file;
        }

        public function getArquivo (): Arquivo {
            return $this->Arquivo;
        }

        public function addCategorizacao (Categoriza $cat) {
            $this->Categorizacoes[] = $cat;
        }

        public function getCategorizacoes (): array {
            return $this->Categorizacoes;
        }

        public function SELECTED_Autor (int $id) {
            foreach($this->Escritas as $Escrita) {
                if($Escrita->getAutor()->getID() == $id)
                    return true;
            }
        }

        public function SELECTED_Categoria (int $Cod) {
            foreach($this->Categorizacoes as $Categoriza) {
                if($Categoriza->getCategoria()->getCod() == $Cod)
                    return true;
            }
        }

        public function AddReadMoreSinopse (): string {
            $Sinopse = nl2br($this->getSinopse());
        
            if (mb_strlen($Sinopse, 'UTF-8') > 600):
                $HTML = mb_substr($Sinopse, 0, 600, 'UTF-8') .
                '<span class="pontos'.$this->getID().'">...</span>' .
                '<a class="read-more'.$this->getID().'" onclick="ReadMore('.$this->getID().')"> Leia mais</a>' .
                '<span class="more-text'.$this->getID().'" style="display: none">' . mb_substr($Sinopse, 600, -1, 'UTF-8') . '</span>' .
                '<a class="read-less'.$this->getID().'" style="display: none" onclick="ReadMore('.$this->getID().')"> Leia menos</a>';
           else:
               $HTML = $Sinopse;
           endif;
           
           return $HTML;
        }

        public function INSERT_Livro (Database $db) {
            $this->Arquivo->INSERT_File($db);

            $values = array(
                'nome'        => $this->nome,
                'preco'       => $this->preco,
                'data_pub'    => NULL,
                'num_paginas' => $this->num_paginas,
                'sinopse'     => $this->sinopse,
                'Cod_editora' => NULL,
                'ID_Idioma'   => $this->Idioma->getID(),
                'ID_Img'      => $this->Arquivo->getID()
            );

            if($this->getData_pub() != NULL)
                $values['data_pub'] = $this->data_pub;
            if($this->Editora->getCod() != NULL)
                $values['Cod_editora'] = $this->Editora->getCod();

            $this->setID($db->INSERT(self::$table, $values));

            foreach($this->Escritas as $Escrita) {
                $Escrita->INSERT_Escrita($db);
            }

            foreach($this->Categorizacoes as $Categorizacao) {
                $Categorizacao->INSERT_Categoriza($db);
            }
        }

        private static function getLivros (array $rows): array {
            $Livros = array();
            $idLivro = 0;
            $cont = 0;
            $Editoras = array();
            $Autores = array();
            $Idiomas = array();
            $Categorias = array();

            foreach($rows as $row) {
                if($row['ID'] != $idLivro):
                    $Livro = new Livro();

                    $Livro->setID($row['ID']);
                    $Livro->setNome($row['Livro']);
                    $Livro->setPreco($row['Preco']);
                    $Livro->setNum_paginas($row['Num_Paginas']);
                    $Livro->setSinopse($row['Sinopse']);

                    if($row['Data_Pub'] == NULL)
                        $Livro->setData_pub("");
                    else
                        $Livro->setData_pub($row['Data_Pub']);                    
                    
                    $Arquivo = new Arquivo();
                    $Arquivo->setID($row['ID_Img']);
                    $Arquivo->setNome($row['Img_Nome']);
                    $Arquivo->setExtension($row['Extension']);
                    
                    $Livro->setArquivo($Arquivo);

                    $diferente = true;
                    foreach($Editoras as $Editora) {
                        if($row['Cod_Editora'] == $Editora->getCod()) {
                            $diferente = false;
                            break;
                        }
                    }

                    if($diferente) {
                        $Editora = new Editora();
                        if($row['Cod_Editora'] == NULL) {
                            $Editora->setCod(0);
                            $Editora->setNome("");
                        } else {
                            $Editora->setCod($row['Cod_Editora']);
                            $Editora->setNome($row['Editora']);
                        }
                        
                        $Editoras[] = $Editora;
                    }

                    $Livro->setEditora($Editora);

                    $diferente = true;
                    foreach($Idiomas as $Idioma) {
                        if($row['ID_Idioma'] == $Idioma->getID()) {
                            $diferente = false;
                            break;
                        }
                    }

                    if($diferente) {
                        $Idioma = new Idioma();
                        $Idioma->setID($row['ID_Idioma']);
                        $Idioma->setNome($row['Idioma']);
                        
                        $Idiomas[] = $Idioma;
                    }
                    
                    // $Idioma pode ser o último adicinado, ou o último da lista $Idiomas
                    $Livro->setIdioma($Idioma);
                    
                    $Livros[] = $Livro;
                    
                    $lastIDAutor = $row['ID_Autor'];
                    $lastCodCategoria = $row['Cod_Categoria'];
                endif;

                if($row['ID_Autor'] == $lastIDAutor) {
                    $diferente = true;
                    foreach($Categorias as $Categoria) {
                        if($row['Cod_Categoria'] == $Categoria->getCod()) {
                            $diferente = false;
                            break;
                        }
                    }
                                
                    if($diferente) {
                        $Categoria = new Categoria();
                        $Categoria->setCod($row['Cod_Categoria']);
                        $Categoria->setNome($row['Categoria']);
                                    
                        $Categorias[] = $Categoria;
                    }

                    $Livro->addCategorizacao(new Categoriza($Categoria, $Livro));
                }
                    
                if($row['Cod_Categoria'] == $lastCodCategoria) {
                    $diferente = true;
                    foreach($Autores as $Autor) {
                        if($row['ID_Autor'] == $Autor->getID()) {
                            $diferente = false;
                            break;
                        }
                    }
                    
                    if($diferente) {
                        $Autor = new Autor();
                        $Autor->setID($row['ID_Autor']);
                        $Autor->setNome($row['Nome']);
                        if($row['Sobrenome'] != NULL)
                            $Autor->setSobrenome($row['Sobrenome']);
                        else
                            $Autor->setSobrenome("");
                        $Autores[] = $Autor;
                    }

                    $Livro->addEscrita(new Escrita($Autor, $Livro));
                }
                    
                $idLivro = $Livro->getID();
            }

            return $Livros;
        }

        public static function SELECT_COUNT (Database $db, string $WHERE = NULL): int {
            $query = "SELECT COUNT(DISTINCT L.ID) AS COUNT " .
                     "FROM tbl_Livro L " .
                     "LEFT JOIN tbl_Editora E " .
                     "ON Cod_Editora = Cod " .
                     "INNER JOIN tbl_Idioma I " .
                     "ON ID_Idioma = I.ID " .
                     "INNER JOIN tbl_Imagem Img " .
                     "ON ID_Img = Img.ID " .
                     "INNER JOIN tbl_Categoriza Categoriza " .
                     "ON ID_Livro = L.ID " .
                     "INNER JOIN tbl_Categoria C " .
                     "ON Categoriza.Cod_Categoria = C.Cod " .
                     "INNER JOIN tbl_Escreve Escreve " .
                     "ON Escreve.ID_Livro = L.ID " .
                     "INNER JOIN tbl_Autor A " .
                     "ON ID_Autor = A.ID " .
                     $WHERE .
                     " ORDER BY 1";

            return intval($db->SELECT($query)[0]['COUNT']);
        }

        public static function SELECT_Livros (Database $db, string $WHERE = NULL, $limit = NULL): array {
            $query = "SELECT L.ID, L.Nome AS 'Livro', Preco, Data_Pub," .
                     "Num_Paginas, Sinopse, Cod_Editora, E.Nome AS 'Editora'," .
                     "ID_Idioma, I.Nome AS 'Idioma', ID_Img, Img.Nome AS Img_Nome," .
                     "Img.Extension, Cod_Categoria, C.Nome AS 'Categoria',". 
                     "ID_Autor,A.Nome, A.Sobrenome " .
                     "FROM tbl_Livro L " .
                     "LEFT JOIN tbl_Editora E " .
                     "ON Cod_Editora = Cod " .
                     "INNER JOIN tbl_Idioma I " .
                     "ON ID_Idioma = I.ID " .
                     "INNER JOIN tbl_Imagem Img " .
                     "ON ID_Img = Img.ID " .
                     "INNER JOIN tbl_Categoriza Categoriza " .
                     "ON ID_Livro = L.ID " .
                     "INNER JOIN tbl_Categoria C " .
                     "ON Categoriza.Cod_Categoria = C.Cod " .
                     "INNER JOIN tbl_Escreve Escreve " .
                     "ON Escreve.ID_Livro = L.ID " .
                     "INNER JOIN tbl_Autor A " .
                     "ON ID_Autor = A.ID " .
                     $WHERE .
                     " ORDER BY 1";

            $rows = $db->SELECT($query);

            return self::getLivros($rows);
        }

        public function UPDATE_Editora (Database $db) {
            $values = array (
                'Cod_editora' => $this->Editora->getCod()
            );
            
            $pk = array('ID' => $this->getID());

            $db->UPDATE(self::$table, $values, $pk);
        }

        public function UPDATE_Idioma (Database $db) {
            $values = array (
                'ID_Idioma' => $this->Idioma->getID()
            );
            
            $pk = array('ID' => $this->getID());

            $db->UPDATE(self::$table, $values, $pk);
        }

        public function UPDATE_Livro (Database $db) {
            if($this->Arquivo->getError() == 0)
                $this->Arquivo->UPDATE_File($db);
            
            $values = array(
                'nome'        => $this->nome,
                'preco'       => $this->preco,
                'data_pub'    => NULL,
                'num_paginas' => $this->num_paginas,
                'sinopse'     => $this->sinopse,
                'Cod_editora' => NULL,
                'ID_Idioma'   => $this->Idioma->getID(),
            );

            if($this->getData_pub() != NULL)
                $values['data_pub'] = $this->data_pub;
            if($this->Editora->getCod() != NULL)
                $values['Cod_editora'] = $this->Editora->getCod();

            Escrita::DELETE_Escrita_ByBook($db, $this->getID());
            Categoriza::DELETE_Categoriza_ByBook($db, $this->getID());

            foreach($this->Escritas as $Escrita) {
                $Escrita->INSERT_Escrita($db);
            }

            foreach($this->Categorizacoes as $Categorizacao) {
                $Categorizacao->INSERT_Categoriza($db);
            }
  
            $pk = array('ID' => $this->getID());

            $db->UPDATE(self::$table, $values, $pk);
        }

        public function DELETE_Livro (Database $db): bool {
            $pk = array('ID' => $this->getID());

            if(!$db->DELETE(self::$table, $pk))
                return false;
            
            if(!$this->Arquivo->DELETE_File($db))
                return false;

            return true;
        }
    }
?>