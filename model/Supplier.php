<?php
include __DIR__.'/City.php';
include __DIR__.'/District.php';
include __DIR__.'/Ward.php';

    class Supplier{
        private int $idNCC;
        private string $tenNCC;
        private string $diachi;
        private string $email;
        private string $dienthoai;
        private int $trangthai;

        function __construct(){
            $this->idNCC = 0;
            $this->tenNCC = '';
            $this->diachi = '';
            $this->email = '';
            $this->dienthoai = '';
            $this->trangthai = 0;
        }

        function nhap(int $idNCC, string $tenNCC, string $diachi, string $email, string $dienthoai, int $trangthai){
            $this->idNCC = $idNCC;
            $this->tenNCC = $tenNCC;
            $this->diachi = $diachi;
            $this->email = $email;
            $this->dienthoai = $dienthoai;
            $this->trangthai = $trangthai;
        }

        static function getAll(){
            $list = [];
            $sql = $sql = "SELECT * FROM nhacungcap";
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $supplier = new self();
                $supplier->nhap($item['idNCC'], $item['tenNCC'], $item['diachi'], $item['email'], $item['dienthoai'], $item['trangthai']);
                $list[] = $supplier;
            }
            return $list;
        }

        static function getAllActive(){
            $list = [];
            $sql = $sql = "SELECT * FROM nhacungcap WHERE trangthai = 1";
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $supplier = new self();
                $supplier->nhap($item['idNCC'], $item['tenNCC'],$item['diachi'], $item['email'], $item['dienthoai'], $item['trangthai']);
                $list[] = $supplier;
            }
            return $list;
        }

        static function getAllActive_GRN(){
            $list = [];
            $sql = $sql = "SELECT DISTINCT ncc.*
            FROM nhacungcap AS ncc
            INNER JOIN sach ON ncc.idNCC = sach.idNCC
            WHERE ncc.trangthai = 1";
            $con = new Database();
            $req = $con->getAll($sql);

            foreach($req as $item){
                $supplier = new self();
                $supplier->nhap($item['idNCC'], $item['tenNCC'],$item['diachi'], $item['email'], $item['dienthoai'], $item['trangthai']);
                $list[] = $supplier;
            }
            return $list;
        }

        static function isExist($idNCC, $ten, $email, $dienthoai){
            $sql = '';
            if($idNCC!=null)
                $sql = 'SELECT idNCC FROM nhacungcap 
            WHERE (tenNCC = "'.$ten.'" or email= "'.$email.'" or dienthoai= "'.$dienthoai.'") AND idNCC != "'.$idNCC.'"';
            else $sql = 'SELECT idNCC FROM nhacungcap 
            WHERE tenNCC = "'.$ten.'" or email= "'.$email.'" or dienthoai= "'.$dienthoai.'"';
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID($idNCC){
            $sql = 'SELECT * FROM nhacungcap WHERE idNCC='.$idNCC;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $supplier = new self();
                $supplier->nhap($req['idNCC'], $req['tenNCC'], $req['diachi'], $req['email'], $req['dienthoai'], $req['trangthai'], $req['diachi']);
                return $supplier;
            }
            return null;
        }

        static function findByIdSach($idSach){
            $sql = 'SELECT * FROM nhacungcap 
            INNER JOIN sach ON nhacungcap.idNCC = sach.idNCC
            WHERE idSach = '.$idSach.'
            LIMIT 1';
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $supplier = new self();
                $supplier->nhap($req['idNCC'], $req['tenNCC'], $req['diachi'], $req['email'], $req['dienthoai'], $req['trangthai'], $req['diachi']);
                return $supplier;
            }
            return null;
        }

        function add(){
            if(!(self::isExist($this->idNCC, $this->tenNCC, $this->email, $this->dienthoai))){
                $sql='INSERT INTO nhacungcap(tenNCC, email, dienthoai, diachi, trangthai) VALUES ("'.$this->tenNCC.'","'.$this->email.'","'.$this->dienthoai.'","'.$this->diachi.'",'.$this->trangthai.')';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(self::isExist($this->idNCC, $this->tenNCC, $this->email, $this->dienthoai))){
                $sql = 'UPDATE nhacungcap 
                SET tenNCC = "'.$this->tenNCC.'", email = "'.$this->email.'", dienthoai = "'.$this->dienthoai.'", diachi = "'.$this->diachi.'",trangthai='.$this->trangthai.'
                WHERE idNCC = '.$this->idNCC;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function convertToShow(){
            $diachi = explode(",", $this->diachi);
            $city = City::find(trim($diachi[3]));
            $district = District::find($diachi[2], $city->getIdTinh());
            $ward = Ward::find($diachi[1], $district->getIdQuan());
            $sonha = $this->setSonha($diachi[0]);
            return [
                'idTinh' => $city->getIdTinh(),
                'idQuan' => $district->getIdQuan(),
                'idXa' => $ward->getIdXa(),
                'sonha' => $sonha
            ];
        }
        
        function toArray() {
            return [
                'idNCC' => $this->idNCC,
                'tenNCC' => $this->tenNCC,
                'email' => $this->email,
                'dienthoai' => $this->dienthoai,
                'diachi' => $this->diachi,
                'trangthai' => $this->trangthai
            ];
        }

        static function search($kyw, $status_select, $sort) {
            $sql = 'SELECT idNCC, tenNCC, diachi, email, dienthoai, trangthai 
                FROM nhacungcap 
                WHERE 1';
            if($kyw != NULL) $sql .= ' AND (idNCC LIKE "%'.$kyw.'%" OR tenNCC LIKE "%'.$kyw.'%")';
            if($status_select != NULL) $sql .= ' AND trangthai = '.$status_select;
            if ($sort == 'AZ') {
                $sql .= " ORDER BY tenNCC COLLATE utf8mb4_unicode_ci ASC";
            } else if ($sort == 'ZA') {
                $sql .= " ORDER BY tenNCC COLLATE utf8mb4_unicode_ci DESC";
            }

            $list = []; 
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $supp = new Supplier();
                $supp->nhap($item['idNCC'], $item['tenNCC'], $item['diachi'], $item['email'], $item['dienthoai'], $item['trangthai']);
                $list[] = $supp;
            }
            return $list;
        }

        function getIdNCC(){
            return $this->idNCC;
        }

        function getTenNCC(){
            return $this->tenNCC;
        }

        function getEmail(){
            return $this->email;
        }

        function getDienthoai(){
            return $this->dienthoai;
        }

        function getTrangthai(){
            return $this->trangthai;
        }

        function setTenNCC(string $tenNCC){
            $this->tenNCC= $tenNCC;
        }

        function setEmail(string $email){
            $this->email = $email;
        }

        function setDienthoai(string $dienthoai){
            $this->dienthoai = $dienthoai;
        }

        function setTrangthai(int $trangthai){
            $this->trangthai = $trangthai;
        }

        function setDiachi($sonha, $idTinh, $idQuan, $idXa){
            $sonha = $this->setSonha($sonha);
            $tinh =  City::findByID($idTinh);
            $tenTinh = $tinh->getTentinh();
            $quan = District::findByID($idQuan);
            $tenQuan = $quan->getTenquan();
            $xa = Ward::findByID($idXa);
            $tenXa = $xa->getTenxa();
            $this->diachi = $sonha.','.$tenXa.','.$tenQuan.','.$tenTinh;
        }

        private function setSonha($sonha){
            $commaPos = strpos($sonha, ',');
            return $commaPos !== false ? substr($sonha, 0, $commaPos) : $sonha;
        }
    }
?>