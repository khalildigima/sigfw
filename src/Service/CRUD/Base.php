<?php 

namespace Sigfw\Service\CRUD;

use Sigfw\Service\Connection;
use Sigfw\Service\Logger;

class Base
{
    private $table_name = "";
    private $connection = null;

    public function __construct($table_name)
    {
        $this->set_table_name($table_name);
        $this->set_connection(new Connection());
    }

    public function set_table_name($table_name) {  $this->table_name = $table_name; }
    public function get_table_name() { return $this->table_name; }

    public function set_connection($connection) 
    {
        $this->connection = $connection;
    }

    public function get_connection()
    {
        if($this->connection)
        {
            return $this->connection->get_connection();
        }

        return null;
    }

    public function verification_connection()
    {
        if($this->get_table_name() == "")
        {
            Logger::add_error_message("table name is empty");
            return null;
        }
        
        $con = $this->get_connection();
        if(!$con)
        {
            Logger::add_error_message("No database connection");
            return null;
        }

        return $con;
    }
}