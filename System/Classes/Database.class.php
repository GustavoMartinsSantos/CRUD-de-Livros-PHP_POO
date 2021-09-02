<?php
    class Database {
        private static string $HOST = 'localhost';
        private static string $DBNAME = 'crud_livros';
        private static string $USER = 'root';
        private static string $PASS = 'G4jIS9D2d62';
        private PDO $connection;

        private function setConnection () {
            try {
                $this->connection = new PDO(
                    "mysql:host=". self::$HOST . ";"
                   ."dbname=" . self::$DBNAME
                   , self::$USER
                   , self::$PASS
                    /*"sqlsrv:Database=crud_livros;
                               server=192.168.0.33,1433",
                               "User_CRUD_Livros", 
                               "YsmeIC792Np"*/
                    /*
                    "sqlsrv:Database=crud_livros;
                               server=localhost",
                               "sa", 
                               "YsmeIC792Nj"*/
                );
            } catch(PDOException $e) {
                die("ERRO: " . $e->getMessage());
            }
        }

        public function getConnection (): PDO {
            return $this->connection;
        }

        private function executeQuery (string $query, $values = []) {
            try {
                $stmt = $this->getConnection()->prepare($query);
                $stmt->execute(array_values($values));
                
                return $stmt;
            } catch(PDOException $e) {
                echo $query;
                die("<br>ERRO: {$e->getMessage()}<br>");
            }
        }

        public function INSERT (string $table, array $values) {
            $keys = array_keys($values);
            $binds = array_fill(0, sizeof($keys), '?');

            $query  = "INSERT INTO $table (" . implode(',',$keys) . ")";
            $query .= " VALUES (" . implode(',', $binds) . ")";

            $this->executeQuery($query, array_values($values));
            return $this->connection->lastInsertId();
        }

        public function SELECT (string $query): array {
            return $this->executeQuery($query)->fetchAll();
        }

        public function UPDATE (string $table, array $values, $pk) {
            $query  = "UPDATE $table ";
            $query .= "SET " . implode('=?, ', array_keys($values)) . '=?';
            $query .= ' WHERE ' . array_keys($pk)[0] . " = " . array_values($pk)[0];

            $this->executeQuery($query, array_values($values));
        }

        public function DELETE (string $table, array $pk): PDOStatement {
            $query  = "DELETE FROM $table ";
            $query .= "WHERE " . array_keys($pk)[0] . " = " . array_values($pk)[0];

            return $this->executeQuery($query);
        }

        public function __construct () {
            $this->setConnection();
        }
    }
?>