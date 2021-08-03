<?php
    class Idioma {
        private static string $table = "tbl_Idioma";
        private int $ID;
        private string $Nome;

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

        public function INSERT_Idioma (Database $db) {
            $values = array (
                'Nome'      => $this->nome,
            );

            $this->setID($db->INSERT(self::$table, $values));
        }
        
        public static function SELECT_Idiomas (Database $db, string $WHERE = null): array {
            $query  = "SELECT ID, Nome FROM " . self::$table . " {$WHERE} ORDER BY 2";
            $rows = $db->SELECT($query);
            $Idiomas = array();
            
            foreach($rows as $row) {
                $Idioma = new Idioma();
                $Idioma->setID($row['ID']);
                $Idioma->setNome($row['Nome']);

                $Idiomas[] = $Idioma;
            }

            return $Idiomas;
        }

        public static function SELECT_COUNT (Database $db, string $WHERE = null): int {
            $query  = "SELECT COUNT(ID) AS COUNT ".
            "FROM " . self::$table . " {$WHERE} ORDER BY 1";

            return intval($db->SELECT($query)[0]['COUNT']);
        }

        public function UPDATE_Idioma (Database $db) {
            $values = array(
                'Nome' => $this->nome
            );

            $pk = array('ID' => $this->getID());

            $db->UPDATE(self::$table, $values, $pk);
        }

        public function DELETE_Idioma (Database $db) {
            $pk = array('ID' => $this->getID());

            $db->DELETE(self::$table, $pk);
        }
    }
?>