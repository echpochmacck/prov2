<?php
class Request
{
    public bool $isPost = false;
    public bool $isGet = false;

    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->isPost = true;
        }else{
            $this->isGet = true;
        }
    }

    
    public function clear(string|array $param) : string
    {
        return trim(strip_tags($param));
    }

    public function clearArr(array $arr):array
    {
        foreach($arr as $item) {

            if (is_array($item)) {
                $item=$this->clearArr($item);
            } else {
                $item = $this->clear($item);
            }

        }
        return $arr;
        
    }


    public function post(mixed $param = false):array|string|null
    {
        if ($param) {
            if(array_key_exists($param, $_POST)) {
                return $this->clear($_POST[$param]);
            }else{
                return null;
            } 

        } else {
        }
            return $this->clearArr($_POST);
        
    } 


    public function get(mixed $param = false):array|string|null{

        if ($param) {

            if (array_key_exists($param, $_GET)) {
                return $this->clear($_GET[$param]);
            }else{
                return null;
            }
            
        }else{
            return $this->clearArr($_GET);
        }

    } 

    public function host(): string
    {
        return $_SERVER['SERVER_NAME'];
    }
    

    public function token(): string|null
    {
        return $this->get('token');
    }

      public function getUrl(): array
    {
        return parse_url($_SERVER['REQUEST_URI']);
    }
}


// $obj = new Request();

// echo $obj-> host();
// echo '<br>';
// var_dump($obj-> get(''));


?>