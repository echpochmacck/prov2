<?php

namespace app\controllers;

use app\models\User1;
use Yii;

class UserController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'login') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    public function actionLogin()
    {
        $user = new User1();
        if (Yii::$app->request->isPost) {;
            $post = Yii::$app->request->post();
            $user->load($post, '');

            if ($user->find()->where(['login' => $user->login])->exists()) {
                if (password_verify($user->password, $user->find()->select('password')->where(['login' => $user->login])->column()[0])) {
                    $test = $user->findOne(['login' => $user->login]);
                    $user->load($test->attributes, '');
                    var_dump($user);die;
                }
            } else {
                var_dump('не зашел');
                die;
            }
            
        }
        // $user->id = 12;
        // return $this->render('index');
    }
}

// if ($bool) {
//     $arr['token'] = $user->token;
//     $arr['userId'] = $user->id;
//     $arr['dateUnlock'] = $user->dateUnlock;
//     if ($user->isAdmin) {
//       $arr['role'] = 'admin';
//     } else $arr['role'] = 'avtor';
//   } else {
//     $arr['error'] = 'Неверный логин или пароль';
//   }
//   $arr = json_encode($arr);
//   echo $arr;