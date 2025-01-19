<?php
    include dirname(__FILE__).'/../config/config.php';
    include dirname(__FILE__).'/../lib/Database.php';
    require dirname(__FILE__).'/Pagination.php';
    class BaseController{
        protected $folder;

        function render($file, $result= array(), $paging=false){
            
            $view_file = dirname(__FILE__).'/../view/'.$this->folder.'/'.$file.'.php';
            if(is_file($view_file)){
                if($paging){
                    $paging = new Pagination($this->folder, strtolower($file), $result['paging']);
                    $pagingButton = $paging->paging($this->folder);
                }
                require($view_file);
            }
        }

        function renderSearch($file, $result = array(), $searchTitle){
            $view_file = dirname(__FILE__).'/../view/'.$this->folder.'/'.$file.'.php';
            if(is_file($view_file)){
                    $paging = new Pagination($this->folder, strtolower($file), $result['paging']);
                    $pagingButton = $paging->pagingSearch($this->folder, $searchTitle);
                    require($view_file);
                    
            }
        }
    }
?>