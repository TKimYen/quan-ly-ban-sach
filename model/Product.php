<?php


class Product
{
    private ?int $idSach;
    private string $tuasach;
    private string $mota;
    private int $tonkho;
    private int $luotban;
    private string $nxb;
    private int $namxb;
    private float $giaban;
    private float $giabia;
    private int $trangthai;
    private int $idNCC;
    private string $hinhanh;
    private float $trongluong;
    private array $idTG;
    private ?int $idMGG;
    private int $idTL;


    // !!! ĐỂ TẠM GIÁ BÁN = GIÁ BÌA
    function __construct(?int $idSach = NULL, string $tuasach = "", string $mota = "", int $tonkho = 0, int $luotban = 0, string $nxb = "", int $namxb = 0, float $giaban = 0, float $giabia = 0, int $trangthai = 1, int $idNCC = 0, string $hinhanh = "", float $trongluong = 0, ?int $idMGG = NULL, array $idTG = [], int $idTL = 0)
    {
        $this->idSach = $idSach;
        $this->tuasach = $tuasach;
        $this->mota = $mota;
        $this->tonkho = $tonkho;
        $this->luotban = $luotban;
        $this->nxb = $nxb;
        $this->namxb = $namxb;
        $this->giaban = $giaban;
        $this->giabia = $giabia;
        $this->trangthai = $trangthai;
        $this->idNCC = $idNCC;
        $this->hinhanh = $hinhanh;
        $this->trongluong = $trongluong;
        $this->idTG = $idTG;
        $this->idMGG = $idMGG;
        $this->idTL = $idTL;
    }


    static function getAll()
    {
        $list = [];
        $sql = 'SELECT * FROM sach';
        $con = new Database();
        $req = $con->getAll($sql);


        foreach ($req as $item) {
            // Lấy danh sách tác giả
            $sql = 'SELECT idTG FROM sach_tacgia WHERE idSach=' . $item['idSach'];
            $reqTG = $con->getAll($sql);
            $idTG = [];
            foreach ($reqTG as $itemTG) {
                $idTG[] = $itemTG['idTG'];
            }
           
            // !!! ĐỂ TẠM GIÁ BÁN = GIÁ BÌA
            $product = new self(
                $item['idSach'],
                $item['tuasach'],
                $item['mota'],
                $item['tonkho'],
                $item['luotban'],
                $item['NXB'],
                $item['namXB'],
                $item['giaban'],
                $item['giabia'],
                $item['trangthai'],
                $item['idNCC'],
                $item['hinhanh'],
                $item['trongluong'],
                null,
                $idTG,
                $item['idTL']
            );
            $list[] = $product;
        }
        return $list;
    }


    function isExist()
    {
        $listTG = implode(',', $this->idTG);
        $sql = 'SELECT s.idSach
            FROM sach s
            JOIN sach_tacgia st ON s.idSach = st.idSach
            WHERE s.tuasach = "' . $this->tuasach . '"
            AND s.namxb = ' . $this->namxb . '
            AND s.giabia = ' . $this->giabia . '
            AND s.idNCC = ' . $this->idNCC . '
            AND s.nxb = "' . $this->nxb . '"';
        if ($this->idSach != null || $this->idSach != 0) $sql .= ' AND s.idSach !=' . $this->idSach . ' ';
        $sql .= 'GROUP BY s.idSach
            HAVING GROUP_CONCAT(st.idTG ORDER BY st.idTG ASC) = "' . $listTG . '"';
        $con = new Database();
        $result = $con->getOne($sql);
        return $result != NULL;
    }


    function add()
    {
        if (!($this->isExist())) {
            $sql = "INSERT INTO sach (tuasach, mota, tonkho, luotban, NXB, namXB, giaban, giabia, trangthai, idNCC, idTL, hinhanh, trongluong)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


            $con = new Database();


            $stmt = $con->getLink()->prepare($sql);
            $stmt->bind_param('ssiisiddiiisd', $this->tuasach, $this->mota, $this->tonkho, $this->luotban, $this->nxb, $this->namxb, $this->giaban, $this->giabia, $this->trangthai, $this->idNCC, $this->idTL, $this->hinhanh, $this->trongluong);
            $stmt->execute();
            $stmt->close();


            $this->idSach = $this->getLastID();
            $this->addDetail();
            return true;
        }
        return false;
    }


    function update() {
        if (!($this->isExist())) {
            $sql = "UPDATE sach
                    SET tuasach = ?,
                        mota = ?,
                        NXB = ?,
                        namXB = ?,
                        giaban = ?,
                        giabia = ?,
                        trangthai = ?,
                        idNCC = ?,
                        hinhanh = ?,
                        trongluong = ?,
                        idMGG = ?,
                        idTL = ?
                    WHERE idSach = ?";


            $con = new Database();


            $stmt = $con->getLink()->prepare($sql);
            $stmt->bind_param('sssiddiisdiii', $this->tuasach, $this->mota, $this->nxb, $this->namxb, $this->giaban, $this->giabia, $this->trangthai, $this->idNCC, $this->hinhanh, $this->trongluong, $this->idMGG, $this->idTL, $this->idSach);
            $stmt->execute();
            $stmt->close();


            $sql = "DELETE FROM sach_tacgia WHERE idSach = ?";
            $stmt = $con->getLink()->prepare($sql);
            $stmt->bind_param('i', $this->idSach);
            $stmt->execute();
            $stmt->close();


            $this->addDetail();
            return true;
        }
        return false;
    }


    function getLastID()
    {
        $sql = 'SELECT idSach
            FROM sach
            ORDER BY idSach DESC
            LIMIT 1';
        $con = new Database();
        return $con->getOne($sql)['idSach'];
    }


    function addDetail()
    {
        $con = new Database();
        $sql = 'INSERT INTO sach_tacgia(idSach, idTG) VALUES (?, ?)';
        foreach ($this->idTG as $idTG) {
            $stmt = $con->getLink()->prepare($sql);
            $stmt->bind_param('ii', $this->idSach, $idTG);
            $stmt->execute();
            $stmt->close();
        }
    }


    static function getProductImage(int $idSach) {
        $sql = "SELECT hinhanh FROM sach WHERE idSach = {$idSach}";
        $con = new Database();
        $result = $con->getOne($sql);
        return $result['hinhanh'];
    }


    static function getProductAuthors(int $idSach) {
        $sql = "SELECT  tacgia.*
                FROM    tacgia
                JOIN    sach_tacgia ON sach_tacgia.idSach = {$idSach}
                WHERE   sach_tacgia.idTG = tacgia.idTG
                AND tacgia.trangthai = 1";
        $con = new Database();
        $result = $con->getAll($sql);
        return $result;
    }

    static function getProductDiscount(int $idSach) {
        $sql = "SELECT  magiamgia.phantram
                FROM    magiamgia
                JOIN    sach ON sach.idSach = {$idSach}
                WHERE   sach.idMGG = magiamgia.idMGG";
        $con = new Database();
        $result = $con->getOne($sql);
        return $result['phantram'];
    }


    static function getProductDetailByID(int $id) {
        $sql = "SELECT  sach.*,  ncc.tenNCC, tl.tenTL
                FROM    sach, nhacungcap ncc, theloai tl
                WHERE   sach.idSach = {$id}
                AND     sach.idNCC = ncc.idNCC
                AND     sach.idTL = tl.idTL ";
        $con = new Database();
        $result = $con->getOne($sql);


        foreach (Product::getProductAuthors($id) as $author) {
            $result['authors'][] = [
                'idTG' => $author['idTG'],
                'tenTG' => $author['tenTG']
            ];
        }


        if ($result['idMGG'] != null) {
            $result['discount'] = Product::getProductDiscount($id);
        }


        return $result;
    }

    static function findByID($idSach){
        $sql = 'SELECT * FROM sach WHERE idSach='.$idSach;
        $con = new Database();
        $req = $con->getOne($sql);


            // Lấy danh sách tác giả
            $sql = 'SELECT idTG FROM sach_tacgia WHERE idSach=' . $req['idSach'];
            $reqTG = $con->getAll($sql);
            $idTG = [];
            foreach ($reqTG as $itemTG) {
                $idTG[] = $itemTG['idTG'];
            }
           
           
            // !!! ĐỂ TẠM GIÁ BÁN = GIÁ BÌA
            $product = new self(
                $req['idSach'],
                $req['tuasach'],
                $req['mota'],
                $req['tonkho'],
                $req['luotban'],
                $req['NXB'],
                $req['namXB'],
                $req['giaban'],
                $req['giabia'],
                $req['trangthai'],
                $req['idNCC'],
                $req['hinhanh'],
                $req['trongluong'],
                null,
                $idTG,
                $req['idTL']
            );
        return $product;
    }

    static function getBooksByCategory($idTL, $search=true) {
        $sql = 'SELECT DISTINCT sach.idSach, hinhanh, tuasach, luotban, giaban, giabia, nxb, idNCC, idTL, namxb, mota, trongluong, sach.trangthai, tonkho
                FROM sach INNER JOIN sach_tacgia ON sach.idSach = sach_tacgia.idSach
                INNER JOIN tacgia ON sach_tacgia.idTG = tacgia.idTG
                WHERE tonkho > 0
                AND sach.trangthai = 1
                AND idTL = '.$idTL.'
                ORDER BY luotban DESC';
        if(!$search) $sql.=' LIMIT 4';
                $con = new Database();
                $req = $con->getAll($sql);
            $list = [];
                foreach ($req as $item) {
                    // Lấy danh sách tác giả
                    $sql = 'SELECT idTG FROM sach_tacgia WHERE idSach=' . $item['idSach'];
                    $reqTG = $con->getAll($sql);
                    $idTG = [];
                    foreach ($reqTG as $itemTG) {
                        $idTG[] = $itemTG['idTG'];
                    }
                    
                    // !!! ĐỂ TẠM GIÁ BÁN = GIÁ BÌA
                    $product = new self(
                        $item['idSach'],
                        $item['tuasach'],
                        $item['mota'],
                        $item['tonkho'],
                        $item['luotban'],
                        $item['nxb'],
                        $item['namxb'],
                        $item['giaban'],
                        $item['giabia'],
                        $item['trangthai'],
                        $item['idNCC'],
                        $item['hinhanh'],
                        $item['trongluong'],
                        null,
                        $idTG,
                        $item['idTL']
                    );
                    $list[] = $product;
                }
                return $list;
    }

    static function getAllBySupplier($idNCC){
        $sql = 'SELECT * FROM sach WHERE idNCC = '.$idNCC;
        $list = [];
        $con = new Database();
        $req = $con->getAll($sql);

        foreach($req as $item){
            $product = new self(
                $item['idSach'],
                $item['tuasach'],
                $item['mota'],
                $item['tonkho'],
                $item['luotban'],
                $item['NXB'],
                $item['namXB'],
                $item['giaban'],
                $item['giabia'],
                $item['trangthai'],
                $item['idNCC'],
                $item['hinhanh'],
                $item['trongluong'],
                null,
                [],
                $item['idTL']
            );
            $list[] = $product->toArray();
        }
        return $list;
    }

    /* HUONG LE - 21/11/2024 */

    static function getBookById($idSach, $isActive = false)
    {
        $sql = 'SELECT hinhanh, tuasach, luotban, nxb, idNCC, idTL, giabia, namxb, mota, trongluong, trangthai, giaban, idSach, tonkho
                FROM sach
                WHERE idSach = ' . $idSach;
        if ($isActive) $sql .= ' AND trangthai = 1';
        $con = new Database();
        $req = $con->getOne($sql);

        // Lấy danh sách tác giả
        $sql = 'SELECT idTG FROM sach_tacgia WHERE idSach=' . $req['idSach'];
        $reqTG = $con->getAll($sql);
        $idTG = [];
        foreach ($reqTG as $itemTG) {
            $idTG[] = $itemTG['idTG'];
        }


        // !!! ĐỂ TẠM GIÁ BÁN = GIÁ BÌA
        $product = new self(
            $req['idSach'],
            $req['tuasach'],
            $req['mota'],
            $req['tonkho'],
            $req['luotban'],
            $req['nxb'],
            $req['namxb'],
            $req['giaban'],
            $req['giabia'],
            $req['trangthai'],
            $req['idNCC'],
            $req['hinhanh'],
            $req['trongluong'],
            null,
            $idTG,
            $req['idTL']
        );
        return $product;
    }

    static function getBestSeller() {
        $sql='SELECT DISTINCT sach.idSach, hinhanh, tuasach, luotban, giaban, giabia, NXB, idNCC, idTL, namXB, mota, trongluong, sach.trangthai, tonkho
            FROM sach INNER JOIN sach_tacgia ON sach.idSach = sach_tacgia.idSach
            INNER JOIN tacgia ON sach_tacgia.idTG = tacgia.idTG
            WHERE tonkho > 0
            AND luotban > 0
            AND sach.trangthai = 1
            ORDER BY luotban DESC
            LIMIT 5';
        $list = [];
        $con = new Database();
        $req = $con->getAll($sql);


        foreach ($req as $item) {
            // Lấy danh sách tác giả
            $sql = 'SELECT idTG FROM sach_tacgia WHERE idSach=' . $item['idSach'];
            $reqTG = $con->getAll($sql);
            $idTG = [];
            foreach ($reqTG as $itemTG) {
                $idTG[] = $itemTG['idTG'];
            }
           
            // !!! ĐỂ TẠM GIÁ BÁN = GIÁ BÌA
            $product = new self(
                $item['idSach'],
                $item['tuasach'],
                $item['mota'],
                $item['tonkho'],
                $item['luotban'],
                $item['NXB'],
                $item['namXB'],
                $item['giaban'],
                $item['giabia'],
                $item['trangthai'],
                $item['idNCC'],
                $item['hinhanh'],
                $item['trongluong'],
                null,
                $idTG,
                $item['idTL']
            );
            $list[] = $product;
        }
        return $list;
    }


    static function getProductByPrice($minPrice, $maxPrice) {
        $sql = 'SELECT DISTINCT sach.idSach, hinhanh, tuasach, luotban, giaban, giabia, nxb, idNCC, idTL, namxb, mota, trongluong, sach.trangthai, tonkho
                FROM sach INNER JOIN sach_tacgia ON sach.idSach = sach_tacgia.idSach
                INNER JOIN tacgia ON sach_tacgia.idTG = tacgia.idTG
                WHERE tonkho > 0
                AND sach.trangthai = 1';


        if ($minPrice != NULL && $maxPrice != NULL) {
            $sql .= ' AND (giaban >= '.$minPrice.' AND giaban <='.$maxPrice.')';
        } else if ($minPrice != NULL) {
            $sql .= ' AND giaban >= '.$minPrice;
        } else if ($maxPrice != NULL) {
            $sql .= ' AND giaban <= '.$maxPrice;
        }


        $con = new Database();
        $req = $con->getAll($sql);
        $list = [];
        foreach ($req as $item) {
            // Lấy danh sách tác giả
            $sql = 'SELECT idTG FROM sach_tacgia WHERE idSach=' . $item['idSach'];
            $reqTG = $con->getAll($sql);
            $idTG = [];
            foreach ($reqTG as $itemTG) {
                $idTG[] = $itemTG['idTG'];
            }
           
            // !!! ĐỂ TẠM GIÁ BÁN = GIÁ BÌA
            $product = new self (
                $item['idSach'],
                $item['tuasach'],
                $item['mota'],
                $item['tonkho'],
                $item['luotban'],
                $item['nxb'],
                $item['namxb'],
                $item['giaban'],
                $item['giabia'],
                $item['trangthai'],
                $item['idNCC'],
                $item['hinhanh'],
                $item['trongluong'],
                null,
                $idTG,
                $item['idTL']
            );
            $list[] = $product;
        }
        return $list;
    }

    static function searchBook($kyw) {
        $sql='SELECT DISTINCT sach.idSach, hinhanh, tuasach, luotban, giaban, giabia, nxb, idNCC, idTL, namxb, mota, trongluong, sach.trangthai, tonkho
        FROM sach INNER JOIN sach_tacgia ON sach.idSach = sach_tacgia.idSach
        INNER JOIN tacgia ON sach_tacgia.idTG = tacgia.idTG
        WHERE tonkho > 0
        AND sach.trangthai = 1
        AND (tuasach like "%'.$kyw.'%"
        OR tenTG like "%'.$kyw.'%")
        order by luotban DESC';
        $list=[];
        $con = new Database();
        $req = $con->getAll($sql);


        foreach ($req as $item) {
            // Lấy danh sách tác giả
            $sql = 'SELECT idTG FROM sach_tacgia WHERE idSach=' . $item['idSach'];
            $reqTG = $con->getAll($sql);
            $idTG = [];
            foreach ($reqTG as $itemTG) {
                $idTG[] = $itemTG['idTG'];
            }
           
            // !!! ĐỂ TẠM GIÁ BÁN = GIÁ BÌA
            $product = new self(
                $item['idSach'],
                $item['tuasach'],
                $item['mota'],
                $item['tonkho'],
                $item['luotban'],
                $item['nxb'],
                $item['namxb'],
                $item['giaban'],
                $item['giabia'],
                $item['trangthai'],
                $item['idNCC'],
                $item['hinhanh'],
                $item['trongluong'],
                null,
                $idTG,
                $item['idTL']
            );
            $list[] = $product;
        }
        return $list;
    }


    function toArray(){
        return [
            'hinhanh' => $this->hinhanh,
            'tuasach' => $this->tuasach,
            'nxb' => $this->nxb,
            'idNCC' => $this->idNCC,
            'idTL' => $this->idTL,
            'giabia' => $this->giabia,
            'namxb' => $this->namxb,
            'mota' => $this->mota,
            'giaban' => $this->giaban,
            'trongluong' => $this->trongluong,
            'trangthai' => $this->trangthai,
            'idSach' => $this->idSach,
            'tonkho' => $this->tonkho,
            'luotban' => $this->luotban
        ];
    }

    static function search($kyw, $idTL, $price_max, $price_min, $sort){
        $sql = 'SELECT *
                FROM sach
                WHERE 1';
        if($kyw != NULL)  $sql .= ' AND (idSach LIKE "%'.$kyw.'%" OR tuasach LIKE "%'.$kyw.'%")';
        if($idTL != NULL)  $sql .= ' AND idTL = '.$idTL;
        if($price_max != NULL && $price_min != NULL) $sql .= ' AND (giaban >= '.$price_min.' AND giaban <='.$price_max.')';
        else if($price_min != NULL) $sql .= ' AND giaban >= '.$price_min;
        else if($price_max != NULL) $sql .= ' AND giaban <= '.$price_max;
        
        if($sort!=NULL){
            if($sort == 'sort19') $sql .= " order by tonkho asc";
            else $sql .= " order by tonkho desc";
        }
        $list = [];
        $con = new Database();
        $req = $con->getAll($sql);  


        foreach ($req as $item) {
            // Lấy danh sách tác giả
            $sql = 'SELECT idTG FROM sach_tacgia WHERE idSach=' . $item['idSach'];
            $reqTG = $con->getAll($sql);
            $idTG = [];
            foreach ($reqTG as $itemTG) {
                $idTG[] = $itemTG['idTG'];
            }
           
            // !!! ĐỂ TẠM GIÁ BÁN = GIÁ BÌA
            $product = new Product(
                $item['idSach'],
                $item['tuasach'],
                $item['mota'],
                $item['tonkho'],
                $item['luotban'],
                $item['NXB'],
                $item['namXB'],
                $item['giaban'],
                $item['giabia'],
                $item['trangthai'],
                $item['idNCC'],
                $item['hinhanh'],
                $item['trongluong'],
                null,
                $idTG,
                $item['idTL']
            );
            $list[] = $product;
        }
        return $list;
    }


    /* HUONG LE - 21/11/2024 */

     /* HUONG NGUYEN - 27/11/2024 */
     static function updateStock($idSach, $soluong)
     {
         $sql = 'UPDATE sach
                 SET tonkho = tonkho - ' . $soluong . '
                 WHERE idSach = ' . $idSach;
         $con = new Database();
         return $con->execute($sql);
     }
     /* HUONG NGUYEN - 27/11/2024 */

    // Getter & Setter
    public function getIdSach(): int
    {
        return $this->idSach;
    }


    public function setIdSach(int $idSach): void
    {
        $this->idSach = $idSach;
    }


    public function getTuasach(): string
    {
        return $this->tuasach;
    }


    public function setTuasach(string $tuasach): void
    {
        $this->tuasach = $tuasach;
    }


    public function getMota(): string
    {
        return $this->mota;
    }


    public function setMota(string $mota): void
    {
        $this->mota = $mota;
    }


    public function getTonkho(): int
    {
        return $this->tonkho;
    }


    public function setTonkho(int $tonkho): void
    {
        $this->tonkho = $tonkho;
    }


    public function getLuotban(): int
    {
        return $this->luotban;
    }


    public function setLuotban(int $luotban): void
    {
        $this->luotban = $luotban;
    }


    public function getNxb(): string
    {
        return $this->nxb;
    }


    public function setNxb(string $nxb): void
    {
        $this->nxb = $nxb;
    }


    public function getNamxb(): int
    {
        return $this->namxb;
    }


    public function setNamxb(int $namxb): void
    {
        $this->namxb = $namxb;
    }


    public function getGiaban(): float
    {
        return $this->giaban;
    }


    public function setGiaban(float $giaban): void
    {
        $this->giaban = $giaban;
    }


    public function getGiabia(): float
    {
        return $this->giabia;
    }


    public function setGiabia(float $giabia): void
    {
        $this->giabia = $giabia;
    }


    public function getTrangthai(): int
    {
        return $this->trangthai;
    }


    public function setTrangthai(int $trangthai): void
    {
        $this->trangthai = $trangthai;
    }


    public function getIdNCC(): int
    {
        return $this->idNCC;
    }


    public function setIdNCC(int $idNCC): void
    {
        $this->idNCC = $idNCC;
    }


    public function getHinhanh(): string
    {
        return $this->hinhanh;
    }


    public function setHinhanh(string $hinhanh): void
    {
        $this->hinhanh = $hinhanh;
    }


    public function getTrongluong(): float
    {
        return $this->trongluong;
    }


    public function setTrongluong(float $trongluong): void
    {
        $this->trongluong = $trongluong;
    }


    public function getIdTG(): array
    {
        return $this->idTG;
    }


    public function setIdTG(array $idTG): void
    {
        $this->idTG = $idTG;
    }


    public function getIdMGG(): ?int
    {
        return $this->idMGG;
    }


    public function setIdMGG(?int $idMGG): void
    {
        $this->idMGG = $idMGG;
    }


    public function getIdTL(): int
    {
        return $this->idTL;
    }


    public function setIdTL(int $idTL): void
    {
        $this->idTL = $idTL;
    }
}


?>

