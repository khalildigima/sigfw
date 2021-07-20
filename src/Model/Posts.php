<?php 

namespace Sigfw\Model;

use Sigfw\Service\Model;
use Sigfw\Service\Logger;
use PDO;
use PDOException;

class Posts extends Model
{
    public function __construct()
    {
        parent::__construct("posts");
    }

    public function get_custom()
    {
        $con = $this->verification_connection();
        if(!$con) return null;
        
        $sql = "SELECT * FROM `{$this->get_table_name()}` where `id` > 18;";

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