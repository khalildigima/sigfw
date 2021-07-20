<?php

require_once __DIR__ . "/vendor/autoload.php";

use Sigfw\Service\Utils;
use Sigfw\Service\Logger;
use Sigfw\Service\FileUpload;
use Sigfw\Service\ImageUpload;

if(isset($_POST["upload"]))
{
    $uploader = new ImageUpload();
    $target_dir = __DIR__ . "/uploads/";
    $key = "fil";
    $uploader->allowed_file_size = 0.1 * 1024 * 1024;
    
    $valid_file = $uploader->verify_file($key);
    if($valid_file)
    {    
        $filename = $uploader->upload($target_dir, $key);
        if(!$filename)
        {
            Utils::dump(Logger::get_error_messages());
        }
        else
        {
            echo $filename;
        }
    }
    else 
    {
        Utils::dump(Logger::get_error_messages());
    }
}
?>
<html>

<head>
</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="fil">
        <button name="upload">Upload</button>
    </form>
</body>

</html>