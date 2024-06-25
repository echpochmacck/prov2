<?php
class Post extends Data
{

    public object $user;
    public object $files;
    public object $validator;

    public string $title = '';
    public string $content = '';
    public string|object $date = '';
    public string $preview = '';

    public array|bool $image = false;

    public string $file = '';
    public bool $titleValid = false;
    public bool $contentValid = false;
    public bool $dateValid = false;
    public bool $previewValid = false;

    public string|null $id = '';

    public string|null $user_id = '';

    public string|null $numberOfComment = null;

    public function __construct(object|bool $user = false, array|bool $post = false, string|bool $link = false)
    {
        $this->validator = new Validator;
        $this->user = $user;
        $this->files = new Files($this->user->mysql);
        if ($post) {
            $this->load($post);
            // $this->date = $this->formDate($this->date);
        }
        if ($link) {
            $this->file = $link;
        }
    }

    public function validate()
    {
        $arr = get_object_vars($this);
        foreach ($arr as $key => $value) {
            // var_dump(str_contains($key, 'Valid'), $key);
            if (str_contains($key, 'Valid')) {
                $noValid = str_replace('Valid', '', $key);
                $key = $this->validator->checkUnEmpty($this->$noValid, $value);
                // var_dump($key, $this->$noValid);
                // die;
                if ($key === false) {
                    return false;
                    break;
                } else {
                    return true;
                }
            }
        }
        // parent::validateData();

    }


    public function list(int|bool $number = false, int|bool|string $offset = false): array
    {
        $request = $this->user->request;
        $mysql = $this->user->mysql;
        $arr = [];
        if ($number && !$offset) {

            $text = 'SELECT * '
                . 'FROM '
                . 'POST '
                . 'ORDER BY date DESC '
                . 'LIMIT ' . "$number ";

            // var_dump($text);die;
            if ($res = $this->user->mysql->querry($text)) {
                foreach ($res as $key => $value) {
                    // var_dump($value);die;
                    $link = '';
                    $idd = $value['id'];
                    $text2 =  $text2 = 'SELECT COUNT(id) '
                        . 'FROM COMMENT '
                        . 'WHERE '
                        . 'post_id = ' . $idd;

                    if ($res = $this->user->mysql->querry($text2))
                        $count = $res[0]['COUNT(id)'];
                    $value['numberOfComment'] = $count;

                    $user = new User($request, $mysql);
                    // var_dump($value['user_id']);die;
                    $user->identity($value['user_id']);
                    $arr[] = new static($user, $value, $link);
                }
            }
            return $arr;
        }

        if ($number && $offset) {
            $text = 'SELECT * '
                . 'FROM '
                . 'POST '
                . 'ORDER BY date DESC '
                . 'LIMIT ' . 3 * $offset . ",$number ";
            // $pages = $this->pages($number);

            $arr = [];
            if ($res = $this->user->mysql->querry($text)) {
                // var_dump($text);die;
                // var_dump($res);die;
                foreach ($res as $key => $value) {
                    $idd = $value['id'];
                    $text2 =  $text2 = 'SELECT COUNT(id) '
                        . 'FROM COMMENT '
                        . 'WHERE '
                        . 'post_id = ' . $idd;
                    if ($res = $this->user->mysql->querry($text2)) {
                        $count = $res[0]['COUNT(id)'];
                        $value['numberOfComment'] = $count;
                    }
                    $user = new User($request, $mysql);
                    $user->identity($value['user_id']);
                    // var_dump($user);die;
                    $arr[] = new static($user, $value);
                }
            }
        }
        return $arr;
    }


    public function pages(int $limit, string|bool $pageOf = ''): array|bool
    {
        $text = ' SELECT count(*) '
            . 'FROM post';

        $arr = [];

        if ($res = $this->user->mysql->querry($text)) {

            $count = $res[0]['count(*)'];
            $totalPages = ceil($count / $limit);
            if (!$pageOf) {
                $n = 1;
            } else {
                if (($pageOf+3)*$limit >= $count) {
                    
                    $n = $totalPages - 3;
                } else {
                $n = $pageOf;
                }
            }
            
            $end = $n+3;
            while ($n <= $end) {
                $arrk['page'] = $n;
                $arr[] = $arrk;
                if ($n*$limit > $count){
                    break;
                }
                $n++;
            }
        }
        // var_dump($arr);
        return $arr;
    }

    public function checkCount (): string {
        $text = ' SELECT count(*) '
        . 'FROM post';
        if ($res = $this->user->mysql->querry($text)) 
            $count = $res[0]['count(*)'];
            return $count;
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



    public function formatnorm(): void
    {
        $arr  = get_object_vars($this);

        foreach ($arr as $key => $value) {
            if (array_key_exists($key . 'Valid', $arr)) {
                $this->$key = $this->replaceBr($value);
            }
        }
    }


    public function load(array $arr): void
    {
        parent::load($arr);
        $this->format();
        if (!empty($_FILES['upload_image_post']['name'])) {
            $this->image = $_FILES['upload_image_post'];
        };
        // var_dump($this);die;
        // $this->formatnorm();
        if ($this->user->request->get('post-id')) {
            // var_dump($this);die;
        }
    }


    public function save(string|bool $user_id = false): bool
    {
        $this->date = date('Y-m-d H:i:s');
        $result = false;
        if ($this->validate()) {
            if (!$this->id) {
                $text =
                    'INSERT INTO post '
                    . '(user_id, title, preview, content, date) '
                    . 'VALUES('
                    . "'" . $user_id . "',"
                    . "'" . $this->title . "',"
                    . "'" . $this->preview . "',"
                    . "'" . $this->content . "',"
                    . "'" . $this->date . "')";
                if ($this->user->mysql->querry($text)) {
                    $text = 'SELECT id '
                        . 'FROM POST '
                        . 'WHERE '
                        . 'user_id = ' .  $user_id
                        . ' and date = ' . "'$this->date'";
                    if ($res = $this->user->mysql->querry($text)) {
                        $this->id = $res[0]['id'];
                        // var_dump($this->image); die;
                        if (!empty($this->image)) {
                            $this->files->saveFile($this->image, $this->id);
                        }
                        $result = true;
                    }
                }
            } else {
                if ($this->user_id === $user_id) {
                    $text =
                        'UPDATE post SET '
                        . "user_id = '" .  $user_id . "', "
                        . "title = '" . $this->title . "', "
                        . "preview = '" . $this->preview . "', "
                        . "content = '" . $this->content . "', "
                        . "date = '" . $this->date . "' "
                        . "WHERE id = " . $this->id;
                    if (!empty($this->image)) {
                        // var_dump($this->image);die;
                        $this->files->saveFile($this->image, $this->id);
                    }
                    $res = $this->user->mysql->querry($text);
                    // var_dump($text);die;
                    if ($res = $this->user->mysql->querry($text)) {
                        $result =  true;
                    }
                }
            }
        }
        return $result;
    }

    public function findOne()
    {
        $text = 'SELECT * '
            . 'FROM POST '
            . 'WHERE '
            . 'id = ' . $this->id;
        $text2 = 'SELECT COUNT(id) '
            . 'FROM COMMENT '
            . 'WHERE '
            . 'post_id = ' . $this->id;

        $text3 = 'SELECT link '
            . 'FROM file '
            . 'WHERE '
            . 'post_id =' .  $this->id;
        if ($arr = $this->user->mysql->querry($text)) {
            $this->load($arr[0]);
            if ($res = $this->user->mysql->querry($text2)) {
                // var_dump($res);die;
                $this->numberOfComment = $res[0]['COUNT(id)'];
                $this->formDate();

                if ($res = $this->user->mysql->querry($text3)) {
                    $this->file = $res[0]['link'];
                }
            }
        }
    }

    public function formDate(): void
    {
        $this->date = parent::doDate($this->date);
    }


    public function deletePost(string $user_id, string $role, string $id)
    {
        // var_dump($isAdmin);die;
        $textt = 'SELECT user_id '
            . 'FROM '
            . 'POST '
            . 'WHERE '
            . 'id = ' . $id;
        $result = false;
        if ($check = $this->user->mysql->querry($textt)) {
            // if ($user_id)
            $text = 'DELETE '
                . 'FROM '
                . 'POST '
                . 'WHERE '
                . 'id = ' . $id;

            if ($role === 'avtor' && ($user_id === $check[0]['user_id'])) {
                // var_dump(!$isAdmin && $user_id === $check[0]['user_id']);die;
                $text2 = 'SELECT COUNT(id) '
                    . 'FROM COMMENT '
                    . 'WHERE '
                    . 'post_id = ' . $id;
                if ($res = $this->user->mysql->querry($text2)) {
                    // var_dump(empty($res[0]['COUNT(id)']));die;
                    if (empty($res[0]['COUNT(id)'])) {
                        $text1 = 'SELECT link '
                            . 'FROM FILE '
                            . 'WHERE post_id = ' . $id;
                        if ($res = $this->user->mysql->querry($text1)) {
                            if (file_exists(__DIR__ . '/../uploads/' . $res[0]['link'])) {
                                unlink(__DIR__ . '/../uploads/' . $res[0]['link']);
                            }
                        }
                        if ($this->user->mysql->querry($text)) {
                            $result = true;
                        }
                    }
                }
            } else if ($role === 'admin') {
                // var_dump('dfsf');die;
                $text1 = 'SELECT link '
                    . 'FROM FILE '
                    . 'WHERE post_id = ' . $id;
                if ($res = $this->user->mysql->querry($text1)) {
                    // var_dump('sdsad');die;
                    if (file_exists(__DIR__ . '/../../uploads/' . $res[0]['link'])) {
                        unlink(__DIR__ . '/../../uploads/' . $res[0]['link']);
                    }
                }
                if ($this->user->mysql->querry($text)) {
                    $result = true;
                }
            }
            return $result;
        }
    }
}
