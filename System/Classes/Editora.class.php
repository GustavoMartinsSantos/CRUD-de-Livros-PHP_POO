<?php
    class Editora {
        private static string $table = "tbl_Editora";
        private int $Cod;
        private string $Nome;

        public function setCod (int $cod) {
            $this->Cod = $cod;
        }

        public function getCod (): int {
            return $this->Cod;
        }

        public function setNome (string $nome) {
            $this->nome = $nome;
        }

        public function getNome (): string {
            return $this->nome;
        }

        public function INSERT_Editora (Database $db) {
            $values = array (
                'Nome'      => $this->nome
            );

            $this->setCod($db->INSERT(self::$table, $values));
        }
        
        public static function SELECT_Editoras (Database $db, string $WHERE = null): array {
            $query  = "SELECT Cod, Nome FROM " . self::$table . " {$WHERE} ORDER BY 2";
            $rows = $db->SELECT($query);
            $Editoras = array();

            foreach($rows as $row) {
                $Editora = new Editora();
                $Editora->setCod($row['Cod']);
                $Editora->setNome($row['Nome']);

                $Editoras[] = $Editora;
            }

            return $Editoras;
        }

        public static function SELECT_COUNT (Database $db, string $WHERE = null): int {
            $query  = "SELECT COUNT(Cod) AS COUNT ".
            "FROM " . self::$table . " {$WHERE} ORDER BY 1";

            return intval($db->SELECT($query)[0]['COUNT']);
        }
        
        public function UPDATE_Editora (Database $db) {
            $values = array(
                'Nome' => $this->nome
            );

            $pk = array('Cod' => $this->getCod());

            $db->UPDATE(self::$table, $values, $pk);
        }

        public function DELETE_Editora (Database $db) {
            $pk = array('Cod' => $this->getCod());

            $db->DELETE(self::$table, $pk);
        }
    }
?>