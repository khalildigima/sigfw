<?php

namespace Sigfw\Service;

use Sigfw\Service\Config;
use Sigfw\Service\Logger;
use PDO;

class Connection
{
    private $config = null;
    
    
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function get_connection()
    {
        $con = null;
        try 
        {
            if(!$this->config->is_deploy()) {

                // connection for offline or development
                $development = $this->config->get_development_config();
                if(!$development)
                {
                    Logger::add_error_message("Development database configuration not provided");
                    return null;
                }

                $con = new PDO(
                    'mysql:host=' . $development["host"] .';dbname=' . $development["dbname"],
                    $development["username"], 
                    $development["password"],
                );
                
            } else {

                // connection for online or production
                $production = $this->config->get_production_config();
                $con = new PDO(
                    'mysql:host=' . $production["host"] .';dbname=' . $production["dbname"],
                    $production["username"], 
                    $production["password"],
                );

                if(!$production)
                {
                    Logger::add_error_message("Production database configuration not provided");
                    return null;
                }
            }

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