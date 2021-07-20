<?php 
namespace Sigfw\Service;

use Sigfw\Service\Logger;

class FileUpload
{

    public $allowed_file_size = 1 * 1024 * 1024; // 1 MB
    public $extensions = [];

    public function verify_file($key, $index = -1)
    {
        if($index < 0)
        {
            $file_size = $_FILES[$key]['size'];
        }
        else
        {
            $file_size = $_FILES[$key]['size'][$index];
        }

        if(!$this->valid_size($file_size)) return false;
        
        
        if($index < 0)
            $ext_arr = explode( '.', $_FILES[$key]['name'] );
        else
            $ext_arr = explode( '.', $_FILES[$key]['name'][$index] );
            

        if(!$this->valid_extension($ext_arr)) return false;
        
		return true;
    }

    private function valid_size($file_size)
    {
        
        if($file_size > $this->allowed_file_size)
        {
            $temp_size = $this->allowed_file_size / 1024 / 1024;
            $temp_size = number_format((float)$temp_size, 2, '.', '');
            $temp_size_2 = $file_size / 1024 / 1024;
            $temp_size_2 = number_format((float)$temp_size_2, 2, '.', '');
            Logger::add_error_message("File size should be less than $temp_size MB. Yours is $temp_size_2 MB");
            return false;
        }

        if($file_size < 1)
        {
            Logger::add_error_message("No file to upload");
            return false;
        }

        return true;
    }

    private function valid_extension($ext_arr)
    {
        if(!$this->extensions) return true;
        if(!is_array($this->extensions)) return true;
        if (count($this->extensions) < 1) return true;


        $ext_raw = end($ext_arr);
        $file_ext = strtolower( $ext_raw );

        if(in_array($file_ext, $this->extensions) === false)
        {
            $temp = implode(", ", $this->extensions);
            $temp = strtoupper($temp);
            Logger::add_error_message("Invalid file. Only extension allowed: $temp");
            return false;
        }

        return true;
    }
    
    public function upload($target_dir, $key, $index = -1)
    {
        if(!is_dir($target_dir . "/."))
        {
            Logger::add_error_message("Directory does not exists");
            return null;
        }

        $time = time();
        if($index < 0)
            $target_filename = $time . '-' . basename($_FILES[$key]["name"]);
        else 
            $target_filename = $time . '-' . basename($_FILES[$key]["name"][$index]);
            
        $target_file = $target_dir . $target_filename;

        if($index < 0)
        { 
            if (move_uploaded_file($_FILES[$key]["tmp_name"], $target_file))
            {
                return $target_filename;
            } 
        }
        else
        {
            if (move_uploaded_file($_FILES[$key]["tmp_name"][$index], $target_file))
            {
                return $target_filename;
            } 
        }

        return null;
    }
}