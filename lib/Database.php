<?php
class Database{
private $host = DB_HOST;
private $user = DB_USER;
private $pass = DB_PASS;
private $dbname = DB_NAME;

public $link;
public $error;

public function __construct(){
    $this->connectDB();
}

private function connectDB(){
    $this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
    $this->link->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
    if(!$this->link){
        $this->error ="Connection fail".$this->link->connect_error;
        return false;
    }
}

public function getLink()
{
    return $this->link;
}

public function execute($sql){
    if($this->link->query($sql)) return true;
    return false;
}

public function getAll($sql){
    $result = $this->link->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : NULL;
}

public function getOne($sql){
    $result = $this->link->query($sql);
    return $result ? $result->fetch_assoc() : NULL;
}

}
?>