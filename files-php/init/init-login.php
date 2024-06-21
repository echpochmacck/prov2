<?php
require_once 'init.php';
$json = file_get_contents('php://input');
if ($json) {
  $arr = json_decode($json, true);
  // var_dump($arr);
  $login = $arr['login'];
  $password = $arr['password'];
  $bool = $user->login($login, $password);
  //  echo $password;
  $arr = [];
  if ($bool) {
    $arr['token'] = $user->token;
    $arr['userId'] = $user->id;
    $arr['dateUnlock'] = $user->dateUnlock;
    if ($user->isAdmin) {
      $arr['role'] = 'admin';
    } else $arr['role'] = 'avtor';
  } else {
    $arr['error'] = 'Неверный логин или пароль';
  }
  $arr = json_encode($arr);
  echo $arr;
}

//     // echo 'dsadsa';
//     // echo $json;
//     // echo $json;
//     }
// echo 'response';
// if(($user->request->get('login'))){
//     // if()
//     $html = '';

// $bool = $user->login($user->request->get('login'), $user->request->get('password'));

// if($bool){
//     echo $user->token;
// }

// if ($bool) {
//     // var_dump($user->checkUnBlock())
//     if ($user->checkUnBlock()){
//     $response->redirect('/practice/');   
//     } else {
//         $date = $user->dateUnlock;
//         if ($user->dateUnlock === '1970-01-01 00:00:00') {
//             $mes = 'вы забанены навсегда';
//         } else {
//             $mes = "забанены до $date";
//         }
//         $user->logout();
//         $response->redirect('login.php',['block-messege'=>"$mes"]);
//     }
// } else {
//     $user->loginError = $user->validator->loginPswdError();
// }
