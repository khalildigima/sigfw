<?php 

namespace Sigfw\Service\CRUD;

use Sigfw\Service\CRUD\Base;
use Sigfw\Service\Logger;
use Sigfw\Service\Utils;
use PDO;
use PDOException;

class Updater extends Base
{
    public function __construct($table_name)
    {
        parent::__construct($table_name);
    }

    public function update_by($column_name, $column_value, $arr) 
    {
        $con = $this->verification_connection();
        if(!$con) return null;
				
        $arr = Utils::clean_array($arr);
        $column_name = Utils::clean_string($column_name);
        $column_value = Utils::clean_string($column_value);

        $sql = "UPDATE `{$this->get_table_name()}` SET ###data### WHERE `{$column_name}`=:column_value;";
        $sql = $this->get_proper_sql_for_updating_data($sql, $arr);
		
        $stmt = $con->prepare($sql);

        foreach($arr as $key=>$value) 
        {
            $stmt->bindParam(':' . $key, $arr[$key]);
        }
        
        $stmt->bindParam(':column_value', $column_value);

        try 
        {
            return $stmt->execute();
        } 
        catch(PDOException $e) 
        {
            Logger::add_error_message("Failed to update data: " . $e->getMessage());
            return null;
        }

    }

    private function get_proper_sql_for_updating_data($sql, $arr) 
    {

        $data = "";
        // data = `column_name_n`=:column_name_n, ...
        foreach($arr as $key=>$value) 
        {
            $data .= "`" . $key . "` = :" . $key . ", ";
        }

        $data = substr($data, 0, strlen($data) - 2);

        $sql = str_replace("###data###", $data, $sql);

        return $sql;
    }

    public function update($id, $arr) 
    {
        return $this->update_by("id", $id, $arr);
    }
}