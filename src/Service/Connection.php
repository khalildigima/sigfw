<?php

namespace Sigfw\Service;

use Sigfw\Service\Config;
use Sigfw\Service\Logger;
use PDO;

class Connection
{
    private $config = null;
    
    
    public function __construct()
    {
        $this->config = new Config();
    }

    public function get_connection()
    {
        $con = null;
        $_config = null;
        try 
        {
            
            if(!$this->config->is_deploy()) 
            {
                $_config = $this->config->get_development_config();
            }
            else
            {
                $_config = $this->config->get_production_config();
            }

            if(!$_config)
            {
                Logger::add_error_message("DB config is empty");
                return null;
            }


            $con = new PDO(
                'mysql:host=' . $_config["host"] .';dbname=' . $_config["dbname"],
                $_config["username"], 
                $_config["password"],
            );
            

            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch(\PDOException $e) 
        {
            if(!$con) 
            { 
                Logger::add_error_message("Failed to connect: " . $e->getMessage());
            }
        }

        return $con;
    }

    public function set_last_error($message) { $this->error = $message; }
    
    public function get_last_error() { return $this->error; }

    /* extremely insecure and dangerous */
    public function raw_query($sql) 
    {
        $conn = $this->get_connection();
        if(!$conn) return $this->set_last_error("Connection does not exists");
        
        $stmt = $conn->prepare($sql);

        try 
        {
            $stmt->execute();
            return $stmt;
        } catch(\PDOException $e) 
        {
            Logger::add_error_message("Failed to execute raw sql: " . $e->getMessage());
        }
    }
}