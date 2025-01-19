<?php
    class Category{
        private int $idTL;
        private string $tenTL;
        private int $trangthai;

        function nhap($tenTL, $trangthai, $idTL=0){
            $this->idTL = $idTL;
            $this->tenTL = $tenTL;
            $this->trangthai = $trangthai;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT * FROM theloai';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $category = new Category();
                $category->nhap($item['tenTL'], $item['trangthai'], $item['idTL']);
                $list[] = $category;
            }
            return $list;
        }

        static function getAllActive(){
            $list = [];
            $sql = 'SELECT DISTINCT * FROM theloai 
            INNER JOIN sach ON theloai.idTL = sach.idTL
            WHERE theloai.trangthai = 1
                AND sach.tonkho > 0
            GROUP BY theloai.idTL';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $category = new Category();
                $category->nhap($item['tenTL'], $item['trangthai'], $item['idTL']);
                $list[] = $category;
            }
            return $list;
        }

        static function isExist($idTL, $tenTL){
            $sql = 'SELECT idTL FROM theloai WHERE tenTL= "'.$tenTL.'"';
            if($idTL!=0) $sql.=' AND idTL!='.$idTL;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID($idTL){
            $sql = 'SELECT * FROM theloai WHERE idTL='.$idTL;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $category = new Category();
                $category->nhap($req['tenTL'], $req['trangthai'], $req['idTL']);
                return $category;
            }
            return null;
        }

        function add(){
            if(!(Category::isExist($this->idTL, $this->tenTL))){
                $sql = 'INSERT INTO theloai(tenTL, trangthai) VALUES ("'.$this->tenTL.'", '.$this->trangthai.')';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(Category::isExist($this->idTL, $this->tenTL))){
                $sql = 'UPDATE theloai
                    SET tenTL = "'.$this->tenTL.'", trangthai = '.$this->trangthai.'
                    WHERE idTL = '.$this->idTL;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        static function search($kyw){
            $sql = 'SELECT idTL, tenTL, trangthai
                FROM theloai
                WHERE 1';
            if($kyw != NULL) $sql .= ' AND (idTL LIKE "%'.$kyw.'%" OR tenTL LIKE "%'.$kyw.'%")';
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $cat = new Category();
                $cat->nhap($item['tenTL'], $item['trangthai'], $item['idTL']);
                $list[] = $cat;
            }
            return $list;
        }

        static function getCategoryByIdBook($idSach) {
            $sql = 'SELECT theloai.idTL, tenTL, theloai.trangthai
                    FROM theloai
                        INNER JOIN sach on theloai.idTL = sach.idTL
                    WHERE sach.idSach='.$idSach.' AND theloai.trangthai=1';
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $category = new self();
                $category->nhap($req['tenTL'], $req['trangthai'], $req['idTL']);
                return $category;
            }
            return null;
        }
        
        function toArray() {
            return [
                'idTL' => $this->idTL,
                'tenTL' => $this->tenTL,
                'trangthai' => $this->trangthai
            ];
        }

        function setIdTL($idTL){
            $this->idTL = $idTL;
        }

        function setTenTL($tenTL){
            $this->tenTL = $tenTL;
        }

        function setTrangthai($trangthai){
            $this->trangthai = $trangthai;
        }

        function getIdTL(){
            return $this->idTL;
        }

        function getTenTL(){
            return $this->tenTL;
        }

        function getTrangthai(){
            return $this->trangthai;
        }

    }
?>