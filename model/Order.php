<?php
class Order
{
    private int $idDH;
    private float $tamtinh;
    private int $idTT;
    private float $phiship;
    private string $diachi;
    private string $ngaytao;
    private string $ngaycapnhat;
    private int $idTK;
    private ?int $idNV;
    private string $phuong_thuc_tt;

    function __construct(
        int $idDH = 0,
        float $tamtinh = 0,
        int $idTT = 0,
        float $phiship = 0,
        string $ngaytao = '',
        string $ngaycapnhat = '',
        int $idTK = 0,
        ?int $idNV = null,
        string $diachi = '',
        string $phuong_thuc_tt = ''
    ) {
        $this->idDH = $idDH;
        $this->tamtinh = $tamtinh;
        $this->idTT = $idTT;
        $this->phiship = $phiship;
        $this->diachi = $diachi;
        $this->ngaytao = $ngaytao;
        $this->ngaycapnhat = $ngaycapnhat;
        $this->idTK = $idTK;
        $this->idNV = $idNV;
        $this->phuong_thuc_tt = $phuong_thuc_tt;
    }

    static function getAll()
    {
        $list = [];
        $sql = 'SELECT DISTINCT * FROM donhang';
        $con = new Database();
        $req = $con->getAll($sql);

        foreach ($req as $item) {
            $order = new Order(
                $item['idDH'],
                $item['tamtinh'],
                $item['idTT'],
                $item['phiship'],
                $item['ngaytao'],
                $item['ngaycapnhat'],
                $item['idTK'],
                $item['idNV'],
                $item['diachi'],
                $item['phuong_thuc_tt']
            );
            $list[] = $order;
        }
        return $list;
    }

    static function findByID($idDH)
    {
        $sql = 'SELECT * FROM donhang
            INNER JOIN trangthaidh ON donhang.idTT = trangthaidh.idTT
            WHERE idDH = ' . $idDH;
        $con = new Database();
        $req = $con->getOne($sql);
        if ($req != null) {
            $order = new Order(
                $req['idDH'],
                $req['tamtinh'],
                $req['idTT'],
                $req['phiship'],
                $req['ngaytao'],
                $req['ngaycapnhat'],
                $req['idTK'],
                $req['idNV'],
                $req['diachi'],
                $req['phuong_thuc_tt']
            );
            return $order;
        }
        return null;
    }

    static function getAllOrdersByIdTK($idTK){
        $sql=
            'SELECT
            donhang.idDH AS idDonHang,
            tenTT AS trangthaiDH,
            tamtinh,
            phiship,
            hinhanh, 
            tuasach, 
            soluong, 
            gialucdat, 
            COUNT(DISTINCT ctdonhang.idSach) AS tongsoluong
            FROM donhang INNER JOIN CTdonhang ON donhang.idDH = CTdonhang.idDH
            INNER JOIN sach ON CTdonhang.idSach = sach.idSach 
            INNER JOIN trangthaidh ON trangthaidh.idTT = donhang.idTT
            WHERE idTK = '.$idTK.'
            GROUP BY donhang.idDH, tenTT, tamtinh, phiship';
        $con = new Database();
        return $con->getAll($sql);
    }

    function update($ngaycapnhat, $idNV, $trangthai){
        $sql = 'UPDATE donhang 
        SET ngaycapnhat = "'.$ngaycapnhat.'",
        idNV = '.$idNV.',
        idTT = '.$trangthai.'
        WHERE idDH ='.$this->idDH;
        $con = new Database();
        $con->execute($sql);
    }

    static function getLastestId()
    {
        $sql = 'SELECT MAX(idDH) as idDH FROM donhang';
        $con = new Database();
        $req = $con->getOne($sql);
        return $req['idDH'];
    }

    function saveOrder()
    {
        $sql = "INSERT INTO donhang (
                    tamtinh, idTT, phiship, diachi, ngaytao, ngaycapnhat, idTK, idNV, phuong_thuc_tt
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $con = new Database();
        $stmt = $con->getLink()->prepare($sql);
        $stmt->bind_param(
            "didsssiis",
            $this->tamtinh,
            $this->idTT,
            $this->phiship,
            $this->diachi,
            $this->ngaytao,
            $this->ngaycapnhat,
            $this->idTK,
            $this->idNV,
            $this->phuong_thuc_tt
        );

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Lỗi khi lưu đơn hàng: " . $stmt->error);
        }
    }

    function cancel_order(){
        $sql = 'UPDATE donhang 
        SET idTT = 2
        WHERE idDH = '.$this->idDH;
        $con = new Database();
        $con->execute($sql);
    }

    function toArray()
    {
        return [
            'idDH' => $this->idDH,
            'tamtinh' => $this->tamtinh,
            'idTT' => $this->idTT,
            'phiship' => $this->phiship,
            'diachi' => $this->diachi,
            'ngaytao' => $this->ngaytao,
            'ngaycapnhat' => $this->ngaycapnhat,
            'idTK' => $this->idTK,
            'idNV' => $this->idNV,
            'phuong_thuc_tt' => $this->phuong_thuc_tt,
            'tongtien' => $this->getTongtien()
        ];
    }

    static function search($kyw, $status_select, $date_start, $date_end) {
        $sql = 'SELECT DISTINCT idDH, tamtinh, donhang.idTT, phiship, ngaytao, ngaycapnhat, donhang.idTK, idNV, diachi, taikhoan.tenTK, tenTT, phuong_thuc_tt
            FROM donhang
                INNER JOIN taikhoan on donhang.idTK = taikhoan.idTK
                INNER JOIN trangthaidh on donhang.idTT = trangthaidh.idTT
            WHERE 1';
        if($kyw != NULL) $sql .= ' AND (idDH LIKE "%'.$kyw.'%" OR tenTK LIKE "%'.$kyw.'%")';
        if($status_select != NULL) $sql .= ' AND donhang.idTT = '.$status_select;
        if($date_start != "" && $date_end != "")
            $sql .= ' AND (donhang.ngaytao BETWEEN "'.$date_start.'" AND "'.$date_end.'")';
        else if ($date_start != NULL) {
            $sql .= ' AND donhang.ngaytao >= '.$date_start;
        } else if ($date_end != NULL) {
            $sql .= ' AND donhang.ngaytao <= '.$date_end;
        }
        $list = [];
        $con = new Database();
        $req = $con->getAll($sql);
        foreach($req as $item){
            $order = new Order(
                $item['idDH'],
                $item['tamtinh'],
                $item['idTT'],
                $item['phiship'],
                $item['ngaytao'],
                $item['ngaycapnhat'],
                $item['idTK'],
                $item['idNV'],
                $item['diachi'],
                $item['phuong_thuc_tt']
            );
            $list[] = $order;
        }
        return $list;
    }

    // Getters and Setters
    public function getIdDH()
    {
        return $this->idDH;
    }

    public function setIdDH($idDH)
    {
        $this->idDH = $idDH;
        return $this;
    }

    public function getTamtinh()
    {
        return $this->tamtinh;
    }

    public function setTamtinh($tamtinh)
    {
        $this->tamtinh = $tamtinh;
        return $this;
    }

    public function getPhiship()
    {
        return $this->phiship;
    }

    public function setPhiship($phiship)
    {
        $this->phiship = $phiship;
        return $this;
    }

    public function getDiachi()
    {
        return $this->diachi;
    }

    public function setDiachi($diachi)
    {
        $this->diachi = $diachi;
        return $this;
    }

    public function getNgaytao()
    {
        return $this->ngaytao;
    }

    public function setNgaytao($ngaytao)
    {
        $this->ngaytao = $ngaytao;
        return $this;
    }

    public function getNgaycapnhat()
    {
        return $this->ngaycapnhat;
    }

    public function setNgaycapnhat($ngaycapnhat)
    {
        $this->ngaycapnhat = $ngaycapnhat;
        return $this;
    }

    public function getIdTK()
    {
        return $this->idTK;
    }

    public function setIdTK($idTK)
    {
        $this->idTK = $idTK;
        return $this;
    }

    public function getIdNV()
    {
        return $this->idNV;
    }

    public function setIdNV($idNV)
    {
        $this->idNV = $idNV;
        return $this;
    }

    public function getPhuong_thuc_tt()
    {
        return $this->phuong_thuc_tt;
    }

    public function setPhuong_thuc_tt($phuong_thuc_tt)
    {
        $this->phuong_thuc_tt = $phuong_thuc_tt;
        return $this;
    }

    public function getIdTT()
    {
        return $this->idTT;
    }

    public function setIdTT($idTT)
    {
        $this->idTT = $idTT;
        return $this;
    }

    public function getTongtien(){
        return $this->tamtinh + $this->phiship;
    }
}
