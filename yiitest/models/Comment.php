<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property string $message
 * @property int $post_id
 * @property int $user_id
 * @property int|null $comment_id
 * @property string|null $date
 *
 * @property Comment $comment
 * @property Comment[] $comments
 * @property Post $post
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'post_id', 'user_id'], 'required'],
            [['message'], 'string'],
            [['post_id', 'user_id', 'comment_id'], 'integer'],
            [['date'], 'safe'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::class, 'targetAttribute' => ['comment_id' => 'id']],
        ];
    }

    public static function list(string $post_id): array
    {
        $comments = Comment::find()
        ->select([
            'comment.id',
            'message',
            'comment_id',
            'post_id',
            'date',
            'user.login',
            'link',
        ])
        ->where(['post_id' => $post_id])
        ->asArray()
        ->join('INNER JOIN', 'user', 'user.id = user.id')
        ->join('INNER JOIN', 'avatar', 'user.id = avatar.user_id')
        ->all();
        
        

        $mainArr = [];
        $arr = self::listOfComments($comments);
        return $arr;
    }

    public static function  listOfComments(array $arr): array
    {
        $mainArr = [];
        foreach ($arr as $key => $value) {
            $arrk = [];

            if (!$value['comment_id']) {

                $arrk['id'] = $value['id'];
                $arrk['com'] = $value;
                $mainArr[] = $arrk;
            } else {

                $arrk['id'] = $value['id'];
                $arrk['com'] = $value;
                
                $mainArr = self::probeg($mainArr, $arrk);
            }
        }

        return $mainArr;
    }

    public static function probeg(array $arr, array $ark): array
    {
        foreach ($arr as &$elem) {

            if ($elem['id'] === $ark['com']['comment_id']) {
                $elem['answer'][] = $ark;
            }
            if (!empty($elem['answer'])) {
                $elem['answer'] = self::probeg($elem['answer'], $ark);
            }
        }

        return $arr;
    }


    public static function deleteComment(string $comment_id)
    {
        $comment = Self::findOne($comment_id);
        $comment->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'post_id' => 'Post ID',
            'user_id' => 'User ID',
            'comment_id' => 'Comment ID',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[Comment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comment::class, ['id' => 'comment_id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['comment_id' => 'id']);
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
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
