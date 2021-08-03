<?php
    class Escrita {
        private static string $table = "tbl_Escreve";
        private Autor $Autor;
        private Livro $Livro;

        public function setAutor (Autor $autor) {
            $this->Autor = $autor;
        }

        public function getAutor (): Autor {
            return $this->Autor;
        }

        public function setLivro (Livro $livro) {
            $this->Livro = $livro;
        }

        public function getLivro (): Livro {
            return $this->Livro;
        }

        public function INSERT_Escrita (Database $db) {
            $values = array (
                'ID_Livro' => $this->Livro->getID(),
                'ID_Autor' => $this->Autor->getID()
            );

            $db->INSERT(self::$table, $values);
        }

        public static function DELETE_Escrita_ByBook (Database $db, int $pk) {
            $pk = ['ID_Livro' => $pk];

            return $db->DELETE(self::$table, $pk);
        }

        public static function DELETE_Escrita_ByAutor (Database $db, int $pk) {
            $pk = ['ID_Autor' => $pk];

            return $db->DELETE(self::$table, $pk);
        }

        public function __construct (Autor $autor, Livro $livro) {
            $this->setAutor($autor);
            $this->setLivro($livro);
        }
    }
?>