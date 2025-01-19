<?php
include __DIR__ . '/../../model/Product.php';
include __DIR__ . '/../../model/Author.php';
include __DIR__ . '/../../model/Category.php';
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../model/City.php';

class HomeController extends BaseController
{

    function __construct()
    {
        $this->folder = 'client';
    }

    function checkAction($action)
    {
        switch ($action) {
            case 'search':
                $this->search();
                break;
            case 'productDetail':
                $this->selectedProduct();
                break;
            case 'cart':
                $this->render('Cart');
                break;
            case 'cart':
                $this->render('Cart');
                break;
            case 'add-to-cart':
                $this->addToCart();
                break;
            case 'delete-cart-item':
                $this->deleteCartItem();
                break;
            case 'update-cart':
                $this->updateCart();
                break;
            case 'home':
            default: 
                $this->index();
                break;
        }
    }

    function index()
    {
        // $product = Product::getAll();
        $category = Category::getAllActive();
        $bestSellers = Product::getBestSeller();
        $result = [
            'category' => $category,
            'bestSellers' => $bestSellers,
        ];

        $this->render('Home', $result);
    }

    /* HUONG LE 22/11/2024 */
    function search()
    {
        if (isset($_GET['kyw'])) {
            $this->searchKyw();
        }
        if (isset($_GET['category'])) {
            $this->searchCategory();
        }
        if (isset($_GET['minPrice']) && isset($_GET['maxPrice'])) {
            $this->searchMinMax();
        }
    }

    function searchKyw()
    {
        if (isset($_GET['kyw'])) {
            $kyw = $_GET['kyw'];
            if ($kyw != "") {
                $product = Product::searchBook($kyw);
                $pageTitle = "&kyw=" . $kyw;

                $category = Category::getAllActive();

                $result = [
                    'category' => $category,
                    'paging' => $product
                ];

                $this->renderSearch('Search', $result, $pageTitle);
            } else {
                $this->index();
            }
        }
    }

    function searchCategory()
    {
        $category = Category::getAllActive();
        if (isset($_GET['category'])) {
            if ($_GET['category'] == "bestseller") {
                $pageTitle = "&category=bestseller";
                $product = Product::getBestSeller();
                $result = [
                    'category' => $category,
                    'paging' => $product
                ];
                $this->renderSearch('Search', $result, $pageTitle);
            } else if (isset($_GET['idTL'])) {
                $idTL = $_GET['idTL'];
                $pageTitle = "&category=" . $_GET['category'] . "&idTL=" . $idTL;
                $product = Product::getBooksByCategory($idTL);
                $result = [
                    'category' => $category,
                    'paging' => $product
                ];
                $this->renderSearch('Search', $result, $pageTitle);
            }
        }
    }

    function searchMinMax()
    {
        if (isset($_GET['minPrice']) && isset($_GET['maxPrice'])) {
            $minPrice  = $_GET['minPrice'];
            $maxPrice = $_GET['maxPrice'];
            $category = Category::getAllActive();

            $pageTitle = "&minPrice=" . $_GET['minPrice'] . "&maxPrice=" . $_GET['maxPrice'] . "";
            $product = Product::getProductByPrice($minPrice, $maxPrice);
            $result = [
                'category' => $category,
                'paging' => $product
            ];
            $this->renderSearch('Search', $result, $pageTitle);
        }
    }

    function selectedProduct()
    {
        if (isset($_GET['idSach'])) {
            $idSach  = (int)$_GET['idSach'];
            $book = Product:: findByID($idSach);
            $author = Product::getProductAuthors($idSach);
            $category = Category::getCategoryByIdBook($idSach);
            $result = [
                'category' => $category,
                'book' => $book,
                'author' => $author
            ];
            $this->render('ProductDetail', $result);
        }
    }
    /* HUONG LE 22/11/2024 */

     /* HUONG NGUYEN 25/11 */
     function addToCart()
     {
         session_start();
         if (isset($_SESSION["user"]) && $_SESSION["user"] != null) {
             $idSach = (int)$_GET['idSach'] ?? 0;
 
             if (!isset($_SESSION['cart'])) {
                 $_SESSION['cart'] = [];
                 $_SESSION['cart']['products'] = [];
             }
 
             if ($idSach != 0) {
                 // Lấy ra sản phẩm nếu nó đang được bán
                 $book = Product::getBookByID($idSach, true);
                 $isExist = false;
                 if ($book) {
                     if ($book->getTonkho() == 0) {
                         echo json_encode(array('status' => 'error', 'message' => 'Sản phẩm đã hết hàng'));
                         exit;
                     }
                     foreach ($_SESSION["cart"]['products'] as &$item) {
                         if ($item['idSach'] == $idSach) {
                             $item["soluong"] += 1;
 
                             if ($item["soluong"] > $book->getTonkho()) {
                                 echo json_encode(array('status' => 'error', 'message' => 'Số lượng sản phẩm trong giỏ hàng vượt quá số lượng tồn kho'));
                                 $item["soluong"] = $book->getTonkho();
                                 exit;
                             }
 
                             $isExist = true;
                             echo json_encode(array('status' => 'success', 'message' => 'Thêm sản phẩm vào giỏ hàng thành công'));
                             break;
                         }
                     }
 
                     if (!$isExist) {
                         $product = [
                             'idSach' => $book->getIdSach(),
                             'tuasach' => $book->getTuasach(),
                             'hinhanh' => $book->getHinhanh(),
                             'giaban' => $book->getGiaban(),
                             'trongluong' => $book->getTrongluong(),
                             'soluong' => 1,
                             'tonkho' => $book->getTonkho()
                         ];
                         array_push($_SESSION['cart']['products'], $product);
                         echo json_encode(array('status' => 'success', 'message' => 'Thêm sản phẩm vào giỏ hàng thành công'));
                     }
                 } else {
                     echo json_encode(array('status' => 'error', 'message' => 'Sản phẩm không tồn tại'));
                 }
             }
         } else {
             echo json_encode(array('status' => 'error', 'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng'));
         }
         exit;
     }
 
     function deleteCartItem()
     {
         session_start();
         if (isset($_SESSION["user"]) && $_SESSION["user"] != null) {
             $index = (int)$_GET['index'] ?? -1;
             if ($index >= 0) {
                 array_splice($_SESSION['cart']['products'], $index, 1);
                 if (count($_SESSION['cart']['products']) == 0) {
                     unset($_SESSION['cart']);
                 }
                 echo json_encode(array('status' => 'success', 'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công'));
             } else {
                 echo json_encode(array('status' => 'error', 'message' => 'Xóa sản phẩm khỏi giỏ hàng thất bại do dữ liệu không hợp lệ'));
                 exit;
             }
         } else {
             echo json_encode(array('status' => 'error', 'message' => 'Bạn cần đăng nhập để xóa sản phẩm khỏi giỏ hàng'));
         }
         exit;
     }
 
     function updateCart()
     {
         session_start();
         $index = (int)$_GET['index'] ?? -1;
         $soluong = (int)$_GET['quantity'] ?? -1;
         if ($index >= 0 && $soluong >= 0) {
             $_SESSION["cart"]['products'][$index]["soluong"] = $soluong;
             echo json_encode(array('status' => 'success', 'message' => 'Cập nhật giỏ hàng thành công', 'data' => $_SESSION["cart"]['products']));
         } else {
             echo json_encode(array('status' => 'error', 'message' => 'Cập nhật giỏ hàng thất bại do dữ liệu không hợp lệ'));
         }
         exit;
     }
 
     /* HUONG NGUYEN 25/11 */
}


$homeController = new HomeController();
if (!isset($_GET['page'])) $action = 'home';
else $action = $_GET['page'];
$homeController->checkAction($action);
