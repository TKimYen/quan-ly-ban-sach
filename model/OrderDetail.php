<?php
    class OrderDetail{
        private int $idDH;
        private int $idSach;
        private int $soluong;
        private float $gialucdat;

        function __construct($idDH = 0, $idSach = 0, $soluong = 0, $gialucdat = 0)
    {
        $this->idDH = $idDH;
        $this->idSach = $idSach;
        $this->soluong = $soluong;
        $this->gialucdat = $gialucdat;
    }
    
        function nhap($idDH, $idSach, $soluong, $gialucdat){
            $this->idDH = $idDH;
            $this->idSach = $idSach;
            $this->soluong = $soluong;
            $this->gialucdat = $gialucdat;
        }

        static function findByOrder($idDH){
            $list = [];
            $sql = 'SELECT * FROM ctdonhang
            WHERE idDH = '.$idDH;
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $detail = new OrderDetail();
                $detail->nhap($item['idDH'], $item['idSach'], $item['soluong'], $item['gialucdat']);
                $list[] = $detail->toArray();
            }
            return $list;
        }

        function saveOrderDetail() {
            $sql = 'INSERT INTO ctdonhang (idDH, idSach, soluong, gialucdat)
                VALUES (' . $this->idDH . ', ' . $this->idSach . ', ' . $this->soluong . ', ' . $this->gialucdat . ')';
            $con = new Database();
            return $con->execute($sql);
        }

        function toArray(){
            return [
                'idDH' => $this->idDH,
                'idSach' => $this->idSach,
                'soluong' => $this->soluong,
                'gialucdat' => $this->gialucdat
            ];
        }

        function getIdSach(){
            return $this->idSach;
        }

    }
?>