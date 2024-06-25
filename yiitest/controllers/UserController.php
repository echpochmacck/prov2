<?php

namespace app\controllers;

use app\models\User;
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
            if ($user = User::findOne(['login' => $post['login']])) {
                if (password_verify($post['password'], $user->password)) {
                    $user->login();
                      
                    if (Role::isAdmin($user->id)) {
                        $arr['role'] = 'admin';
                    } else {
                        $arr['role'] = 'avtor';
                    }

                    $arr['token'] = $user->token;
                    $arr['id'] = $user->id;
                   return $this->asJson($arr);
                }
            } else {
                $arr['error'] = 'Неверный логин или пароль';
                return $this->asJson($arr);
            }
        }
    }


    public function actionRegister () {


        if (Yii::$app->request->isPost) {;
            $post = Yii::$app->request->post();
            $user = new User();
            $user->scenario = User::SCENARIO_REGISTER;
            $user->load($post, '');

            if ($user->validate()) {
                if ($user->password === $user->password_repeat) {
                    $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
                    $user->token = yii::$app->security->generateRandomString();
                    
                    $user->save();

                    $user->role_id = 1;
                    if (Role::isAdmin($user->id)) {
                        $arr['role'] = 'admin';
                    } else {
                        $arr['role'] = 'avtor';
                    }
                    $user->save();
                    $arr['token'] = $user->token;
                    $arr['id'] = $user->id;
                   return $this->asJson($arr);
                }
            } else {
                echo 'не зашел';
                $arr['error'] = 'Неверный логин или пароль';
              return $this->asJson($arr);
            }
            
        }

    }
}

