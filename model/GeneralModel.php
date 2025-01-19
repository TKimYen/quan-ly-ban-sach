<?php
class GeneralModel
{
    static function getNewAutoIncrementNumber($table_name)
    {
        $db = new Database();
        $sql = "SELECT AUTO_INCREMENT as newID
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = '" . DB_NAME . "'
                AND TABLE_NAME = '" . $table_name . "'";
        $result = $db->getOne($sql);
        return $result['newID'];
    }
}