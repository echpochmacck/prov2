<?php
class Avatar 
{

    public string $token = '';
    public string $file_name = '';
    public string $tmp_name = '';

    public string $extension = '';
    public string $dir = '';
    public object $mysql;


    public function __construct($mysql)
    {
        $this->mysql = $mysql;
    }

    public function getTmpName(array $str): void
    {
        // var_dump($str);die;
        $this->tmp_name = $str['tmp_name'];
    }
    public function getExtension(array $str): void
    {
        $this->extension = pathinfo($str['name'])['extension'];
    }

    public function getToken(): void
    {
        $this->token = bin2hex(random_bytes(20));
    }

    public function getDir()
    {
        $this->getToken();
        $this->file_name = time() . "_$this->token.$this->extension";
        $this->dir =   __DIR__ . '/../../uploads/';
        // var_dump($this->dir);die;
    }


    public function saveFile(array $str, $id = false): bool
    {
        $this->getTmpName($str);
        $this->getExtension($str);
        $this->getDir();
        $result = false;
        // var_dump();die;
        if (move_uploaded_file($this->tmp_name, $this->dir . $this->file_name)) {
            $text = ' SELECT link '
            .'FROM avatar '
            .'where user_id = '.$id;
            // var_dump($this->mysql->querry($text));die;
            // var_dump($text);die;
            if (empty($this->mysql->querry($text))){
                $text = 'INSERT into avatar '
                . ' (link, user_id) '
                . "VALUES ('$this->file_name', '$id') ";
                // var_dump($this->mysql->querry($text));die;
                if ($this->mysql->querry($text)) {
                    $result = true;
                }} else {
                    // var_dump($text);die;
                    $text1 = 'SELECT link '
                    .'FROM avatar '
                    .'WHERE user_id = ' . $id;
                    if ($res = $this->mysql->querry($text1)) {
                        if (file_exists(__DIR__.'/../uploads/'.$res[0]['link'])) {
                            unlink(__DIR__.'/../uploads/'.$res[0]['link']);
                        }

                    }
                    $text = 'UPDATE '
                    .'avatar '
                    .'SET ' 
                    .'link = '. "'$this->file_name'"
                    .'WHERE '
                    .'user_id = ' . $id;
                        // var_dump($this->mysql->querry($text));die;
                    if ($this->mysql->querry($text)) {
                        $result = true;
            }
        }
        return $result;
    }
}}