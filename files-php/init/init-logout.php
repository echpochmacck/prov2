<?php
require_once 'init.php';
if ($user->request->post()) {
    $user->identity($user->request->post('id'));
    $user->logout();
    echo json_encode('true');
}