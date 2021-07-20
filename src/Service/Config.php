<?php 
namespace Sigfw\Service;

use Sigfw\Service\Logger;

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

    public function __construct()
    {
        $file = __DIR__ . "/.env";
        $this->load_configuration_from_file($file);
    }

    public function load_configuration_from_file($file)
    {
        $this->load_env($file);
        
        $_development = array(
            "host" => getenv('DEVELOPMENT_HOSTNAME'),
            "username" => getenv('DEVELOPMENT_USERNAME'),
            "password" => getenv('DEVELOPMENT_PASSWORD'),
            "dbname" => getenv('DEVELOPMENT_DBNAME')
        );

        $_production = array(
            "host" => getenv('PRODUCTION_HOSTNAME'),
            "username" => getenv('PRODUCTION_USERNAME'),
            "password" => getenv('PRODUCTION_PASSWORD'),
            "dbname" => getenv('PRODUCTION_DBNAME')
        );

        $this->debug = getenv('DEBUG');
        $this->deploy = getenv('DEPLOY');

        $this->set_development_creds($_development);
        $this->set_production_creds($_production);

    }

    public function load_env($file)
    {
        if(!file_exists($file)) 
        {
            Logger::add_error_message("Config: ENV file not found");
            return;
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) 
        {

            if (strpos(trim($line), '#') === 0) { continue; }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) 
            {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
    
    public function validate_creds($creds)
    {
        if(!is_array($creds)) return false;

        if(!array_key_exists("host", $creds)) return false;
        if(!array_key_exists("username", $creds)) return false;
        if(!array_key_exists("password", $creds)) return false;
        if(!array_key_exists("dbname", $creds)) return false;

        return true;
    }

    public function set_development_creds($creds)
    {
        if(!$this->validate_creds($creds))
        {
            $this->development = null;
            return null;    
        }

        // for development or local hosting
        $this->development = array(
            "host" => $creds["host"],
            "username" => $creds["username"],
            "password" => $creds["password"],
            "dbname" => $creds["dbname"]
        );
    }

    public function get_development_creds() { return $this->development; }

    
    public function set_production_creds($creds)
    {
        if(!$this->validate_creds($creds))
        {
            $this->production = null;
            return null;    
        }
        
        // for production or local hosting
        $this->production = array(
            "host" => $creds["host"],
            "username" => $creds["username"],
            "password" => $creds["password"],
            "dbname" => $creds["dbname"]
        );
    }

    public function get_production_creds() { return $this->production; }

}