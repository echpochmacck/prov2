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
        if ($action->id == 'posts' || $action->id == 'create-post' || $action->id = 'delete-post'  || $action->id = 'all-posts') {
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

    public function actionCreatePost($postId = '')
    {
        if ($this->request->isGet) {
            $post = Post::findOne($postId);
            return $this->asJson($post->attributes);
        }
        if (yii::$app->request->isPost) {
            $arr = [];
            if ($post_id = yii::$app->request->post('post_id')) {
                $post = Post::findOne($post_id);
                $post->load(yii::$app->request->post(), '');
                $post->imageFile = UploadedFile::getInstanceByName('upload_image_post');
                if (!$post->redPost()) {
                    $arr['errors'] = 'ne norm';
                }
            } else {
                $post = new Post();
                $post->load(yii::$app->request->post(), '');
                $post->imageFile = UploadedFile::getInstanceByName('upload_image_post');
                if ($post->createPost()) {
                    $arr['errors'] = '';
                } else {
                    $arr['errors'] = 'ne norm';
                }
            }
        }
        return $this->asJson($arr);
    }

    public function actionAllPosts($offset = 0)
    {
        $limit = 3;
        $count  = ceil(Post::checkCount() / $limit);
        // var_dump($offset);die;
        // var_dump($post->pages($limit, $offset, $pageOf));
        $arr['count'] = $count;
        // $arr['user'] = $user;
        $arr['posts'] =  Post::list($limit, $offset);
        $arr['pages'] = Post::pages($limit, $count, Post::checkCount(), $offset);
        return $this->asJson($arr);
    }

    public function actionDeletePost()
    {
        $arr = [];
        $arr['answer'] = 'ne norm';
        if (yii::$app->request->isPost) {
            $data = yii::$app->request->post();
            if (Post::deletePost($data['user_id'],  $data['post_id'],  Role::isAdmin($data['user_id']))) {
                $arr['answer'] = 'norm';
            }
        }
        echo json_encode($arr);
    }
}
// }