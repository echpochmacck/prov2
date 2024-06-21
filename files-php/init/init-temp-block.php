<?php
require_once 'init.php';
// die;
if ($user->isAdmin){
    // var_dump($user->request->post());die;
    if ($user->request->post('date_block')) {
        $superUser->tempBlock($user->request->post('date_block'), $user->request->get('user_id'));
        // $response->redirect('users.php');
    }

}