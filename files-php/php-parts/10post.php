<?php 
require_once '../init/init-index.php';
$arr['user'] = $user;
// var_dump($arr);die;
$arr['posts'] = $post->list(10);
// var_dump($arr['user']);die;
$arr = json_encode($arr);

echo $arr;