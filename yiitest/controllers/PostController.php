<?php

namespace app\controllers;


use app\models\Post;
use app\models\File;
use yii\web\UploadedFile;
use Yii;

class PostController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'posts' || $action->id == 'create-post') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    public static function actionTenPosts()
    {
        if (Yii::$app->request->isGet) {;
            // $post = Yii::$app->request->post();
            $posts = Post::find()
                ->select([
                    'post.id',
                    'title',
                    'preview',
                    'content',
                    'post.date',
                    'user_id',
                    'login'
                    // 'user.link'
                ])
                ->join('INNER JOIN', 'user', 'post.user_id = user.id')
                ->orderBy('post.date desc')
                ->limit(10)
                ->asArray()
                ->all();
            echo json_encode($posts);
        }
    }

    public static function actionCreatePost()
    {

        if ($post_id = yii::$app->request->get('postId')) {
            $post = Post::findOne($post_id);
            echo json_encode($post->attributes);
            // var_dump($pdost);die; 
        }

        if (yii::$app->request->isPost) {
            // var_dump('fdsf');die;
            if ($post_id = yii::$app->request->post('post_id')) {
                $post = Post::findOne($post_id);
                $post->load(yii::$app->request->post(), '');
                // var_dump($post->attributes);
                $arr = [];
                if ($post->validate()) {
                    $post->save(true);
                    $arr['errors'] = '';
                } else {
                    $arr['errors'] = 'ошибка в полях';
                }

                echo json_encode($arr);
            } else {

                $post = new Post();
                $post->load(yii::$app->request->post(), '');
                $post->date = date('Y-m-d H:i:s');
                // var_dump($post);die;
                if ($post->validate()) {
                    $post->save();
                    $arr = $post->attributes;
                    if (!$_FILES['upload_image_post']['error']) {
                        // var_dump($_FILES['error']);die;
                        $file = new File();
                        $file->imageFile = UploadedFile::getInstanceByName('upload_image_post');
                        $file->post_id = $post->findOne(['user_id' => $post->user_id, 'date' => $post->date])->id;
                        // загрузка и сейв в бд
                        if ($file->upload()) {
                            $res = $file->save();
                        }
                        $arr['link'] = $file->link;
                    }
                    // var_dump($arr);
                    // die;
                    $arr['errors'] = '';
                } else {
                    $arr['errors'] = 'ошибка в полях';
                }
                echo json_encode($arr);
            }
        }
    }
}
// }