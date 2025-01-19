<?php
    class OrderStatus{
        private int $idTT;
        private string $tenTT;

        function __construct($idTT, $tenTT)
        {
            $this->idTT = $idTT;
            $this->tenTT = $tenTT;   
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT * FROM trangthaidh';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $orderstatus = new OrderStatus($item['idTT'], $item['tenTT']);
                $list[] = $orderstatus->toArray();
            }
            return $list;
        }

        static function findByID($idTT){
            $sql = 'SELECT * FROM trangthaidh
            WHERE idTT = '.$idTT;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req != null)
                return new OrderStatus($req['idTT'], $req['tenTT']);
            
            return null;
        }

        function toArray(){
            return [
                'idTT' => $this->idTT,
                'tenTT' => $this->tenTT
            ];
        }

        function getTenTT(){
            return $this->tenTT;
        }
    }
?>