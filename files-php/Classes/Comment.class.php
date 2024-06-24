<?php
class Comment extends Data
{
    public string $message = '';
    public bool $messageValid = false;


    public object $user;
    public string $user_id = '';
    public string $post_id = '';
    public string $id = '';

    public string|null $comment_id = '';
    public string|object|null $date = '';

    public object $validator;

    public function __construct(object $user, string $post_id, array|bool $arr = false)
    {
        $this->validator = new Validator();
        if ($arr) {
            parent::load($arr);
        }
        $this->format();
        $this->user = $user;
        $this->post_id = $post_id;
        // var_dump($this);die;
    }


    public function save(): bool
    {
        $result = false;
        if ($this->messageValid = $this->validator->checkUnEmpty($this->message)) {
            $this->date = date('Y-m-d H:i:s');
            if (!$this->comment_id) {
                $text = 'INSERT INTO comment (message, post_id, user_id, date) '
                    . 'VALUES ('
                    . "'$this->message'" . ','
                    . "'$this->post_id'" . ','
                    . "'" . $this->user_id . "',"
                    . "'$this->date'" . ')';
                // var_dump($text);die;
                if ($this->user->mysql->querry($text)) {
                    $result = true;
                }
            } else {

                $text = 'INSERT INTO comment (message, post_id, user_id, comment_id, date) '
                    . 'VALUES ('
                    . "'$this->message'" . ','
                    . "'$this->post_id'" . ','
                    . "'" . $this->user_id . "',"
                    . "'" . $this->comment_id . "',"
                    . "'$this->date'" . ')';
                // var_dump($text);die;
                if ($this->user->mysql->querry($text)) {
                    $result = true;
                }
            }
            // var_dump($this->user->mysql->error);die;
        }
        return $result;
    }


    public function formDate(): void
    {
        $this->date = parent::doDate($this->date);
    }

    public function format(): void
    {
        $arr  = get_object_vars($this);

        foreach ($arr as $key => $value) {
            if (array_key_exists($key . 'Valid', $arr)) {
                $this->$key = $this->replaceSmth($value);
            }
        }
    }

    public static function list(string $post_id, $mysql, $request): array
    {
        $text = 'SELECT * '
            . 'FROM '
            . 'comment '
            . 'where post_id = ' . $post_id
            . ' ORDER BY date, comment_id';
        $arr = [];

        if ($res = $mysql->querry($text)) {
            foreach ($res as $key => $value) {
                $user = new User($request, $mysql);
                $user->identity($value['user_id']);
                $arr[] = new static($user, $value['post_id'], $value);
            }
        }

        $mainArr = [];
        $arr = self::listOfComments($arr);
        return $arr;
    }

    public static function  listOfComments(array $arr): array
    {
        $mainArr = [];


        foreach ($arr as $key => $value) {
            $arrk = [];

            if (!$value->comment_id) {

                $arrk['id'] = $value->id;
                $arrk['com'] = $value;
                $mainArr[] = $arrk;
            } else {

                $arrk['id'] = $value->id;
                $arrk['com'] = $value;
                
                $mainArr = self::probeg($mainArr, $arrk);
            }
        }

        return $mainArr;
    }

    public static function probeg(array $arr, array $ark): array
    {
        foreach ($arr as &$elem) {

            if ($elem['id'] === $ark['com']->comment_id) {
                $elem['answer'][] = $ark;
            }

            if (!empty($elem['answer'])) {
                $elem['answer'] = self::probeg($elem['answer'], $ark);
            }
        }

        return $arr;
    }


    

    // public static function hz(object $main, array $noMainArr, array $union)
    // {
    //     // $arr = [];
    //     foreach ($noMainArr as $key => $value) {
    //         if ($main->id === $value->comment_id) {
    //             $union[] = $main;
    //             $union[array_search($main, $union) + 1][] = $value;
    //         }

    //         return $union;
    //     }
    // }


    public static function deleteComment(string $comment_id, object $mysql): bool
    {
        $result = false;

        // if ($this->user->isAdmin) {
            $text = 'DELETE'
                . ' FROM comment'
                . ' WHERE id = ' . $comment_id;
            if ($mysql->querry($text)) {
                $result = true;
            }
        // }

        return $result;
    }
}
