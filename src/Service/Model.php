<?php

namespace Sigfw\Service;

use Sigfw\Service\CRUD\Reader;
use Sigfw\Service\CRUD\Creater;

class Model
{
    private $reader = null;
    private $creater = null;
    public function __construct($table_name)
    {
        $this->reader = new Reader($table_name);
        $this->creater = new Creater($table_name);
    }

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
     * CREATER CLASS FUNCTIONS *
     ***************************/
    public function insert($arr) { return $this->creater->insert($arr); }
} 