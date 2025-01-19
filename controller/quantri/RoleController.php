<?php
if(isset($_POST['action'])){
    require '../BaseController.php';
    require '../../model/Role.php';
}
else{
    require '../controller/BaseController.php';
    require '../model/Role.php';
}
    class RoleController extends BaseController{
        private $role;

        function __construct()
        {
            $this->folder = 'quantri';
            $this->role= new Role();
        }

        function index(){
            $role = Role::getAll();
            $this->render('Role', array('paging' => $role), true);
        }

        function add(){
            $this->role->setTenNQ($_POST['tenNQ']);
            $this->role->setTrangthai(1);
            $permission_name = array_slice(array_keys($_POST), 2);
            $req = $this->role->add($permission_name);
            if($req == '') echo json_encode(array('btn'=>'add', 'success'=>true));
            else echo json_encode(array('btn'=>'add', 'success'=>false, 'msg' => $req));
            exit;
        }

        function viewForm(){
            $role = Role::findByID($_POST['role_id']);
            
            echo json_encode($role==null ? null: $role->toArray());
            exit;
        }

        function update(){
            $this->role->setIdNQ($_POST['idNQ']);
            $this->role->setTenNQ($_POST['tenNQ']);
            $permission_name = array_slice(array_keys($_POST), 2);
            $req = $this->role->update($permission_name);
            if($req) echo json_encode(array('btn'=>'update', 'success'=>true));
            else echo json_encode(array('btn'=>'update', 'success'=>false));
            exit;
        }

        function lock(){
            $this->role->setIdNQ($_POST['role_id']);
            $this->role->lock();
            echo json_encode(array('success'=>true));
            exit;
        }

        function unlock(){
            $this->role->setIdNQ($_POST['role_id']);
            $this->role->unlock();
            echo json_encode(array('success'=>true));
            exit;
        }

        function search(){
            $pageTitle = 'searchRole';
            $kyw = NULL;
            if(isset($_GET['kyw']) && isset($_GET['kyw']) != "") {
                $kyw = $_GET['kyw'];
                $pageTitle .= '&kyw='.$kyw;
            }
            $result = [
                'paging' => Role::search($kyw)
            ];
            $this->renderSearch('Role', $result, $pageTitle);
        }

        function checkAction($action){
            switch ($action){
                case 'index':
                    $this->index();
                    break;

                case 'submit_btn_add':
                    $this->add();
                    break;
                
                case 'view_data':
                    $this->viewForm();
                    break;

                case 'submit_btn_update':
                    $this->update();
                    break;

                case 'lock_role':
                    $this->lock();
                    break;

                case 'unlock_role':
                    $this->unlock();
                    break;

                case 'search':
                    $this->search();
                    break;
            }
        }
    }

    $roleController = new RoleController();
    if(isset($_GET['page']) && $_GET['page'] == 'searchRole') $action = 'search';
    else if(!isset($_POST['action'])) $action = 'index';    
    else $action = $_POST['action'];
    $roleController->checkAction($action);

?>