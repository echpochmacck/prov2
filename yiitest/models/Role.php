<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $title
 *
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }


    public static function isAdmin(string $user_id): bool
    {
        // var_dump(Role::findOne(['title' => 'admin'])->id);die;
        $user_role_id = User::find()
        ->select('role_id')
        ->where(['id' => $user_id])
        ->asArray()
        ->one();
        // var_dump( $user_role_id['role_id'] === Role::findOne(['title' => 'admin'])->id);die;
        return $user_role_id['role_id'] === Role::findOne(['title' => 'admin'])->id;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['role_id' => 'id']);
    }
}
