<?php
    class GRNDetail{
        private int $idSach;
        private int $idPN;
        private int $soluong;

        function nhap($idPN, $idSach, $soluong){
            $this->idSach = $idSach;
            $this->idPN = $idPN;
            $this->soluong = $soluong;
        }

        function addCTPhieunhap(){
            $sql='INSERT INTO ctphieunhap(idPN, idSach, soluong) 
        VALUES ('.$this->idPN.','.$this->idSach.','.$this->soluong.')';
        $con = new Database();
        $con->execute($sql);
        }

        static function findByGRN($idPN){
            $list = [];
            $sql = 'SELECT * FROM ctphieunhap
            WHERE idPN = '.$idPN;
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $detail = new GRNDetail();
                $detail->nhap($item['idPN'], $item['idSach'], $item['soluong']);
                $list[] = $detail->toArray();
            }
            return $list;
        }

        function updateCTPhieuNhap(){
            $sql = 'UPDATE ctphieunhap
            SET soluong = '.$this->soluong.'
            WHERE idPN = '.$this->idPN.'
            AND idSach = '.$this->idSach;
            $con = new Database();
            $con->execute($sql);
        }

        function toArray(){
            return [
                'idSach' => $this->idSach,
                'idPN' => $this->idPN,
                'soluong' => $this->soluong
            ];
        }
    }
?>