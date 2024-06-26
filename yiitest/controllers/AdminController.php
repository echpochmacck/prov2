<?php

namespace app\controllers;

use app\models\User;
use app\models\Post;
use app\models\File;
use app\models\Comment;
use app\models\Banforever;
use DateTime;
use yii\web\UploadedFile;
use Yii;

class AdminController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'list-users' || $action->id == 'temp-block' || $action->id == 'forever-block') {
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


    public function actionTempBlock()
    {
        $arr['error'] = 'что-то пошло не так';
        if (Yii::$app->request->isPost) {
            $date = Yii::$app->request->post();
            $user = User::findOne($date['user_id']);
            if ($user->tempBlock($date['date_block'])) {
                $arr['error'] = '';
            } 
        }
        return $this->asJson($arr);
    }

    public function actionForeverBlock()
    {
        $arr['error'] = 'что-то пошло не так';
        if (Yii::$app->request->isPost) {
            $banForever = new Banforever();
            $date = new DateTime();
            $banForever->date = $date->format('Y-m-d H:i:s');
            // var_dump(Yii::$app->request->post());die;
            $banForever->load(Yii::$app->request->post(), '');
            // var_dump($banForever);die;
            if ($banForever->banForever()) {
                $arr['error'] = '';
            }
            
            return $this->asJson($arr);
        }
    }

    

    

    
}