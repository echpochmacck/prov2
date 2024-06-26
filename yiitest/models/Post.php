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

    public static function list(int|bool $number = false, int|bool|string $offset = false)
    {
        if ($number && !$offset) {

            $arr = Post::find()
                ->select([
                    'post.id',
                    'content',
                    'preview',
                    'post.date',
                    'user.id as user_id',
                    // 'avatar.link as user_link',
                    'title',
                    'login',
                    'post.user_id',
                    'count(comment.id) as numberOfComments'
                ])
                ->innerJoin('user', 'user.id = post.user_id')
                // ->innerJoin('avatar', 'user.id = avatar.user_id')
                ->leftJoin('comment', 'post.id = comment.post_id')
                ->groupBy('post.id')
                ->orderBy('post.date desc')
                ->limit($number)
                ->asArray()
                ->all();
            // var_dump($arr);
            // die;
            // return $arr;
        }

        if ($number && $offset) {

            $arr = Post::find()
                ->select([
                    'post.id',
                    'content',
                    'preview',
                    'post.date',
                    'user.id as user_id',
                    // 'avatar.link as user_link',
                    'title',
                    'login',
                    'post.user_id',
                    'count(comment.id) as numberOfComments'
                ])
                ->innerJoin('user', 'user.id = post.user_id')
                ->leftJoin('comment', 'post.id = comment.post_id')
                ->groupBy('post.id')
                ->orderBy('post.date desc')
                ->limit($number)
                ->offset(3*$offset)
                ->asArray()
                ->all();
            // var_dump($arr);
            // die;
            
        }
        return $arr;
    }


    public static function pages(int $limit, $totalPages, $count, string|bool $pageOf = ''): array|bool
    {

       
            if (!$pageOf) {
                $n = 1;
            } else {
                if (($pageOf+3)*$limit >= $count) {
                    
                    $n = $totalPages - 3;
                } else {
                $n = $pageOf;
                }
            }
            $end = $n+3;
            while ($n <= $end) {
                $arrk['page'] = $n;
                $arr[] = $arrk;
                if ($n*$limit > $count){
                    break;
                }
                $n++;
            }

        // var_dump($arr);
        return $arr;
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



    public  function redPost(): bool
    {
        $result = false;
        if ($this->imageFile) {
            $link = File::find()
                ->where(['post_id' => $this->id])
                ->select(['link'])
                ->asArray()
                ->one();
            if ($link) {
                if (file_exists(__DIR__ . '/../../uploads/' . $link['link'])) {
                    unlink(__DIR__ . '/../../uploads/' . $link['link']);
                }
            }
            $file = File::findOne(['post_id' => $this->id]);
            File::upload($this->imageFile);
            $file = new File();
            $file->link = $this->imageFile->name;
            $file->post_id = $this->id;
            $res = $file->save();
            $arr['link'] = $file->link;
        }
        if ($this->validate()) {
            if ($this->save(true)) {
                $result = true;
            }
        }

        return $result;
    }

    public static function checkCount()
    {
        $arr =  Post::find()
            ->select('COUNT(*)')
            ->asArray()
            ->one();
        return $arr['COUNT(*)'];
    }

    public  function createPost(): bool
    {
        $result = false;
        $this->date = date('Y-m-d H:i:s');
        if ($this->validate()) {
            if ($this->save()) {

                if ($this->imageFile) {
                    File::upload($this->imageFile);
                    $file = new File();
                    $file->link = $this->imageFile->name;
                    $file->post_id = $this->id;
                    $res = $file->save();
                    $arr['link'] = $file->link;
                }
                $result = true;
            }
        }
        return $result;
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
        $post = self::findOne($post_id);
        $post->delete();
    }
    public static function deletePost(string $user_id, string $post_id, bool $isAdmin = false)
    {
        $result = false;
        if ($isAdmin) {
            self::queryToDelPost($post_id);
            $arr['answer'] = 'успешно удалил';
            $result = true;
        } else {
            if (!self::checkNumber($post_id)) {
                self::queryToDelPost($post_id);
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
