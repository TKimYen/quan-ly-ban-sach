<?php
    class Author{
        private int $idTG;
        private string $tenTG;
        private string $email;
        private int $trangthai;

        function nhap($tenTG, $email, $trangthai, $idTG = 0){
            $this->idTG = $idTG;
            $this->tenTG = $tenTG;
            $this->email = $email;
            $this->trangthai = $trangthai;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT * FROM tacgia';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $author = new self();
                $author->nhap($item['tenTG'], $item['email'], $item['trangthai'], $item['idTG']);
                $list[] = $author;
            }
            return $list;
        }

        static function getAllActive(){
            $list = [];
            $sql = 'SELECT * FROM tacgia WHERE trangthai = 1';
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $author = new self();
                $author->nhap($item['tenTG'], $item['email'], $item['trangthai'], $item['idTG']);
                $list[] = $author;
            }
            return $list;
        }

        static function isExist($idTG, $email){
            $sql = 'SELECT idTG FROM tacgia WHERE email= "'.$email.'"';
            if($idTG!=null) $sql.=' AND idTG!='.$idTG;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID($idTG){
            $sql = 'SELECT * FROM tacgia WHERE idTG='.$idTG;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $author = new Author();
                $author->nhap($req['tenTG'], $req['email'], $req['trangthai'], $req['idTG']);
                return $author;
            }
            return null;
        }

        function add(){
            if(!(Author::isExist($this->idTG, $this->email))){
                $sql = 'INSERT INTO tacgia(tenTG, email, trangthai) VALUES ("'.$this->tenTG.'", "'.$this->email.'", '.$this->trangthai.')';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(Author::isExist($this->idTG, $this->email))){
                $sql = 'UPDATE tacgia
                    SET tenTG = "'.$this->tenTG.'", 
                    email = "'.$this->email.'",
                    trangthai = '.$this->trangthai.'
                    WHERE idTG = '.$this->idTG;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        static function search($kyw){
            $sql = 'SELECT DISTINCT idTG, tenTG, email, trangthai
            FROM tacgia
            WHERE 1';
            if($kyw != NULL) $sql .= ' AND (idTG LIKE "%'.$kyw.'%" OR tenTG LIKE "%'.$kyw.'%" OR email LIKE "%'.$kyw.'%")';
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $author = new self();
                $author->nhap($item['tenTG'], $item['email'], $item['trangthai'], $item['idTG']);
                $list[] = $author;
            }
            return $list;
        }
        
        function toArray() {
            return [
                'idTG' => $this->idTG,
                'tenTG' => $this->tenTG,
                'email' => $this->email,
                'trangthai' => $this->trangthai
            ];
        }

        function setIdTG($idTG){
            $this->idTG = $idTG;
        }

        function setTenTG($tenTG){
            $this->tenTG = $tenTG;
        }

        function setTrangthai($trangthai){
            $this->trangthai = $trangthai;
        }

        function getIdTG(){
            return $this->idTG;
        }

        function getTenTG(){
            return $this->tenTG;
        }

        function getTrangthai(){
            return $this->trangthai;
        }

        function getEmail(){
            return $this->email;
        }

    }
?>