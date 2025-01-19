<?php
    class Account{
        private ?int $idTK;
        private string $tenTK;
        private string $dienthoai;
        private string $email;
        private ?string $matkhau;
        private int $trangthai;
        private int $idNQ;

        function __construct(){
            $this->idTK=0;
            $this->tenTK = '';
            $this->dienthoai = '';
            $this->email = '';
            $this->matkhau = '';
            $this->trangthai = 0;
            $this->idNQ = 0;
        }

        function nhap(string $tenTK, string $dienthoai, string $email, string $matkhau = NULL, int $trangthai, $idNQ, int $idTK = 0){
            $this->idTK = $idTK;
            $this->tenTK = $tenTK;
            $this->dienthoai = $dienthoai;
            $this->email = $email;
            $this->matkhau = $matkhau;
            $this->trangthai = $trangthai;
            $this->idNQ = $idNQ;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT idTK, tenTK, email, matkhau, dienthoai, taikhoan.idNQ AS idNQ, tenNQ, nhomquyen.trangthai AS trangthaiNQ, taikhoan.trangthai AS trangthai FROM taikhoan 
            LEFT JOIN nhomquyen ON taikhoan.idNQ = nhomquyen.idNQ';
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $account = new self();
                $account->nhap($item['tenTK'], $item['dienthoai'], $item['email'], $item['matkhau'], $item['trangthai'], $item['idNQ'], $item['idTK']);
                $list[] = [
                    'account' => $account->toArray(),
                    'tenNQ' => $item['tenNQ']
                ];
            }
            return $list;
        }

        static function isExist(int $idTK, string $email){
            $sql = 'SELECT idTK FROM taikhoan WHERE email = "' . $email . '"';
            if($idTK!=0) $sql.=' AND idTK!='.$idTK;
            $con = new Database();
            return ($con->getOne($sql))!=null;
        }

        static function findByID(int $idTK){
            $sql = 'SELECT * FROM taikhoan WHERE idTK='.$idTK;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $account = new self();
                $account->nhap($req['tenTK'], $req['dienthoai'], $req['email'], $req['matkhau'], $req['trangthai'], $req['idNQ'], $req['idTK']);
                return $account;
            }
            return null;
        }

        static function findByEmail($email){
            $sql = 'SELECT * FROM taikhoan WHERE email="'.$email.'"';
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $account = new self();
                $account->nhap($req['tenTK'], $req['dienthoai'], $req['email'], $req['matkhau'], $req['trangthai'], $req['idNQ'], $req['idTK']);
                return $account;
            }
            return null;
        }

        static function search($kyw, $idNQ, $trangthai){
            $sql = 'SELECT idTK, tenTK, email, matkhau, dienthoai, taikhoan.idNQ AS idNQ, tenNQ, nhomquyen.trangthai AS trangthaiNQ, taikhoan.trangthai AS trangthai
                FROM taikhoan
                    LEFT JOIN nhomquyen ON taikhoan.idNQ = nhomquyen.idNQ
                WHERE 1';
            if($kyw != NULL)  $sql .= ' AND (idTK LIKE "%'.$kyw.'%" OR tenTK LIKE "%'.$kyw.'%")';
            if($idNQ != NULL)  $sql .= ' AND taikhoan.idNQ = '.$idNQ;
            if($trangthai != NULL) $sql .= ' AND taikhoan.trangthai = '.$trangthai;
            $list = [];
            $con = new Database();
            $req = $con->getAll($sql);
            foreach($req as $item){
                $account = new self();
                $account->nhap($item['tenTK'], $item['dienthoai'], $item['email'], $item['matkhau'], $item['trangthai'], $item['idNQ'], $item['idTK']);
                $list[] = [
                    'account' => $account->toArray(),
                    'tenNQ' => $item['tenNQ']
                ];
            }
            return $list;
        }

        function add(){
            if(!(self::isExist($this->idTK, $this->email))){
                $sql = 'INSERT INTO taikhoan (tenTK, email, matkhau, dienthoai, trangthai, idNQ) 
                VALUES ("' . $this->tenTK . '", "' . $this->email . '", "' . $this->matkhau . '", "' . $this->dienthoai . '", ' . $this->trangthai . ', ' . $this->idNQ . ')';
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }

        function update(){
            if(!(self::isExist($this->idTK, $this->email))){
                $sql = 'UPDATE taikhoan 
                    SET tenTK = "' . $this->tenTK . '", 
                        dienthoai = "' . $this->dienthoai . '", 
                        email = "' . $this->email . '",
                        trangthai = ' . $this->trangthai . ', 
                        idNQ = ' . $this->idNQ . ' 
                    WHERE idTK = ' . $this->idTK;
                $con = new Database();
                $con->execute($sql);
                return true;
            }
            return false;
        }
        
        function toArray() {
            return [
                'idTK' => $this->idTK,
                'tenTK' => $this->tenTK,
                'matkhau' => $this->matkhau,
                'dienthoai' => $this->dienthoai,
                'email' => $this->email,
                'trangthai' => $this->trangthai,
                'idNQ' => $this->idNQ
            ];
        }

        // Của Híuuu - Hàm cập nhật thông tin và hàm cập nhật mật khẩu cho CustomerInfoController.php
        function updateAccountInfo() {
            if(!(self::isExist($this->idTK, $this->email))){
                $fields = [];

                if ($this->tenTK != '') {
                    $fields[] = "tenTK = '" .$this->tenTK. "'";
                }

                if ($this->email != '') {
                    $fields[] = "email = '" .$this->email. "'";
                }

                if ($this->dienthoai != '') {
                    $fields[] = "dienthoai = '" .$this->dienthoai ."'";
                }

                if (empty($fields)) {
                    return true; // Không có gì để cập nhật
                } else {
                    $sql = "UPDATE taikhoan SET " . implode(",", $fields) . " WHERE idTK = ". $this->idTK;
                    $con = new Database();
                        $con->execute($sql);
                        return true;
                }
            }
            return false;
        }

    function updateAccountPassword() {
            $sql = "UPDATE taikhoan SET matkhau = '" .$this->matkhau. "' WHERE idTK = ". $this->idTK;
            $con = new Database();
            $con->execute($sql);
    }

    // Hết phần của Híuuu rồi nè
    
        function setIdTK(int $idTK){
            $this->idTK = $idTK;
        }

        function setTenTK(string $tenTK){
            $this->tenTK = $tenTK;
        }

        function setDienthoai(string $dienthoai){
            $this->dienthoai = $dienthoai;
        }

        function setEmail(string $email){
            $this->email = $email;
        }

        function setMatkhau(string $matkhau){
            $this->matkhau = $matkhau;
        }

        function setTrangthai(int $trangthai){
            $this->trangthai = $trangthai;
        }

        function setIdNQ(int $idNQ){
            $this->idNQ = $idNQ;
        }

        function getIdTK(){
            return $this->idTK;
        }

        function getTenTK(){
            return $this->tenTK;
        }

        function getDienthoai(){
            return $this->dienthoai;
        }

        function getEmail(){
            return $this->email;
        }

        function getTrangthai(){
            return $this->trangthai;
        }

        function getMatkhau(){
            return $this->matkhau;
        }

        function getIdNQ(){
            return $this->idNQ;
        }
    }
?>