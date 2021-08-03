<?php
    class Autor {
        private static string $table = "tbl_Autor";
        private int $ID;
        private string $Nome;
        private string $Sobrenome;
        private array $Escritas = array();

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

        public function setSobrenome (string $sobrenome) {
            $this->sobrenome = $sobrenome;
        }

        public function getSobrenome (): string {
            return $this->sobrenome;
        }

        public function addEscrita (Escrita $Escrita) {
            $this->Escritas[] = $Escrita;
        }

        public function getEscritas(): array {
            return $this->Escritas;
        }

        public function INSERT_Autor (Database $db) {
            $values = array (
                'Nome'      => $this->nome,
                'Sobrenome' => NULL
            );

            if($this->getSobrenome() != NULL)
                $values['Sobrenome'] = $this->sobrenome;

            $this->setID($db->INSERT(self::$table, $values));
        }

        public static function SELECT_Autores (Database $db, string $WHERE = null): array {
            $query  = "SELECT ID, Nome, Sobrenome FROM " . self::$table . " {$WHERE} ORDER BY 2";
            $rows = $db->SELECT($query);
            $Autores = array();

            foreach($rows as $row) {
                $Autor = new Autor();
                $Autor->setID($row['ID']);
                $Autor->setNome($row['Nome']);

                if($row['Sobrenome'] != NULL)
                    $Autor->setSobrenome($row['Sobrenome']);
                else
                    $Autor->setSobrenome("");

                $Autores[] = $Autor;
            }

            return $Autores;
        }

        public static function SELECT_COUNT (Database $db, string $WHERE = null): int {
            $query  = "SELECT COUNT(ID) AS COUNT ".
            "FROM " . self::$table . " {$WHERE} ORDER BY 1";

            return intval($db->SELECT($query)[0]['COUNT']);
        }

        public function UPDATE_Autor (Database $db) {
            $values = array (
                'Nome'      => $this->nome,
                'Sobrenome' => NULL
            );

            if($this->getSobrenome() != NULL)
                $values['Sobrenome'] = $this->sobrenome;

            Escrita::DELETE_Escrita_ByAutor($db, $this->getID());

            foreach($this->Escritas as $Escrita) {
                $Escrita->INSERT_Escrita($db);
            }

            $pk = array('ID' => $this->getID());

            $db->UPDATE(self::$table, $values, $pk);
        }
        
        public function DELETE_Autor (Database $db) {
            $pk = array('ID' => $this->getID());

            return $db->DELETE(self::$table, $pk);
        }
    }
?>