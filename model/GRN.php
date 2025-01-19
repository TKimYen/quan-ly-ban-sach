<?php
    class GRN{
        private int $idPN;
        private float $tongtien;
        private int $tongsoluong;
        private string $ngaytao;
        private string $ngaycapnhat;
        private string $trangthai;
        private float $chietkhau;
        private int $idNV;

        function __construct(int $idPN = 0, float $tongtien = 0, int $tongsoluong = 0, string $ngaytao = '', string $ngaycapnhat = '', string $trangthai = '', float $chietkhau = 0, int $idNV = 0){
            $this->idPN = $idPN;
            $this->tongtien = $tongtien;
            $this->tongsoluong = $tongsoluong;
            $this->ngaytao = $ngaytao ?: date('Y-m-d');
            $this->ngaycapnhat = $ngaycapnhat ?: date('Y-m-d');
            $this->trangthai = $trangthai;
            $this->chietkhau = $chietkhau;
            $this->idNV = $idNV;
        }

        function nhap(int $idPN, string $ngaytao, string $ngaycapnhat, float $tongtien, string $trangthai, int $idNV, float $chietkhau, int $tongsoluong){
            $this->idPN = $idPN;
            $this->ngaytao = $ngaytao;
            $this->ngaycapnhat = $ngaycapnhat;
            $this->trangthai = $trangthai;
            $this->idNV = $idNV;
            $this->tongtien = $tongtien;
            $this->chietkhau = $chietkhau;
            $this->tongsoluong = $tongsoluong;
        }

        static function getAll(){
            $list = [];
            $sql = 'SELECT DISTINCT * FROM phieunhap';
            $con = new Database();
            $req = $con->getAll($sql);


            foreach($req as $item){
                $grn = new self();
                $grn->nhap($item['idPN'], $item['ngaytao'], $item['ngaycapnhat'], $item['tongtien'], $item['trangthai'], $item['idNV'], $item['chietkhau'], $item['tongsoluong']);
                $list[] = $grn;
            }
            return $list;
        }

        function deletePhieuNhap(){
            $con = new Database();
            $sql = 'DELETE FROM ctphieunhap WHERE idPN = '.$this->idPN;
            $con->execute($sql);
            $sql = 'DELETE FROM phieunhap WHERE idPN = '.$this->idPN;
            $con->execute($sql);
        }

        function addNewPhieuNhapKho(){
            $sql='INSERT INTO phieunhap(tongtien, tongsoluong, ngaytao, trangthai, ngaycapnhat, idNV, chietkhau) 
            VALUE('.$this->tongtien.', '.$this->tongsoluong.', "'.$this->ngaytao.'", "'.$this->trangthai.'", "'.$this->ngaycapnhat.'", '.$this->idNV.', '.$this->chietkhau.')';
            $con = new Database();
            $con->execute($sql);
        }


        static function getLastPhieuNhapKhoID(){
            $sql = 'SELECT idPN
            FROM phieunhap
            ORDER BY idPN DESC
            LIMIT 1';
            $con = new Database();
            $req = $con->getOne($sql);
            return $req['idPN'];
        }


        function nhapUpdate($ngaycapnhat, $tongsoluong, $tongtien, $trangthai, $ngaytao='', $chietkhau=0){
            $this->ngaytao = $ngaytao;
            $this->ngaycapnhat = $ngaycapnhat;
            $this->trangthai = $trangthai;
            $this->tongtien = $tongtien;
            $this->chietkhau = $chietkhau;
            $this->tongsoluong = $tongsoluong;
        }


        function createPhieuNhapKho(){
            $sql = 'UPDATE phieunhap
            SET ngaytao = "'.$this->ngaytao.'",
            ngaycapnhat = "'.$this->ngaycapnhat.'",
            chietkhau = '.$this->chietkhau.',
            tongsoluong= '.$this->tongsoluong.',
            tongtien = '.$this->tongtien.',
            trangthai = "'.$this->trangthai.'"
            WHERE idPN = '.$this->idPN;
            $con = new Database();
            $con->execute($sql);
        }


        static function findByID($idPN){
            $sql = 'SELECT * FROM phieunhap WHERE idPN='.$idPN;
            $con = new Database();
            $req = $con->getOne($sql);
            if($req!=null){
                $grn = new GRN();
                $grn->nhap($req['idPN'], $req['ngaytao'], $req['ngaycapnhat'], $req['tongtien'], $req['trangthai'], $req['idNV'], $req['chietkhau'], $req['tongsoluong']);
                return $grn;
            }
            return null;
        }


        function update(){
            $sql = 'UPDATE phieunhap
            SET ngaycapnhat = "'.$this->ngaycapnhat.'",
            tongsoluong = '.$this->tongsoluong.',
            tongtien = '.$this->tongtien.',
            trangthai = "'.$this->trangthai.'"
            WHERE idPN = '.$this->idPN;
            $con = new Database();
            $con->execute($sql);
        }


        function toArray(){
            return [
                'idPN' => $this->idPN,
                'ngaytao' => $this->ngaytao,
                'ngaycapnhat' => $this->ngaycapnhat,
                'tongtien' => $this->tongtien,
                'trangthai' => $this->trangthai,
                'idNV' => $this->idNV,
                'chietkhau' => $this->chietkhau,
                'tongsoluong' => $this->tongsoluong
            ];
        }

static function search($kyw, $status_select, $date_start, $date_end)  {
    $sql = 'SELECT DISTINCT phieunhap.idPN, phieunhap.ngaytao, phieunhap.ngaycapnhat, tongtien, phieunhap.trangthai, phieunhap.idNV, chietkhau, tongsoluong
    FROM phieunhap
        INNER JOIN ctphieunhap on phieunhap.idPN = ctphieunhap.idPN
        INNER JOIN sach on ctphieunhap.idSach = sach.idSach
        INNER JOIN nhacungcap on sach.idNCC = nhacungcap.idNCC
    WHERE 1';
    if($kyw != NULL) $sql .= ' AND (phieunhap.idPN LIKE "%'.$kyw.'%" OR tenNCC LIKE "%'.$kyw.'%")';
    if($status_select != NULL) $sql .= ' AND phieunhap.trangthai LIKE "'.$status_select.'"';
    if($date_start != "" && $date_end != "")
        $sql .= ' AND (phieunhap.ngaytao BETWEEN "'.$date_start.'" AND "'.$date_end.'")';
    else if ($date_start != NULL) {
        $sql .= ' AND phieunhap.ngaytao >= '.$date_start;
    } else if ($date_end != NULL) {
        $sql .= ' AND phieunhap.ngaytao <= '.$date_end;
    }
    $list = [];
    $con = new Database();
    $req = $con->getAll($sql);


    foreach($req as $item){
        $grn = new self();
        $grn->nhap(
            $item['idPN'],
            $item['ngaytao'],
            $item['ngaycapnhat'],
            $item['tongtien'],
            $item['trangthai'],
            $item['idNV'],
            $item['chietkhau'],
            $item['tongsoluong']
        );
        $list[] = $grn;
    }
    return $list;
}

/* ... */

        function setIdPN($idPN){
            $this->idPN = $idPN;
        }


        function setIdNV($idNV){
            $this->idNV = $idNV;
        }


        function getIdPN(){
            return $this->idPN;
        }


        function getNgaytao(){
            return $this->ngaytao;
        }


        function getNgaycapnhat(){
            return $this->ngaycapnhat;
        }


        function getTongtien(){
            return $this->tongtien;
        }


        function getTrangthai(){
            return $this->trangthai;
        }


        function getIdNV(){
            return $this->idNV;
        }


        function getChietkhau(){
            return $this->chietkhau;
        }


        function getTongsoluong(){
            return $this->tongsoluong;
        }
    }
?>

