<?php
    class District{
        private int $idQuan;
        private string $tenQuan;

        function __construct(){
            $this->idQuan = 0;
            $this->tenQuan = '';
        }

        function nhap(int $idQuan, string $tenQuan){
            $this->idQuan = $idQuan;
            $this->tenQuan = $tenQuan;
        }

        static function findByID($idQuan){
            $sql = 'SELECT * FROM quan WHERE idQuan='.$idQuan;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $district = new self();
                $district->nhap($req['idQuan'], $req['tenQuan']);
                return $district;
            }
            return null;
        }

        static function find($tenQuan, $idTinh){
            $sql = 'SELECT * FROM quan WHERE tenQuan LIKE "%'.$tenQuan.'%" AND idTinh = '.$idTinh;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $district = new self();
                $district->nhap($req['idQuan'], $req['tenQuan']);
                return $district;
            }
            return null;
        }

        static function getAllByCity(int $idTinh){
            $list = [];
            $sql = 'SELECT * FROM quan 
            WHERE idTinh = '.$idTinh.'
            ORDER BY tenQuan ASC';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $district = new self();
                $district->nhap($item['idQuan'], $item['tenQuan']);
                $list[] = $district->toArray();
            }
            return $list;
        }

        function toArray(){
            return [
                'idQuan' => $this->idQuan,
                'tenQuan' => $this->tenQuan
            ];
        }

        function setIdQuan($idQuan){
            $this->idQuan = $idQuan;
        }

        function getIdQuan(){
            return $this->idQuan;
        }

        function getTenQuan(){
            return $this->tenQuan;
        }
    }
?>