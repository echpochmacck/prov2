<?php
class User extends Data
{
    public string $tablename = 'user';
    public object $validator;
    public object $sequrity;
    public object $asist;
    public object $response;
    public object $avatars;

    
    public string|null $id = '';

    public  string $name = '';
    public  string $nameMessage = '';
    public  bool $nameValid;
    
    public string $surname = '';
    public bool $surnameValid;
    public  string $surnameMessage = '';

    public  string $patronymic = '';
    public  bool $patronymicValid;
    public string $login = '';
    public bool $loginValid;
    public string $loginMessage = '';
    public  string $email = '';
    public  string $emailMessage = '';

    public  bool $emailValid;
    public string $password = '';
    public string $passwordMessage = '';

    public bool $passwordValid;

    public array|null $avatar = [];
    public string $password_repeat = '';
    public string | null $token = '';
    public string $rules;

    public bool $isVal;

    public string $adminLogin;
    public string $adminLoginValid;
    public string $blockMessege = '';
    public string|null $link = '';



    public string $adminPassword;
    public string|null $dateUnlock;

    public bool $adminPasswordValid;

    public $request;
    public $mysql;


    public bool $isGuest = true;
    public bool $isAdmin = false;

    public string $loginError = '';


    public function __construct($request, $mysql)
    {
        $this->request = $request;
        $this->mysql = $mysql;

        $this->validator = new Validator();
        $this->sequrity = new Sequrityy();
        $this->avatars = new Avatar($this->mysql);

        // var_dump('sadsd');die;
        if ($this->request->get('token')) {
            $this->token = $this->request->get('token');
            $this->identity();

        }
    }

    public function clearPassword(array $arr) 
    {

        if (isset($arr['password'])) {
            unset($arr['password']);
            unset($arr['password_repeat']);
        } 
        return $arr;

    }

    public function getParams(): array 
    {
        foreach ($this as $key => $value) {
            if (property_exists($this, $key.'Valid')) {
                $arr[$key] = $value;
                
                if(property_exists($this, $key.'Message')){
                    $arr[$key.'Message'] = $this->{$key.'Message'};
                }
            }
        }
        return $arr;
    }

    public function load(array $arr): void
    {

        parent::load($arr);
        if (!empty($_FILES['avatar']['name'])) {
            $this->avatar = $_FILES['avatar'];
        };
        $this->isAdmin = $this->isAdmin();
       
    }


    public function getData (string $str): array {  

        $arr = explode('&', $str);
        // $arr = explode(' ', $str);
        $arr2 = [];
        foreach($arr as $values) {
            list($key, $value) = explode('=', $values);
            $arr2[$key] = $value;
        }
        return $arr2;
    }



    public function validateRegister(): bool
    {

        $this->nameValid = $this->validator->checkString($this->name);
        $this->surnameValid = $this->validator->checkString($this->surname);
        $query = $this->mysql->checkUniqe('user', 'login', $this->login);


        $this->nameMessage = $this->validator->nameMessage($this->name);
        $this->surnameMessage = $this->validator->nameMessage($this->surname);
        $this->loginValid = $this->validator->checkString($this->login) && $query;

        $this->loginMessage = $this->validator->logMessege($this->loginValid);


        $query = $this->mysql->checkUniqe('user', 'email', $this->email);
        $this->emailValid = $this->validator->checkEmail($this->email) && $query;
        // var_dump()
        $this->emailMessage = $this->validator->emMessege($this->emailValid);


        $this->passwordValid = $this->validator->checkPassword($this->password, $this->password_repeat);
        $this->passwordMessage = $this->validator->pswdMessege($this->passwordValid);

        if ($this->patronymic) {
            $this->patronymicValid =  $this->validator->checkString($this->patronymic);
        }

        $result = true;

        return $this->validateData();
    }

    public function getErros(): array{
        $arr = get_object_vars($this);
        $arr = array_filter($arr, fn($value,$key) => str_contains($key, 'Message'),ARRAY_FILTER_USE_BOTH);
        return $arr;
    }

    public function save(): bool
    {


        if ($this->validateRegister()) {
            
            $hash = $this->sequrity->hash($this->password);


            // var_dump($this->loginValid);
            if ($this->mysql->querry(
                
                'INSERT INTO ' .
                    $this->tablename .
                    ' (name, surname, patronymic, login, email, password) ' .
                    'VALUES (' .
                    "'" . $this->name . "', " .
                    "'" . $this->surname . "', " .
                    "'" . $this->patronymic . "', " .
                    "'" . $this->login . "', " .
                    "'" . $this->email . "', " .
                    "'" .  $hash  . "')"

            )) {
                // var_dump($this->avatar);die;
                if (!empty($this->avatar)) {
                    $text = 'SELECT id '
                    .'FROM USER '
                    .'WHERE login = ' . "'$this->login'";
                    if($res = $this->mysql->querry($text))
                    $this->avatars->saveFile($this->avatar, $res[0]['id']);
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function validateLogin($login, $password)
    {

        // $check = ! $this->mysql->checkUniqe('user', 'login', $login);

        $this->loginValid = $this->validator->checkString($login);

        if ($this->loginValid){
        // $query = $this->mysql->querry(
        //     'SELECT PASSWORD '
        //     .'FROM user '
        //     .'WHERE login = ' . "'$login'"
        // );

        $this->passwordValid = $this->validator->checkMinLength($password, 6);
        $this->passwordMessage = $this->validator->pswdMessege($this->passwordValid);
    }
        return $this->loginValid && $this->passwordValid;
    }

    public function login ($login, $password): bool
    {

     $result = false;

     if ($this->validateLogin($login, $password)) {;


       if (!$this->mysql->checkUniqe('user', 'login', $login)) {
        
        $text = 'SELECT PASSWORD '
        .'FROM user '
        .'WHERE login = ' . "'$login'";

        if ($query = $this->mysql->querry($text)) {

                if (password_verify($password, $query[0]['PASSWORD'])) {

                    $text = '
                    SELECT *
                    FROM user
                    WHERE login = ' 
                    ."'$login'";

                    // var_dump($this->mysql->querry($text));
                    if ($query = $this->mysql->querry($text)){
                        $this->load($query[0]);

                        $this->UpdateToken();

                                $this->isAdmin = $this->isAdmin();

                                $result = true;
                    }
                }

        }   
    }

    }
    return $result;
    }
    public function UpdateToken(): void
    {
        $this->token = Asist::setToken();
        // var_dump($this->token);
        if ($this->mysql->querry(
                'UPDATE USER '
                .'SET token = '
                ."'$this->token'"
                .' WHERE '
                .'login = ' . "'$this->login'"
          )); 
         
    }


    public function identity (string|bool $id = false): void
    {
        if ($id) {
            $this->load($this->mysql->querry('
            SELECT *
            FROM user
            WHERE id = ' 
            ."'$id'")[0]);

            $text3 = 'SELECT link '
            . 'FROM avatar '
            . 'WHERE '
            . 'user_id =' .  $this->id;
            // var_dump($text3);
            if ($res = $this->mysql->querry($text3))
            $this->link = $res[0]['link'];
            // var_dump($this->link);
    
        } else {

        $text = 'SELECT * '
        .'FROM ' 
        .'user '
        .'WHERE ' 
        ."token = '$this->token'";
        if ($this->mysql->querry($text)){
            
        
            $this->load($this->mysql->querry('
            SELECT *
            FROM user
            WHERE token = ' 
            ."'$this->token'" )[0]);
            $this->isGuest = false;
        } else {
            $this->token = null;
        }
        }
        
        
    }
    


    public function isAdmin(): bool
    {
     
       $query = $this->mysql->querry("
        SELECT *
        FROM user 
        where role_id = 
        (SELECT id from role 
        WHERE title = 'admin')"
    );

      return  $this->login == $query[0]['login'] && $this->password === $query[0]['password'];
        
    }

    public function logout (): bool
    {
        $text = 'UPDATE '
        .'user '
        .'SET ' 
        .'token = null '
        .'WHERE '
        .'login = ' . "'$this->login'";
        
        if ($this->mysql->querry($text)) {
            $this->token = null;
            $this->id = null;
            return true;
        } else {
            // var_dump($text, $this->mysql->error);
            return false;
        }
    }


    public function checkUnBlock(): bool
    {   
        // var_dump(date('Y-m-d H:i:s'),$this->dateUnlock );
        return  (strtotime(date('Y-m-d H:i:s')) > strtotime($this->dateUnlock));
    }

}