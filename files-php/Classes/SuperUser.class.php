<?php
class SuperUser extends User
{

    public function listOfUsers()
    {
        $res = [];
        $text = 'SELECT * '
            . 'FROM '
            . 'USER ';

        if ($res = $this->mysql->querry($text)) {
        }

        return $res;
    }


    public function tempBlock(string $date, string $id): bool
    {
        $result = false;
        $date = $this->formdata($date);
        $text = 'UPDATE USER '
            . 'SET dateUnlock = ' . "'$date'"
            . ' WHERE id = ' . "'$id'";
        if ($this->mysql->querry($text)) {
            $result = true;
        }
        // var_dump($this->mysql->error);die;
        return $result;
    }


    public function foreverBlock(string $id, string $couse, string $admin_id): bool
    {
        if ($this->validator->checkUnEmpty($couse)) {
            $result = false;
            $text = '';
            $arr = $this->listOfFiles($id);
            foreach ($arr as $value) {
                // var_dump(__DIR__.'/../uploads');die;
                if ($value->file && file_exists(__DIR__ . '/../uploads/' . $value->file)) {
                    unlink(__DIR__ . '/../uploads/' . $value->file);
                }
            }



            $text = 'DELETE FROM POST '
                . 'WHERE user_id = ' . "'$id'";

            if ($this->mysql->querry($text)) {
                $text = 'UPDATE USER '
                    . "SET dateUnlock = '1970-01-01 00:00:00'"
                    . ' WHERE id = ' . "'$id'";
                if ($this->mysql->querry($text)) {
                    $date = date('Y-m-d H:i:s');
                    $text = 'INSERT into banforever (cause, admin_id, date, user_id) '
                        . 'VALUES( '
                        . "'$couse', "
                        . "'$admin_id', "
                        . "'$date', "
                        . "$id)";
                    if ($this->mysql->querry($text))
                        $result = true;
                }
                // var_dump($this->mysql->error, $text);die;
            }
        }

        return $result;
    }


    public function listOfFiles($id): array
    {


        $request = $this->request;
        $mysql = $this->mysql;
        $arr = [];

        $text = 'SELECT * '
            . 'FROM '
            . 'POST '
            . 'WHERE user_id = ' . "'$id'";

        // var_dump($this->mysql->querry($text));die;

        if ($res = $this->mysql->querry($text)) {
            // var_dump($res);die;
            foreach ($res as $key => $value) {

                $link = '';
                $idd = $value['id'];
                $text2 = 'SELECT link '
                    . 'FROM file '
                    . 'WHERE '
                    . 'post_id = ' . $idd;

                if ($res = $this->mysql->querry($text2))
                    $link = $res[0]['link'];
                $user = new User($request, $mysql);
                // var_dump($value['user_id']);die;
                $user->identity($value['user_id']);
                $arr[] = new Post($user, $value, $link);
            }
        }
        // var_dump($this->mysql->error); die;
        return $arr;
    }
}
