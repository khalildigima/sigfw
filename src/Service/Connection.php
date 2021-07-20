<?php

namespace Sigfw\Service;

use Sigfw\Service\Config;
use Sigfw\Service\Logger;
use PDO;

class Connection
{
    private $config = null;
    private $creds = null;
    
    public function __construct()
    {
        $this->config = new Config();

        if(!$this->config->is_deploy()) 
        {
            $this->creds = $this->config->get_development_creds();
        }
        else
        {
            $this->creds = $this->config->get_production_creds();
        }

        if(!$this->creds)
        {
            Logger::add_error_message("DB config is empty");
            return null;
        }
    }

    public function get_connection($creds = null)
    {

        $con = null;
        $_creds = null;
        
        if($this->config->validate_creds($creds))
        {
            $_creds = $creds;
        }
        else 
        {
            $_creds = $this->creds;
        }
        
        try 
        {
            
            $con = new PDO(
                'mysql:host=' . $_creds["host"] .';dbname=' . $_creds["dbname"],
                $_creds["username"], 
                $_creds["password"],
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