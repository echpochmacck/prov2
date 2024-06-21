<?php
require_once 'init.php';

// if ($user->request->get('post-id')) { 
//     $post->id = $user->request->get('post-id');
//     $post->findOne();
// if ($user->id === $post->user_id) {
//     $post->formatnorm();
// } else {
//     $response->redirect('/practice/');
// }
// }

if ($postData = $user->request->post()) {
    // var_dump($_FILES);die;
    $post->load($postData);

    if (array_key_exists('post_id', $postData)) {
         $post->id = $postData['post_id']; 
    }
    if ($post->save($postData['user_id'])) {
        $postData = [];
        $postData['post_id'] = $post->id;
        echo (json_encode($postData));
                // $response->redirect('/practice/post.php', ['post-id' => $post->id]);
    } else {
       echo json_encode('');
    }
}
