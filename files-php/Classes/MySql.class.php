<?php
class Mysql extends mysqli
{
    public bool $isConnected = false;
    public function __construct(array $arr)
    {

        $this->isConnected = parent::__construct(
            $arr['host'],
            $arr['user'],
            $arr['password'],
            $arr['basename']
        );

        // parent::__construct(
        //     $arr['host'],
        //     $arr['user'],
        //     $arr['password'],
        //     $arr['basename']
        // );

        if (empty(parent::$connect_error) && empty(parent::$connect_errno)) {
            $this->isConnected = true;
        } else {

            $this->isConnected = false;
            echo 'Ошибка с бд' .
                parent::$connect_error .
                parent::$connect_errno;
                die;
        }
    }

    public function querry(string $querry, $result = false): array|bool
    {
        if ($querry && $this->isConnected){
        try{
            $result =  parent::query($querry);
            if (!is_bool($result)) {
            if ($result = $result->fetch_all(MYSQLI_ASSOC)) {
                
            }}
        } catch(Exception) {
        
        }}
        return $result ;
    }







    public function checkUniqe(string $tableName, string $pole, string $value): bool
    {
        if (count($this->querry("
            SELECT $pole 
            FROM 
            $tableName 
            WHERE $pole = '$value';
        ")) === 0) {
            return true;
        } else {
            return false;
        }
    }
}

// $new = new Mysql([
//     'host'=>'127.0.0.1',
//     'user'=>'root',
//     'password'=>'root',
//     'basename'=>'forum'
// ]);

// var_dump($new->isConnected);
// var_dump($new->querry('SELECT * from user'));
// var_dump($new->checkUniqe('user', 'id', '1'));
