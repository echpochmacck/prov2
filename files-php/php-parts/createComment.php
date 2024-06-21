<?php
require_once '../init/init.php';
if ($user->request->post('message')) {
    $comment = new Comment( $user, $user->request->post('post_id'), $user->request->post());
    if ($user->request->post('comment_id')) {
        $comment->comment_id = $user->request->post('comment_id');
        // var_dump( $comment->comment_id);die;
    }
    // var_dump($comment);die;
    if ($comment->save()) {
    echo 'хорошо';
    } else echo 'плохо';
}