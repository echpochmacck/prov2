<?php

namespace app\controllers;

use app\models\User;
use app\models\Post;
use app\models\File;
use app\models\Comment;
use yii\web\UploadedFile;
use Yii;

class CommentController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'create-comment') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }




    public static function actionCreateComment()
    {
        if (Yii::$app->request->isPost) {
           $post = Yii::$app->request->post();
            $comment = new Comment();
            $comment->load($post, '');
            if ($comment->validate()) {
                $comment->date = date('Y-m-d H:i:s');
                $comment->save();
            }
        }
    }

    public static function actionListComment()
    {
        if ($post_id = Yii::$app->request->get('post_id')) {
          
            $comments = Comment::list($post_id);
            echo json_encode($comments);   
         }
    }
    
   

}
// }