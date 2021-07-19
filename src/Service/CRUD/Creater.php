<?php 

namespace Sigfw\Service\CRUD;

use Sigfw\Service\CRUD\Base;
use Sigfw\Service\Logger;
use Sigfw\Service\Utils;
use PDO;
use PDOException;

class Creater extends Base
{
    public function __construct($table_name)
    {
        parent::__construct($table_name);
    }

    public function insert($arr) 
    {
        $con = $this->verification_connection();
        if(!$con) return null;

        $arr = Utils::clean_array($arr);

        $sql = "INSERT INTO `{$this->get_table_name()}`";
        $sql = $this->get_proper_sql_for_data_insertion($sql, $arr);

        $stmt = $con->prepare($sql);

        foreach($arr as $key=>$value) 
        {
            $stmt->bindParam(':' . $key, $arr[$key]);
        }

        try 
        {
            $stmt->execute();
			return $con->lastInsertId();
        } 
        catch(PDOException $e) 
        {
            Logger::add_error_message("Failed to insert data: " . $e->getMessage());
            return null;
        }

    }

    private function get_proper_sql_for_data_insertion($sql, $arr) 
    {
        $column_names = "";
        $column_params = "";

        foreach($arr as $key=>$value) {
            $column_names .= "`" . $key . "`, "; // `column_n`, 
            $column_params .= ":" . $key . ", "; // :column_n, 
        }

        // (`column_1`, `column_2`, ..., `column_n`)
        $column_names = substr($column_names, 0, strlen($column_names) - 2);
        $column_names = '(' . $column_names . ')';

        // (:column_1, :column_2, ..., :column_n)
        $column_params = substr($column_params, 0, strlen($column_params) - 2);
        $column_params = '(' . $column_params . ')';

        // INSERT INTO `table_name` (`column_1`, `column_2`, ..., `column_n`) VALUES (:column_1, :column_2, ..., :column_n)
        $sql = $sql . " " . $column_names . " VALUES " . $column_params . ";";

        return $sql;
    }
}