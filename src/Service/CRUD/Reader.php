<?php 

namespace Sigfw\Service\CRUD;

use Sigfw\Service\CRUD\Base;
use Sigfw\Service\Logger;
use Sigfw\Service\Utils;
use PDO;
use PDOException;

class Reader extends Base
{
    public function __construct($table_name)
    {
        parent::__construct($table_name);
    }
    
    public function get_all()
    {
        $con = $this->verification_connection();
        if(!$con) return null;

        $sql = "SELECT * FROM `{$this->get_table_name()}`;";
        
        try
        {
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }
        catch(PDOException $e) 
        {
            Logger::add_error_message("Failed to get data: " . $e->getMessage());
            return null;
        }
    }

    public function get_all_desc() 
    {
        $con = $this->verification_connection();
        if(!$con) return null;

        $sql = "SELECT * FROM `{$this->get_table_name()}` ORDER BY `id` DESC;";
        
        try
        {
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }
        catch(PDOException $e) 
        {
            Logger::add_error_message("Failed to get data: " . $e->getMessage());
            return null;
        }
    }

    public function get_by($column_name, $value) 
    {
        $con = $this->verification_connection();
        if(!$con) return null;

        $column_name = Utils::clean_string($column_name);
        $value = Utils::clean_string($value);

        $sql = "SELECT * FROM `{$this->get_table_name()}` WHERE `{$column_name}`=:value;";

        try
        {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $data = $stmt->fetchAll();
            if(count($data) < 1) return null;

            return $data[0];
        }
        catch(PDOException $e) 
        {
            Logger::add_error_message("Failed to get data: " . $e->getMessage());
            return null;
        }

    }

    public function get_by_id($id) { return $this->get_by("id", $id); }

    public function get_all_by($column_name, $value) 
    {
        $con = $this->verification_connection();
        if(!$con) return null;

        $column_name = Utils::clean_string($column_name);
        $value = Utils::clean_string($value);

        $sql = "SELECT * FROM `{$this->get_table_name()}` WHERE `{$column_name}`=:value;";

        try
        {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }
        catch(PDOException $e) 
        {
            Logger::add_error_message("Failed to get data: " . $e->getMessage());
            return null;
        }
    }

    public function get_number_of_pages($limit) 
    {
        if($limit < 1) $limit = 1;
        $data = $this->get_all();
        $total = count($data);
        return ceil($total / $limit);
    }

    public function get_page($page, $limit = 10) 
    {
        $con = $this->verification_connection();
        if(!$con) return null;

        if($page < 1) $page = 1;
        if($limit < 1) $limit = 1;
        $total_pages = $this->get_number_of_pages($limit);
        if($page > $total_pages) $page = $total_pages;

        $offset = ( $page - 1 ) * $limit;

        $sql = "SELECT * FROM `{$this->get_table_name()}` LIMIT {$limit} OFFSET {$offset};";

        try
        {
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }
        catch(PDOException $e) 
        {
            Logger::add_error_message("Failed to get data: " . $e->getMessage());
            return null;
        }

    }
}