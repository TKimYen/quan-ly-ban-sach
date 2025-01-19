<?php
    class City{
        private int $idTinh;
        private string $tenTinh;

        function __construct()
        {
            $this->idTinh = 0;
            $this->tenTinh = '';
        }

        function nhap($idTinh, $tenTinh){
            $this->idTinh = $idTinh;
            $this->tenTinh = $tenTinh;
        }

        static function findByID($idTinh){
            $sql = 'SELECT * FROM tinh WHERE idTinh='.$idTinh;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $city = new self();
                $city->nhap($req['idTinh'], $req['tenTinh']);
                return $city;
            }
            return null;
        }

        static function find(string $tenTinh){
            $sql = 'SELECT * FROM tinh WHERE tenTinh LIKE "%'.$tenTinh.'%"';
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $city = new self();
                $city->nhap($req['idTinh'], $req['tenTinh']);
                return $city;
            }
            return null;
        }

        static function getAll(){
            $list = [];
            $sql = "SELECT * FROM tinh ORDER BY tenTinh ASC";
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $city = new self();
                $city->nhap($item['idTinh'], $item['tenTinh']);
                $list[] = $city->toArray();
            }
            return $list;
        }

        function toArray(){
            return [
                'idTinh' => $this->idTinh,
                'tenTinh' => $this->tenTinh
            ];
        }

        function setIdTinh($idTinh){
            $this->idTinh = $idTinh;
        }

        function getIdTinh(){
            return $this->idTinh;
        }

        function getTentinh(){
            return $this->tenTinh;
        }
    }
?>