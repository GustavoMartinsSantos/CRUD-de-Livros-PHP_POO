<?php
    class Categoriza {
        private static string $table = "tbl_Categoriza";
        private Livro $Livro;
        private Categoria $Categoria;

        public function setLivro (Livro $livro) {
            $this->Livro = $livro;
        }

        public function getLivro (): Livro {
            return $this->Livro;
        }

        public function setCategoria (Categoria $categoria) {
            $this->Categoria = $categoria;
        }

        public function getCategoria (): Categoria {
            return $this->Categoria;
        }

        public function INSERT_Categoriza (Database $db) {
            $values = array (
                'ID_Livro'     => $this->Livro->getID(),
                'Cod_Categoria' => $this->Categoria->getCod()
            );

            $db->INSERT(self::$table, $values);
        }

        public static function DELETE_Categoriza_ByBook (Database $db, int $pk) {
            $pk = ['ID_Livro' => $pk];

            $db->DELETE(self::$table, $pk);
        }

        public static function DELETE_Categoriza_ByCategoria (Database $db, int $pk) {
            $pk = ['Cod_Categoria' => $pk];

            $db->DELETE(self::$table, $pk);
        }

        public function __construct (Categoria $categoria, Livro $livro) {
            $this->setCategoria($categoria);
            $this->setLivro($livro);
        }
    }
?>