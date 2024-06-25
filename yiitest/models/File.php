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

    public function upload()
    {
        if ($this->validate()) {
            // var_dump($this->imageFile, __DIR__.'/../../uploads/'. $this->imageFile->name. '.' .pathinfo($this->imageFile->name, PATHINFO_EXTENSION));die;
            $extension = $this->imageFile->getExtension();
            // var_dump($extension);die;
            $this->imageFile->name = yii::$app->security->generateRandomString() . '.' . $extension;
             if ($this->imageFile->saveAs(__DIR__.'/../../uploads/'. $this->imageFile->name)) {
                $this->link = $this->imageFile->name;
                 return true;
             }
        } else {
            return false;
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
