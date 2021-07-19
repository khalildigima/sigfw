<?php 
namespace Sigfw\Service;

class Config
{
    private $debug = true;
    private $deploy = false;

    private $development = null;
    private $production = null;

    public function set_debug($value) { $this->debug = $value; }
    public function is_debug() { return $this->debug; }

    public function set_deploy($value) { $this->deploy = $value; }
    public function is_deploy() { return $this->deploy; }

    private function validate_configuration($config)
    {
        if(!is_array($config)) return false;

        if(!array_key_exists("host", $config)) return false;
        if(!array_key_exists("username", $config)) return false;
        if(!array_key_exists("password", $config)) return false;
        if(!array_key_exists("dbname", $config)) return false;

        return true;
    }

    public function set_development_config($config)
    {
        if(!$this->validate_configuration($config))
        {
            $this->development = null;
            return null;    
        }

        // for development or local hosting
        $this->development = array(
            "host" => $config["host"],
            "username" => $config["username"],
            "password" => $config["password"],
            "dbname" => $config["dbname"]
        );
    }

    public function get_development_config() { return $this->development; }

    
    public function set_production_config($config)
    {
        if(!$this->validate_configuration($config))
        {
            $this->production = null;
            return null;    
        }
        
        // for production or local hosting
        $this->production = array(
            "host" => $config["host"],
            "username" => $config["username"],
            "password" => $config["password"],
            "dbname" => $config["dbname"]
        );
    }

    public function get_production_config() { return $this->production; }

}