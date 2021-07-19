<?php 

namespace Sigfw\Service\CRUD;

use Sigfw\Service\CRUD\Base;
use Sigfw\Service\Logger;
use Sigfw\Service\Utils;
use PDO;
use PDOException;

class Deleter extends Base
{
    public function __construct($table_name)
    {
        parent::__construct($table_name);
    }

    public function delete_by($column_name, $column_value)
    {
        $con = $this->verification_connection();
        if(!$con) return null;
        
        $column_name = Utils::clean_string($column_name);
        $column_value = Utils::clean_string($column_value);

        $sql = "DELETE FROM `{$this->get_table_name()}` WHERE `{$column_name}`=:column_value;";
        
        try
        {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':column_value', $column_value);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
        catch(PDOException $e) 
        {
            Logger::add_error_message("Failed to update data: " . $e->getMessage());
            return null;
        }

    }

    public function delete($id) { return $this->delete_by("id", $id); }
}