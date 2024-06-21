<?php
class Request
{
    public bool $isPost;
    public bool $isGet;

    public function __construct()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
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
        foreach($arr as $item){

            if(is_array($item)){
                $item=$this->clearArr($item);
            }else{
                $item = $this->clear($item);
            }

        }
        return $arr;
        
    }


    public function post(mixed $param):array|string{

        if(!empty($param)){

            if(array_key_exists($param,$_POST)){
                return $this->clear($_POST[$param]);
            }else{
                return null;
            }

        }else{
            return $this->clearArr($_POST);
        }

    } 


    public function get(string $param):array|string{

        if(!empty($param)){

            if(array_key_exists($param,$_GET)){
                return $this->clear($_GET[$param]);
            }else{
                return null;
            }
            
        }else{
            return $this->clearArr($_GET);
        }

    } 

    public function host():string{
        return $_SERVER['REMOTE_HOST'];
    }
    

    public function token():string|null{
        return $this->get('token');
    }

  

    
}


?>