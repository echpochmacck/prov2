<?php

namespace app\controllers;


use app\models\Post;
use app\models\Role;
use app\models\File;
use Request;
use yii\web\UploadedFile;
use Yii;

class PostController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'posts' || $action->id == 'create-post' || $action->id = 'delete-post') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    public function actionTenPosts()
    {
        if (Yii::$app->request->isGet) {
            // $post = Yii::$app->request->post();
            $posts = Post::find()
                ->select([
                    'post.id',
                    'title',
                    'preview',
                    'content',
                    'post.date',
                    'post.user_id',
                    'login',
                    'COUNT(comment.id) as numberOfComment'
                ])
                ->join('INNER JOIN', 'user', 'post.user_id = user.id')
                ->join('LEFT JOIN', 'comment', 'comment.post_id = post.id')
                ->orderBy('post.date desc')
                ->groupBy('post.id')
                ->limit(10)
                ->asArray()
                ->all();
            }
            return $this->asJson($posts);
    }

    public function actionCreatePost()
    {

        if ($post_id = yii::$app->request->get('postId')) {
            $post = Post::findOne($post_id);
            return $this->asJson($post->attributes);
        }

        if (yii::$app->request->isPost) {
            $arr = [];
            
            if ($post_id = yii::$app->request->post('post_id')) {
                Post::redPost($post_id);
            } else {
                Post::createPost();
            }
        }
       return $this->asJson($arr);
    }



    public function actionDeletePost()
    {
        $arr = [];
        $arr['answer'] = 'ne norm';
        if (yii::$app->request->isPost) {
            $data = yii::$app->request->post();
            if (Post::deletePost($data['user_id'], $data['post_id'],  Role::isAdmin($data['user_id']))) {
                $arr['answer'] = 'norm';
            }
        }
        echo json_encode($arr);
    }
}
// }