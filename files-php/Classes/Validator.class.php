<?php

class Validator
{

    public function checkUnEmpty(string $str): bool
    {
        return !empty($str);
    }

    public function checkMaxLength(string $str, int $max): bool
    {
        return mb_strlen($str) <= $max;
    }
     public function checkMinLength(string $str, int $min): bool
     {
        return mb_strlen($str) >= $min;
     }


     public function checkEmail(string $str): mixed
     {
        return $this->checkUnEmpty($str) && filter_var($str, FILTER_VALIDATE_EMAIL);
     }
     

    public function checkString(string $name): bool
    {
        return  $this->checkUnEmpty($name) && $this->checkMaxLength($name, 255);
    }

    
      public function checkLoginn (bool $name): bool{
        return $name;
      }  

    public function checkPassword(string $pswd, string $pswdRp): bool
    {
        return $this->checkString($pswd) && $this->checkMinLength($pswd, 6) && $pswd === $pswdRp;
    }

    public function nameMessage(string $name): string
    {
        if (!$this->checkunEmpty($name)) return 'поле должно быть заполнено';
        else if (!$this->checkMaxLength($name, 255)) return 'поле должно быть меньше 255 символов';
        else return '';
    } 

    public function logMessege(bool $log) :string  
    {
        return ! $log ? 'такой логин уже есть' : '';
    }

    public function emMessege(bool $em): string 
    {
        return ! $em ? 'ошибка в email' : '';
    }
    
    public function pswdMessege(bool $pswd): string 
    {
       
        return ! $pswd ? 'ошибка в пароле' : '';
    }

    public function checkpPasswordLog(string $pswd, string $pswd2)
    {
        return password_verify($pswd, $pswd2);
    }

    public function loginPswdError() {
        return 'ошибка в логине или пороле';
    }
}
