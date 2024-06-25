<?php

namespace app\models;

use app\models\User;
use yii\web\UploadedFile;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $preview
 * @property string $content
 * @property string $date
 *
 * @property Comment[] $comments
 * @property File[] $files
 * @property User $user
 */
class Post extends \yii\db\ActiveRecord
{


    public  $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'title', 'content'], 'required'],
            [['user_id'], 'integer'],
            [['preview', 'content'], 'string'],
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Title',
            'preview' => 'Preview',
            'content' => 'Content',
            'date' => 'Date',
        ];
    }


    public static function checkNumber($post_id)
    {
        // var_dump($post_id);die;
        $numberofComments = Comment::find()
            ->select('*')
            ->where(['post_id' => $post_id]);
        // ->one();
        $numberofComments = $numberofComments->count();
        return $numberofComments;
    }



    public static function redPost(string $post_id)
    {
        $post = Post::findOne($post_id);
        $post->load(yii::$app->request->post(), '');
        if (!$_FILES['upload_image_post']['error']) {
            // var_dumdsp($_FILES['error']);die;
            $link = File::find()
                ->where(['post_id' => $post_id])
                ->select(['link'])
                ->asArray()
                ->one();
            // var_dump($link);die;
            if ($link) {
                if (file_exists(__DIR__ . '/../../uploads/' . $link['link'])) {
                    unlink(__DIR__ . '/../../uploads/' . $link['link']);
                }
            }
            $file = File::findOne(['post_id' => $post_id]);
            $file->imageFile = UploadedFile::getInstanceByName('upload_image_post');
            $file->post_id = $post
                ->findOne(['user_id' => $post->user_id, 'date' => $post->date])
                ->id;
            if ($file->upload()) {
                $res = $file->save();
            }
            $arr['link'] = $file->link;
        }
        if ($post->validate()) {
            $post->save(true);
            $arr['errors'] = '';
        } else {
            $arr['errors'] = 'ошибка в полях';
        }
    }

    public static function createPost()
    {   
        $post = new Post();
        $post->load(yii::$app->request->post(), '');
        $post->date = date('Y-m-d H:i:s');
        // var_dump($post);die;
        if ($post->validate()) {
            $post->save();
            $arr = $post->attributes;
            if (!$_FILES['upload_image_post']['error']) {
                // var_dumdsp($_FILES['error']);die;
                $file = new File();
                $file->imageFile = UploadedFile::getInstanceByName('upload_image_post');
                $file->post_id = $post
                    ->findOne(['user_id' => $post->user_id, 'date' => $post->date])
                    ->id;
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
    }

    public static function queryToDelPost(string $post_id)
    {
        $link = File::find()
            ->where(['post_id' => $post_id])
            ->select(['link'])
            ->asArray()
            ->one();
        if ($link) {
            if (file_exists(__DIR__ . '/../../uploads/' . $link['link'])) {
                unlink(__DIR__ . '/../../uploads/' . $link['link']);
            }
        }
        $post = Self::findOne($post_id);
        $post->delete();
    }
    public static function deletePost(string $user_id, string $post_id, bool $isAdmin = false)
    {
        $result = false;
        if ($isAdmin) {
            Self::queryToDelPost($post_id);
            $arr['answer'] = 'успешно удалил';
            $result = true;
        } else {
            if (!Self::checkNumber($post_id)) {
                Self::queryToDelPost($post_id);
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
