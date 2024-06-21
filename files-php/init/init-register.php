<?php
require_once 'init.php';

if ($user->request->isPost) {
  // var_dump($_FILES);die;
  // var_dump($user->request->post());die;
  $user->load(($user->request->post()));
  $arr = [];
  if ($user->save()) {
    // var_dump($user);
    $user->login($user->login, $user->password);
    $token = json_encode($user->token);
    $arr['token'] = $token;
    $arr['userId'] = $user->id;
    if ($user->isAdmin) {
      $arr['role'] = 'admin';
    } else $arr['role'] = 'avtor';
  } else {
    // var_dump($user);
    $arr['errors'] = $user->getErros();
  }

  $arr = json_encode($arr);
  echo $arr;
}

//  if (array_key_exists('query',  $request->geturl())) {
//   // выделяем ток параметры
//   $params = $request->geturl()['query'];
//   // var_dump($params);
//   $params = Asist::deCode($params);
//   // получаем ключи и згначения
//   $params = (Asist::getData($params));
//   $user->load($params);
// }
