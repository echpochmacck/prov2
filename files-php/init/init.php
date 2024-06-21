<?php
// var_dump(__DIR__);
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../config.php';

$request = new Request();
$mysql = new Mysql($toSql);
$user = new User($request, $mysql);
$post = new Post($user);
$response = new Response($user, $post);
// $menu = new Menu($arrMenu, $response, $user);
$superUser = new SuperUser($request, $mysql);
