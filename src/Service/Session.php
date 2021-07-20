<?php

namespace Sigfw\Service;

class Session 
{
    public static function set($key, $val) { $_SESSION[$key] = $val; }

    public static function get($key) { return $_SESSION[$key]; }

    public static function exist($key) { return isset($_SESSION[$key]); }

    public static function success($value)
    {
        $_SESSION["success"] = $value;
        return self::get("success");
    }

    public static function failure($value)
    {
        $_SESSION["failure"] = $value;
        return self::get("failure");
    }

    public static function error($value)
    {
        $_SESSION["error"] = $value;
        return self::get("error");
    }

    public static function unset($key) 
    {
        if(self::exists($key)) unset($_SESSION[$key]);
    }

    public static function unset_all()
    {
        foreach($_SESSION as $key => $value)
        {
            self::unset($key);
        }
    }

    public static function destroy() { session_destroy(); }

    public static function unset_by($keys)
    {
        if (!is_array($arr)) return;
        foreach($keys as $key)
        {
            self::unset($key);
        }

    }
}