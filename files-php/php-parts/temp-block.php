<?php
require_once '../init/init.php';
if ($res = $user->request->post()) {
    // var_dump($res);
    $superUser->tempBlock($res['date_block'], $res['user_id']);
}