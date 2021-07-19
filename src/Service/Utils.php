<?php 

namespace Sigfw\Service;

class Utils
{
    public static function clean_string($str)
    {
        $s = "";
        $s = htmlspecialchars($str, ENT_QUOTES);
        return $s;
    }

    public static function clean_array($arr) 
    {
        if(!is_array($arr)) return $arr;
        
        foreach($arr as $key=>$value)
        {
            $arr[$key] = $this->clean_string($value);
        }
        
        return $arr;
    }

    public static function format_date($date, $format = "d M Y")
	{
		$date_created = date_create($date);
		return date_format($date_created, $format);
	}

    // DANGEROUS FUNCTION: danger of xss
	public static function deformat_html($text)
	{
		$text = str_replace("&lt;", "<", $text);
		$text = str_replace("&gt;", ">", $text);
		$text = str_replace("&amp;", "&", $text);
		
		return $text;	
	}

    public static function dump($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
}