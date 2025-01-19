<?php
    class Permission{
        private int $idCN;
        private string $tenCN;

        function __construct($idCN, $tenCN)
        {
            $this->idCN = $idCN;
            $this->tenCN = $tenCN;
        }

        static function findByName($tenCN){
            $sql = 'SELECT * FROM chucnang WHERE tenCN ="'.$tenCN.'"';
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null) return new self($req['idCN'], $req['tenCN']);
            return null;
        }
        
        function toArray() {
            return [
                'idCN' => $this->idCN,
                'tenCN' => $this->tenCN,
            ];
        }

        function getIdCN(){
            return $this->idCN;
        }

    }
?>