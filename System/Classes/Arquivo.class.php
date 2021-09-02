<?php
    class Arquivo {
        private static string $table = "tbl_Imagem";
        private static string $uploadDir = '../IMG/';
        private int $ID;
        private string $Nome;
        private string $Extension;
        private int $Tamanho;
        private int $error;
        private string $tmpName;

        public function setID(int $ID) {
            $this->ID = $ID;
        }

        public function getID(): int {
            return $this->ID;
        }

        public function setNome (string $nome) {
            $this->Nome = $nome;
        }

        public function getNome (): string {
            return $this->Nome;
        }

        public function setExtension (string $extension) {
            $this->Extension = strtolower($extension);
        }

        public function getExtension (): string {
            return "." . $this->Extension;
        }

        public function setTamanho (int $size) {
            $this->Tamanho = $size;
        }

        public function getTamanho (): int {
            return $this->Tamanho;
        }

        public function setError (int $erro) {
            $this->error = $erro;
        }

        public function getError (): int {
            return $this->error;
        }

        public function setTmpName (string $dir) {
            $this->tmpName = $dir;
        }

        public function getTmpName (): string {
            return $this->tmpName;
        }

        private function Upload (): bool {
            $valid_extensions = array('.jpeg', '.jpg', '.png');

            $IS_VALID = false;
            foreach($valid_extensions as $valid) {
                if($this->getExtension() == $valid)
                    $IS_VALID = true;
            }

            if($this->getError() != 0 || !$IS_VALID)
               return false;

            $destination = self::$uploadDir . $this->getNome() . $this->getExtension();
            move_uploaded_file($this->getTmpName(), $destination);

            return true;
        }

        public function INSERT_File (Database $db) {
            $upload = $this->Upload();

            if($upload) {
                $size = round($this->getTamanho() / 1024, 2);
                date_default_timezone_set('America/Sao_Paulo');
                $data = date('y-m-d H:i:s');

                $values = array(
                    'Nome'       =>$this->Nome,
                    'Extension'  =>$this->Extension,
                    'Tamanho_KB' =>$size,
                    'Data'       =>$data
                );

                $this->setID($db->INSERT(self::$table, $values));
            }
        }

        private static function getFile (Database $db, int $ID): array {
            $query = "SELECT ID, Nome, Extension, Tamanho_KB, Data " .
                     "FROM " . self::$table . 
                     " WHERE ID = $ID";

            return $db->SELECT($query)[0];
        }

        public function UPDATE_File (Database $db) {
            $OldName = self::getFile($db, $this->getID());

            $path = self::$uploadDir . $OldName['Nome'] . "." . $OldName['Extension'];
            
            $delete = unlink($path);
            $upload = $this->Upload();

            if($upload) {
                $size = round($this->getTamanho() / 1024, 2);
                
                date_default_timezone_set('America/Sao_Paulo');
                $data = date('Y-m-d H:i:s');

                $values = array(
                    'Nome'       =>$this->Nome,
                    'Extension'  =>$this->Extension,
                    'Tamanho_KB' =>$size,
                    'Data'       =>$data
                );

                $pk = array('ID' => $this->getID());

                $db->UPDATE(self::$table, $values, $pk);
            }
        }

        public function DELETE_File (Database $db) {
            $Arquivo = self::getFile($db, $this->getID());

            $path = self::$uploadDir . $Arquivo['Nome'] . "." . $Arquivo['Extension'];
            $delete = unlink($path);

            if($delete) {
                $pk = array('ID' => $this->getID());

                return $db->DELETE(self::$table, $pk);
            }
        }

        /*
        $file = $_FILES['arquivo'];
        $Arquivo = new Arquivo();
        //$basename = pathinfo($file['name'])['basename'];
        $extension = pathinfo($file['name'])['extension'];

        $Arquivo->setNome(md5(time()));
        $Arquivo->setExtension($extension);
        $Arquivo->setTamanho($file['size']);
        $Arquivo->setError($file['error']);
        $Arquivo->setTmpName($file['tmp_name']);
        */
    }
?>