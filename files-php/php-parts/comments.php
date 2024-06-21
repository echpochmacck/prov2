<?php
require_once '../init/init.php';
if ($id = $user->request->get('postId')) {
    
    echo json_encode(Comment::list($id, $user->mysql, $user->request), $id);
} 

