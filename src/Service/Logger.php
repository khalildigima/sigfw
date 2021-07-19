<?php

namespace Sigfw\Service;

class Logger 
{
    private static $error_messages = [];
    private static $error_messages_count = 0;
    
    public static function add_error_message($message)
    {
        
        array_unshift(self::$error_messages, $message);
        self::$error_messages_count++;
    }

    public static function get_error_messages()
    {
        return self::$error_messages;
    }

    public static function get_last_error()
    {
        if(self::$error_messages_count < 1) return "";
        
        return self::$error_messages[0];
    }


}