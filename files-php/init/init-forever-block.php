<?php
require_once 'init.php';
if ($user->isAdmin){
if ($id = $user->request->get('user_id') ) {
    if ($couse = $user->request->post('couse')) {
   $superUser->foreverBlock($id, $couse, $user->id);
   $response->redirect('/practice/users.php');
 }
}
}