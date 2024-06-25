<?php

namespace app\controllers;

use app\models\User;
use app\models\Post;
use app\models\File;
use app\models\Comment;
use yii\web\UploadedFile;
use Yii;

class AdminController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'list-users') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }




    public function actionListUsers()
    {
        if (Yii::$app->request->isPost) {
           $users = User::listOfUsers();
           return $this->asJson($users);
        }
    }

    

    

    
}