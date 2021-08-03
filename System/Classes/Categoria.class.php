<?php
    class Categoria {
        private static string $table = "tbl_Categoria";
        private int $Cod;
        private string $Nome;
        private array $Categorizacoes = array();

        public function setCod (int $cod) {
            $this->Cod = $cod;
        }

        public function getCod (): int {
            return $this->Cod;
        }

        public function setNome (string $nome) {
            $this->Nome = $nome;
        }

        public function getNome (): string {
            return $this->Nome;
        }

        public function addCategorizacao (Categoriza $cat) {
            $this->Categorizacoes[] = $cat;
        }

        public function getCategorizacoes (): array {
            return $this->Categorizacoes;
        }

        public function INSERT_Categoria (Database $db) {
            $values = array (
                'Nome' => $this->Nome
            );

            $this->setCod($db->INSERT(self::$table, $values));
        }

        public static function SELECT_Categorias (Database $db, string $WHERE = NULL): array {
            $query  = "SELECT Cod, Nome FROM " . self::$table . " {$WHERE} ORDER BY 2";

            $rows = $db->SELECT($query);
            $Categorias = array();

            foreach($rows as $row) {
                $Categoria = new Categoria();
                $Categoria->setCod($row['Cod']);
                $Categoria->setNome($row['Nome']);

                $Categorias[] = $Categoria;
            }

            return $Categorias;
        }

        public static function SELECT_COUNT (Database $db, string $WHERE = null): int {
            $query  = "SELECT COUNT(Cod) AS COUNT ".
            "FROM " . self::$table . " {$WHERE} ORDER BY 1";

            return intval($db->SELECT($query)[0]['COUNT']);
        }

        public function UPDATE_Categoria (Database $db) {
            $values = array (
                'Nome' => $this->Nome
            );

            Categoriza::DELETE_Categoriza_ByCategoria($db, $this->getCod());

            foreach($this->Categorizacoes as $Categorizacao) {
                $Categorizacao->INSERT_Categoriza($db);
            }

            $pk = array('Cod' => $this->getCod());

            $db->UPDATE(self::$table, $values, $pk);
        }

        public function DELETE_Categoria (Database $db) {
            $pk = array('Cod' => $this->getCod());

            $db->DELETE(self::$table, $pk);
        }
    }
?>