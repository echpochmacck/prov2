<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banforever".
 *
 * @property int $id
 * @property string $cause
 * @property int $admin_id
 * @property string $date
 * @property int $user_id
 *
 * @property User $admin
 * @property User $user
 */
class Banforever extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banforever';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cause', 'admin_id', 'date', 'user_id'], 'required'],
            [['cause'], 'string'],
            [['admin_id', 'user_id'], 'integer'],
            [['date'], 'safe'],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['admin_id' => 'id']],
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
            'cause' => 'Cause',
            'admin_id' => 'Admin ID',
            'date' => 'Date',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Admin]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
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
