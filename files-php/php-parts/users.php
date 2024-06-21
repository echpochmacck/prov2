<?php
require_once '../init/init.php';
if ($user->request->post()) {
}
$json =  json_encode($superUser->listOfUsers());
echo $json;