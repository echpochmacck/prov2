<?php
require_once 'init.php';
// var_dump($user->request->get());die;
if ( $user->request->get('postId') ) {
    // var_dump($user);die;
    $post->id = $user->request->get('postId');
    $post->findOne();

        $postData['title'] = $post->title;
        $postData['preview'] = $post->preview;
        $postData['content'] = $post->content;
        $postData['date'] = $post->date;
        $postData['file'] = $post->file;
        $postData['post_id'] = $post->id;
        $postData['user_id'] = $post->user_id;
        $postData['numberOfComment'] = $post->numberOfComment;
        $postData['link'] = $post->file;
        
        echo (json_encode($postData));
} else {
    // $response->redirect('/practice/');
}