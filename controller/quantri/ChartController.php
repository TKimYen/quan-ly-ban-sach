<?php 
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../model/Chart.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

class ChartController extends BaseController {
    function __construct() {
        $this->folder = 'quantri';
    }

    function checkAction($action){
        switch($action){
            case 'index':
                $this->index();
                break;
            case 'getRCP':
                $this->getRCP();
                break;
            case 'getTop10BestSellingBooks':
                $this->getTop10BestSellingBooks();
                break;
            case 'getTop10LoyalCustomers':
                $this->getTop10LoyalCustomers();
                break;
            case 'getBookQuantityByCategory':
                $this->getBookQuantityByCategory();
                break;
            default:
                $this->index();
                break;
        }
    }

    function index(){
        $this->render('Chart', array(), false);
    }

    /* SHOW DATA IN VIEW */
    static function getWeeksOrderCount(){
        $chart = new Chart();
        $data = $chart->getWeeksOrderCount();
        $result['current_week_orders'] = $data['current_week_orders'] ?? 0;
        $result['previous_week_orders'] = $data['previous_week_orders'] ?? 0;
        if ($result['previous_week_orders'] != 0) {
            $result['compare'] = round(($result['current_week_orders'] - $result['previous_week_orders']) / $result['previous_week_orders'] * 100, 2);
        } else {
            $result['compare'] = 0;
        }
        return $result;
    }

    static function getWeeksRevenue(){
        $chart = new Chart();
        $data = $chart->getWeeksRevenue();
        $result['current_week_total'] = $data['current_week_total'] ?? 0;
        $result['previous_week_total'] = $data['previous_week_total'] ?? 0;
        if ($result['previous_week_total'] != 0) {
            $result['compare'] = round(($result['current_week_total'] - $result['previous_week_total']) / $result['previous_week_total'] * 100, 2);
        } else {
            $result['compare'] = 0;
        }
        return $result;
    }

    static function getWeeksQuantitySold() {
        $chart = new Chart();
        $data = $chart->getWeeksQuantitySold();
        $result['current_week_quantity'] = $data['current_week_quantity'] ?? 0;
        $result['previous_week_quantity'] = $data['previous_week_quantity'] ?? 0;
        if ($result['previous_week_quantity'] != 0) {
            $result['compare'] = round(($result['current_week_quantity'] - $result['previous_week_quantity']) / $result['previous_week_quantity'] * 100, 2);
        } else {
            $result['compare'] = 0;
        }
        return $result;
    }
    /* SHOW DATA IN VIEW */

    /* AJAX HANDLER */
    function getRCP() {
        $chart = new Chart();
        $data = $chart->getRCP();
        $result = [];
        $result['months'] = array_map(fn($i) => "Tháng $i", range(1, date('m')));
        $result['revenues'] = array_fill(0, date('m'), 0);
        $result['costs'] = array_fill(0, date('m'), 0);
        $result['profits'] = array_fill(0, date('m'), 0);
        foreach ($data as $item) {
            $result['revenues'][$item['thang'] - 1] = $item['doanhthu'];
            $result['costs'][$item['thang'] - 1] = $item['chiphi'];
            $result['profits'][$item['thang'] - 1] = $item['loinhuan'];
        }
        echo json_encode($result);
        exit;
    }

    function getTop10BestSellingBooks() {
        $chart = new Chart();
        $data = $chart->getTop10BestSellingBooks();
        $result = [];
        foreach($data as $row) {
            $result['yAxis'][] = $row['tuasach'];
            $result['xAxis'][] = $row['soluong'];
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);
            $result['borderColors'][] = "rgba($r, $g, $b, 1)";
            $result['backgroundColors'][] = "rgba($r, $g, $b, 0.3)";
        }
        echo json_encode($result);
        exit;
    }

    function getTop10LoyalCustomers() {
        $chart = new Chart();
        $data = $chart->getTop10LoyalCustomers();
        $result = [];
        foreach($data as $row) {
            $result['yAxis'][] = $row['tenTK'];
            $result['xAxis'][] = $row['tongtien'];
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);
            $result['borderColors'][] = "rgba($r, $g, $b, 1)";
            $result['backgroundColors'][] = "rgba($r, $g, $b, 0.3)";
        }
        echo json_encode($result);
        exit;
    }

    function getBookQuantityByCategory() {
        $chart = new Chart();
        $data = $chart->getBookQuantityByCategory();
        $result = [];
        $result['months'] = array_map(fn($i) => "Tháng $i", range(1, date('m')));
        $categories = array_reduce(array_column($data, 'category'), function($carry, $item) {
            if (!in_array($item, $carry)) {
                $carry[] = $item;
            }
            return $carry;
        }, []);
        $result['categories'] = array_fill_keys($categories, array_fill(0, 12, 0));
        foreach ($data as $item) {
            $months = $item['month'];
            $result['categories'][$item['category']][$months-1] = (int)$item['quantity'];
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);
            $result['borderColors'][$item['category']] = "rgba($r, $g, $b, 1)";
            $result['backgroundColors'][$item['category']] = "rgba($r, $g, $b, 0.3)";
        }
        echo json_encode($result);
        exit;
    }
    /* AJAX HANDLER */
}

$chartController = new ChartController();
if (isset($_GET['page']) && $_GET['page'] == 'chart') {
    $action = $_GET['page'];
} else if (isset($_POST['action']) && $_POST['action'] !== '') {
    $action = $_POST['action'];
}
$chartController->checkAction($action);
?>