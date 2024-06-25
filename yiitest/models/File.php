<?php

namespace app\models;

use Sequrityy;
use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $link
 * @property int $post_id
 *
 * @property Post $post
 */

class File extends \yii\db\ActiveRecord
{
    // const SCENARIO_UPLOAD = 'upload';

    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['link', 'post_id'], 'required'],
            [['post_id'], 'integer'],
            [['link'], 'string', 'max' => 255],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']]

            // [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'on' => static::SCENARIO_UPLOAD],
        ];
    }

    public static function upload($imageFile)
    {
            $extension = $imageFile->getExtension();
            $imageFile->name = yii::$app->security->generateRandomString() . '.' . $extension;
             if ($imageFile->saveAs(__DIR__.'/../../uploads/'. $imageFile->name)) {
                 return true;
             }
     
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
            'post_id' => 'Post ID',
        ];
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
}
