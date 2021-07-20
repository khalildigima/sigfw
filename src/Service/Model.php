<?php

namespace Sigfw\Service;

use Sigfw\Service\CRUD\Base;
use Sigfw\Service\CRUD\Creater;
use Sigfw\Service\CRUD\Reader;
use Sigfw\Service\CRUD\Updater;
use Sigfw\Service\CRUD\Deleter;

class Model extends Base
{
    private $reader = null;
    private $creater = null;
    
    public function __construct($table_name)
    {
        parent::__construct($table_name);
        
        $this->creater = new Creater($table_name);
        $this->reader = new Reader($table_name);
        $this->updater = new Updater($table_name);
        $this->deleter = new Deleter($table_name);
    }

    /**************************
     * CREATER CLASS FUNCTIONS *
     ***************************/
    public function insert($arr) { return $this->creater->insert($arr); }
    
    /**************************
     * READER CLASS FUNCTIONS *
     ***************************/
    public function get_all() { return $this->reader->get_all(); }
    public function get_all_desc() { return $this->reader->get_all_desc(); }
    public function get_by($column_name, $value) { return $this->reader->get_by($column_name, $value); }
    public function get_by_id($id) { return $this->reader->get_by_id($id); }
    public function get_all_by($column_name, $value) { return $this->reader->get_all_by($column_name, $value); }
    public function get_number_of_pages($limit) { return $this->reader->get_number_of_pages($limit); }
    public function get_page($page, $limit = 10) { return $this->reader->get_page($page, $limit = 10); }

    /**************************
     * UPDATER CLASS FUNCTIONS *
     ***************************/
    public function update($id, $arr) { return $this->updater->update($id, $arr); }
    public function update_by($column_name, $column_value, $arr) 
    { 
        return $this->updater->update_by($column_name, $column_value, $arr); 
    }

    /**************************
     * DELETER CLASS FUNCTIONS *
     ***************************/
    public function delete($id) {  return $this->deleter->delete($id); }
    public function delete_by($column_name, $column_value) 
    {  
        return $this->deleter->delete_by($column_name, $column_value); 
    }
}