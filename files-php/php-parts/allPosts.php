<?php
require '../init/init-posts.php';
$limit = 3;
$count  = ceil($post->checkCount() / $limit);

if ($user->request->get('offset') == 0 || $user->request->get('offset')) {
    // var_dump($user->request->get('offset'));
    // die;
    $pageOf = $user->request->get('offset');
    // var_dump($pageOf);
    // die;
    $offset = $pageOf;
    // var_dump($post->pages($limit, $offset, $pageOf));
    $arr['count'] = $count;
    // $arr['user'] = $user;
    $arr['posts'] =  $post->list($limit, $offset);
    $arr['pages'] = $post->pages($limit, $pageOf);
    echo json_encode($arr);
}
