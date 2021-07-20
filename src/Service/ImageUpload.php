<?php 

namespace Sigfw\Service;

use Sigfw\Service\Logger;
use Sigfw\Service\FileUpload;

class ImageUpload extends FileUpload
{
    public $maxDim = 100;
    public function __construct()
    {
        $this->extensions = array(
            "jpg",
            "png",
            "jpeg",
            "bmp",
            "webp",
            "tiff",
            "gif",
            "jp2",
        );

    }

    public function upload($target_dir, $key, $index = -1)
    {
        if(!is_dir($target_dir . "/."))
        {
            Logger::add_error_message("Directory does not exists");
            return null;
        }
        
        $time = time();
        
        $file_name = "";
		if($index < 0)
		{
			$file_name = $_FILES[$key]['tmp_name'];
			$tfile = $time . '-' . basename($_FILES[$key]['name']);
		}
		else
		{
			$file_name = $_FILES[$key]['tmp_name'][$index];
			$tfile = $time . '-' . basename($_FILES[$key]['name'][$index]);
		}
		
		if(!$file_name) return null;

        $target_filename = $target_dir . $tfile;

        list($width, $height, $type, $attr) = getimagesize( $file_name );
        $dst = $this->create_image($file_name, $width, $height);
        if(!$dst)
        {
            if(move_uploaded_file($file_name, $target_filename))
			{
				return $tfile;
			}
			else
			{
                Logger::add_error_message("Failed to upload image");
				return null;
			}
        }
        else
        {
            $moved = $this->move_created_image_file($dst, $target_filename, $type);
			imagedestroy( $dst );
			
            if ($moved) return $tfile;
            else 
            { 
                Logger::add_error_message("Failed to upload compressed image");
                return null;
            }
		}
		
    }

    private function create_image($file_name, $width, $height)
    {
        
        if ( $width < $this->maxDim && $height < $this->maxDim ) return null;

        $ratio = $width/$height;
        if( $ratio > 1) 
        {
            $new_width = $this->maxDim;
            $new_height = $this->maxDim / $ratio;
        } 
        else 
        {
            $new_width = $this->maxDim * $ratio;
            $new_height = $this->maxDim;
        }

        $src = imagecreatefromstring( file_get_contents( $file_name ) );
        $dst = imagecreatetruecolor( $new_width, $new_height );
        imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
        imagedestroy( $src );

        return $dst;
    }

    private function move_created_image_file($dst, $target_filename, $type)
    {
        switch ($type)
        {
            case IMAGETYPE_JPEG:
                $image = imagejpeg( $dst, $target_filename );
                break;
                
            case IMAGETYPE_GIF:
                $image = imagegif( $dst, $target_filename );
                break;
                
            case IMAGETYPE_PNG:
                $image = imagepng( $dst, $target_filename );
                break;
                
            case IMAGETYPE_JPEG2000:
                $image = imagejpeg( $dst, $target_filename );
                break;
            
            case IMAGETYPE_BMP:
                $image = imagebmp( $dst, $target_filename );
                break;
            
            case IMAGETYPE_WBMP:
                $image = imagewbmp( $dst, $target_filename );
                break;
            
            case IMAGETYPE_JP2:
                $image = imagejpeg( $dst, $target_filename );
                break;
                
            default:
                Logger::add_error_message("Failed to upload image");
                return false;
        }

        return true;
    }
}