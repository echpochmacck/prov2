<?php

class Data
{
    public function validateData(): bool
    {
        $result = true;

        $arr = get_object_vars($this);

        foreach ($arr as $key => $val) {
            if (str_contains($key, 'Valid')) {
                
                if ($val === false) {
                    $result = false;
                    break;
                }
            }

        }
        return $result;
    }

    public function load(array $arr): void
    {

        foreach ($arr as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
       
    }
    
    
    public function replaceSmth(string $str): string  
    {
        return preg_replace('/\r\n|\r|\n/u', '<br/>', $str);
    }

    public function  replaceBr(string $str): string
    {
        return str_replace('<br/>', "\r\n", $str);
    }

    public function doDate(string $date): string
    {
         $date = new DateTime($date);
         return $date->format('d.m.Y H:i:s');
    }

    public function formdata(string $date): string
    {
        $date = new DateTime($date);
        return $date->format('Y-m-d H:i:s');

    }


    
}
