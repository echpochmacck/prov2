<?php
require_once '../init/init.php';
if ($res = $user->request->post()) {
    // var_dump($res);
   $superUser->foreverBlock( $res['user_id'],$res['couse'], $res['admin_id']);
}