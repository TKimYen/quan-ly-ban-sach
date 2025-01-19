<?php
    class Discount{
        private int $idMGG;
        private float $phantram;
        private string $ngaybatdau;
        private string $ngayketthuc;
        private string $trangthai;

        function nhap(float $phantram, string $ngaybatdau, string $ngayketthuc, string $trangthai = 'cdr', int $idMGG=0){
            $this->idMGG = $idMGG;
            $this->phantram = $phantram;
            $this->ngaybatdau = $ngaybatdau;
            $this->ngayketthuc = $ngayketthuc;
            $this->trangthai = $trangthai;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT * FROM magiamgia';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $discount = new Discount();
                $discount->nhap($item['phantram'], $item['ngaybatdau'], $item['ngayketthuc'], $item['trangthai'], $item['idMGG']);
                $list[] = $discount;
            }
            return $list;
        }

        static function getAllWaiting(){
            $list = [];
            $sql = 'SELECT * FROM magiamgia WHERE trangthai = "cdr"';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $discount = new Discount();
                $discount->nhap($item['phantram'], $item['ngaybatdau'], $item['ngayketthuc'], $item['trangthai'], $item['idMGG']);
                $list[] = $discount;
            }
            return $list;
        }

        static function isExist(int $idMGG, float $phantram, string $ngaybatdau, string $ngayketthuc){
            $sql = 'SELECT idMGG FROM magiamgia WHERE phantram= '.$phantram.' AND ngaybatdau= "'.$ngaybatdau.'" AND ngayketthuc= "'.$ngayketthuc.'" AND trangthai!="huy"';
            if($idMGG!=0) $sql.=' AND idMGG!='.$idMGG;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID($idMGG){
            $sql = 'SELECT * FROM magiamgia WHERE idMGG='.$idMGG;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $discount = new Discount();
                $discount->nhap($req['phantram'], $req['ngaybatdau'], $req['ngayketthuc'], $req['trangthai'], $req['idMGG']);
                return $discount;
            }
            return null;
        }

        static function search($kyw){
            $sql = 'SELECT DISTINCT idMGG, phantram, ngaybatdau, ngayketthuc, trangthai
                    FROM magiamgia
                    WHERE 1';
            if($kyw != NULL) $sql .= ' AND (idMGG LIKE "%'.$kyw.'%" OR phantram LIKE "%'.$kyw.'%")';
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $discount = new Discount();
                $discount->nhap($item['phantram'], $item['ngaybatdau'], $item['ngayketthuc'], $item['trangthai'], $item['idMGG']);
                $list[] = $discount;
            }
            return $list;
        }

        function add(){
            if(!(Discount::isExist($this->idMGG, $this->phantram, $this->ngaybatdau, $this->ngayketthuc))){
                $sql='INSERT INTO magiamgia(phantram, ngaybatdau, ngayketthuc, trangthai) values ('.$this->phantram.',"'.$this->ngaybatdau.'","'.$this->ngayketthuc.'","'.$this->trangthai.'")';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(Discount::isExist($this->idMGG, $this->phantram, $this->ngaybatdau, $this->ngayketthuc))){
                $sql = 
                'UPDATE magiamgia 
                SET phantram = '.$this->phantram.',
                ngaybatdau = "'.$this->ngaybatdau.'",
                ngayketthuc = "'.$this->ngayketthuc.'",
                trangthai = "'.$this->trangthai.'"
                WHERE idMGG = '.$this->idMGG;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function lock(){
            $sql = 'UPDATE magiamgia SET trangthai = "'.$this->trangthai.'" WHERE idMGG = '.$this->idMGG;
            $con = new Database();
            $con->execute($sql);
        }
        
        function toArray() {
            return [
                'idMGG' => $this->idMGG,
                'phantram' => $this->phantram,
                'ngaybatdau' => $this->ngaybatdau,
                'ngayketthuc' => $this->ngayketthuc,
                'trangthai' => $this->trangthai
            ];
        }

        function setIdMGG($idMGG){
            $this->idMGG = $idMGG;
        }

        function setPhantram($phantram){
            $this->phantram = $phantram;
        }

        function setNgaybatdau($ngaybatdau){
            $this->ngaybatdau = $ngaybatdau;
        }

        function setNgayketthuc($ngayketthuc){
            $this->ngayketthuc = $ngayketthuc;
        }

        function setTrangthai($trangthai){
            $this->trangthai = $trangthai;
        }

        function getIdMGG(){
            return $this->idMGG;
        }

        function getPhantram(){
            return $this->phantram;
        }

        function getNgaybatdau(){
            return $this->ngaybatdau;
        }

        function getNgayKetthuc(){
            return $this->ngayketthuc;
        }

        function getTrangthai(){
            return $this->trangthai;
        }

    }
?>