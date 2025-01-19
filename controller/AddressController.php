<?php
require '../config/config.php';
require '../lib/Database.php';
require '../model/City.php';
require '../model/District.php';
require '../model/Ward.php';
    class AddressController{
        
        function showCity(){
            $cities = City::getAll();
            $result = [
                'success' => true,
                'cities' => $cities
            ];
            echo json_encode($result);
        }

        function showDistrict(){
            $districts = District::getAllByCity($_POST['province_id']);
            $result = [
                'success' => true,
                'districts' => $districts
            ];
            echo json_encode($result);
        }

        function showWard(){
            $wards = Ward::getAllByDistrict($_POST['district_id']);
            $result = [
                'success' => true,
                'wards' => $wards
            ];
            echo json_encode($result);
        }

        function checkAction($action){
            switch ($action){
                case 'show_city':
                    $this->showCity();
                    break;
                case 'show_district':
                    $this->showDistrict();
                    break;
                case 'show_ward':
                    $this->showWard();
                    break;
                default:
                    $this->showError();
                    break;
            }
        }

        function showError(){
            echo json_encode([
                'success' => false,
                'msg' => 'Lỗi hệ thống'
            ]);
        }
    }

    $addressController = new AddressController();
    if(isset($_POST['action'])) $addressController->checkAction($_POST['action']);
    else $addressController->showError();
?>