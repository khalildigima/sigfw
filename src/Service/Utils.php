<?php 

namespace Sigfw\Service;

class Utils
{
    public function clean_string($str)
    {
        $s = "";
        $s = htmlspecialchars($str, ENT_QUOTES);
        return $s;
    }

    public function clean_array($arr) 
    {
        if(!is_array($arr)) return $arr;
        
        foreach($arr as $key=>$value)
        {
            $arr[$key] = $this->clean_string($value);
        }
        
        return $arr;
    }

    public function format_date($date, $format = "d M Y")
	{
		$date_created = date_create($date);
		return date_format($date_created, $format);
	}

    // DANGEROUS FUNCTION: danger of xss
	public function deformat_html($text)
	{
		$text = str_replace("&lt;", "<", $text);
		$text = str_replace("&gt;", ">", $text);
		$text = str_replace("&amp;", "&", $text);
		
		return $text;	
	}

    public function dump($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
}