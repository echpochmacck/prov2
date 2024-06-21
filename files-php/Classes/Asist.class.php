<?php

class Asist
{
    public static function prePareStr(array $arr): string 
    {
        return serialize($arr);
    }

    public static function getData(string $str): array
    {
        return unserialize($str);
    }

    public static function encode(string $str): string 
    {
        return base64_encode($str);
    }


    public static function deCode(string $str)
    {
        return base64_decode($str);
    }
    
    public static function setToken(): string
    {
        return  bin2hex(random_bytes(20));
    }

}
