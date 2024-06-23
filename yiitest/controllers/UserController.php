<?php

namespace app\controllers;

use app\models\User1;
use app\models\Role;
use Yii;

class UserController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'login'||$action->id == 'register') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    public function actionLogin()
    {
        if (Yii::$app->request->isPost) {;
            $post = Yii::$app->request->post();
           

            if ($user = User1::findOne(['login' => $post['login']])) {
                // var_dump($user);die;
                if (password_verify($post['password'], $user->password)) {

                    $test = $user->findOne(['login' => $user->login]);
                    $user->load($test->attributes, '');
                    $user->token = yii::$app->security->generateRandomString();
                    $user->save('token');
                    // var_dump($user);die;
                    if (Role::isAdmin($user->role_id)) {
                        $arr['role'] = 'admin';
                    } else {
                        $arr['role'] = 'avtor';
                    }
                    $arr['token'] = $user->token;
                    $arr['id'] = $user->id;
                    echo json_encode($arr);
                    // var_dump($user);die;
                }
            } else {
                $arr['error'] = 'Неверный логин или пароль';
                echo json_encode($arr);
                die;
            }
            
        }
    }


    public function actionRegister () {


        if (Yii::$app->request->isPost) {;
            $post = Yii::$app->request->post();
            $user = new User1();
            $user->load($post, '');
            // var_dump($user);die;

            if ($user->validate()) {
                // var_dump($user);die;
                if ($user->password === $user->password_repeat) {

                    $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
                    $user->token = yii::$app->security->generateRandomString();
                    // var_dump($user); die;
                    $user->save('token');
                    // var_dump($user);die;
                    $user->role_id = 1;
                    if (Role::isAdmin($user->role_id)) {
                        $arr['role'] = 'admin';
                    } else {
                        $arr['role'] = 'avtor';
                    }
                    $user->save();
                    $arr['token'] = $user->token;
                    $arr['id'] = $user->id;
                    echo json_encode($arr);
                    // var_dump($user); die;
                }
            } else {
                echo 'не зашел';die;
                $arr['error'] = 'Неверный логин или пароль';
                echo json_encode($arr);
                die;
            }
            
        }

    }
}

// if ($bool) {
//     $arr['token'] = $user->token;
//     $arr['userId'] = $usdser->id;
//     $arr['dateUnlock'] = $user->dateUnlock;
//     if ($user->isAdmin) {
//       $arr['role'] = 'admin';
//     } else $arr['role'] = 'avtor';
//   } else {
//     $arr['error'] = 'Неверный логин или пароль';
//   }
//   $arr = json_encode($arr);
//   echo $arr;