<?php

namespace Sigfw\Service;

use Sigfw\Service\Session;
use Sigfw\Service\Logger;
use Sigfw\Service\Utils;

class Cart 
{
    private $model = null;

    private $key = "";

    public function __construct()
    {
        $this->key = "cart";
        if(!Session::exist($this->key))
        {
            Session::set($this->key, []);
        }
    }

    public function set_product_model($model)
    {
        $this->model = $model;
    }

    public function set_key($key)
    {
        $this->key = $key;
        if(!Session::exist($this->key))
        {
            Session::set($this->key, []);
        }
    }

    public function add_item($item_id, $quantity = 1)
    {
        $item_id = Utils::clean_string($item_id);
        $quantity = Utils::clean_string($quantity);
        $quantity = intval($quantity);
        if($quantity < 1) $quantity = 1;

        if(!$this->model) return;
        $product = $this->model->get_by_id($item_id);
        if(!$product) return;

        if(empty(Session::get($this->key)))
        {
            $arr = [];
			$item = array("product_id" => $item_id, "quantity" => $quantity);
			array_push($arr, $item);
			Session::set($this->key, $arr);
        }
        else
        {
            if($this->item_exists($item_id)) return;
            
            $arr = Session::get($this->key);
            $item = array(
                "product_id" => $item_id,
                "quantity" => $quantity
            );

            array_push($arr, $item);

            Session::set($this->key, $arr);
        }

    }

    public function item_exists($item_id)
    {
        $arr = Session::get($this->key);
        foreach($arr as $item) 
        {
			if($item["product_id"] == $item_id) return true;
		}
		
		return false;
    }

    public function remove_item($item_id) 
    {
	
		$item_id = Utils::clean_string($item_id);
		
		if(!$this->item_exists($item_id)) return; // product not found in cart
		
		$arr = Session::get($this->key);
		
		$index = 0;
		foreach($arr as $item) 
        {
			if($item["product_id"] == $item_id) 
            {
				break;
			}
			$index++;
		}
		
		array_splice($arr, $index, 1);
		
		Session::set($this->key, $arr);
		
	}

    public function clear() 
    {
		Session::set($this->key, []);
	}

    public function change_item_quantity($item_id, $quantity) 
    {
		
		$item_id = Utils::clean_string($item_id);
        $quantity = Utils::clean_string($quantity);
        $quantity = intval($quantity);
        if($quantity < 1) $quantity = 1;

		if(!$this->model) return;
        $product = $this->model->get_by_id($item_id);
        if(!$product) return;
		
		if(empty(Session::get($this->key))) return;
		
		if(!$this->item_exists($item_id)) return;

		$arr = Session::get($this->key);

		$index = 0;
		foreach($arr as $item) 
        {
			if($item["product_id"] == $item_id) 
            {		
				$arr[$index]["quantity"] = $quantity;
				break;
			}

			$index++;
		}

		Session::set($this->key, $arr);

	}
    
    public function get() { return Session::get($this->key); }
}