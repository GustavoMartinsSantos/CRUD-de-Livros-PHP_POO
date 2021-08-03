<?php
    class Paginacao {
        public int $paginas;
        public int $paginaAtual; // receberá valor GET
        public int $limit;
        public int $resultados;

        private function getLimitByPage (): int {
            return $this->limit * $this->paginaAtual;
        }

        public function getResultsByPage (array $Rows) {
            $Results = array();
            
            for($c = $this->limit * ($this->paginaAtual - 1); $c < $this->getLimitByPage(); $c++) {
                if($c > (count($Rows) - 1))
                    break;
                $Results[] = $Rows[$c];
            }
            
            return $Results;
        }

        public function getLinkPages (): string {
            unset($_GET['pagina']);
            $gets = http_build_query($_GET);
    
            $linksPaginas = '';
            for($p = 1; $p <= $this->paginas; $p++) {
                if($this->paginas == 1)
                    break;
                $currentPage = $this->paginaAtual == $p ? "bg-danger" : 'bg-dark';
                $linksPaginas .= "<a href=?pagina=$p&$gets>
                                    <button class='btn $currentPage text-light'>$p</button>
                                </a>";
            }
    
            return $linksPaginas;
        }

        public function __construct (int $results, int $limit = 5, int $currentPage = 1) {
            $currentPage = is_numeric($currentPage) & $currentPage > 0 ? $currentPage : 1;

            $this->resultados = $results;
            $this->limit = $limit;
            $this->paginas = $this->resultados == 0 ? 1 : intval(ceil($this->resultados / $this->limit));
            $this->paginaAtual = $currentPage > $this->paginas ? $this->paginas : $currentPage;
        }
    }
?>