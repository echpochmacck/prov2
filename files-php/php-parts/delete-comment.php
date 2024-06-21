<?php
require_once '../init/init.php';
if ($user->request->get('comment_id')) {
    // var_dump($user->request->get());
   Comment::deleteComment($user->request->get('comment_id'), $mysql);

}