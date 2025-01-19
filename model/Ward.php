<?php
    class Ward{
        private int $idXa;
        private string $tenXa;

        function __construct()
        {
            $this->idXa = 0;
            $this->tenXa = '';
        }

        function nhap(int $idXa, string $tenXa){
            $this->idXa = $idXa;
            $this->tenXa = $tenXa;
        }
        
        static function findByID(int $idXa){
            $sql = 'SELECT * FROM xa WHERE idXa='.$idXa;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $ward = new self();
                $ward->nhap($req['idXa'], $req['tenXa']);
                return $ward;
            }
            return null;
        }

        static function find(string $tenXa, int $idQuan){
            $sql = 'SELECT * FROM xa WHERE tenXa LIKE "%'.$tenXa.'%" AND idQuan = '.$idQuan;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $ward = new self();
                $ward->nhap($req['idXa'], $req['tenXa']);
                return $ward;
            }
            return null;
        }

        static function getAllByDistrict(int $idQuan){
            $list = [];
            $sql = 'SELECT * 
            FROM xa WHERE idQuan = '.$idQuan.'
            ORDER BY tenXa ASC';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $ward = new self();
                $ward->nhap($item['idXa'], $item['tenXa']);
                $list[] = $ward->toArray();
            }
            return $list;
        }

        function toArray(){
            return [
                'idXa' => $this->idXa,
                'tenXa' => $this->tenXa
            ];
        }

        function getTenxa(){
            return $this->tenXa;
        }

        function getIdXa(){
            return $this->idXa;
        }
    }
?>