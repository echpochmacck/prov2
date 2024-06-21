<?php
require_once '../init/init.php';
if ($user->request->post()) {
    // var_dump($user->request->post());

    $json = $post->deletePost($user->request->post('user_id'), $user->request->post('role'), $user->request->post('postId'));
    $json = json_encode($json);
    echo $json;
}