<?php

namespace app\models;

use DateTime;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string|null $patronymic
 * @property string $login
 * @property string $email
 * @property string $password
 * @property string|null $dateUnlock
 * @property string|null $token
 * @property int|null $role_id
 *
 * @property Avatar[] $avatars
 * @property Banforever[] $banforevers
 * @property Banforever[] $banforevers0
 * @property Comment[] $comments
 * @property Post[] $posts
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord 
{

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    public string $password_repeat = '';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public static function listOfUsers()
    {
        $users = self::find()
                ->select([
                    'login',
                    'name',
                    'surname',
                    'email',
                    'id',
                    'dateUnlock'
                ])
                ->asArray()
                ->all()
                ;
        return $users;
    }


    public function login() 
    {
        
        $this->token = yii::$app->security->generateRandomString();
        $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'login', 'email', 'password'], 'required'],
            [['dateUnlock'], 'safe'],
            [['role_id'], 'integer'],
            [['password_repeat'], 'required','on' => self::SCENARIO_REGISTER],
            [['name', 'surname', 'patronymic', 'login', 'email', 'password', 'token'], 'string', 'max' => 255],
            [['login'], 'unique'],
            [['token'], 'safe'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }


   
    // public function isAdmin()
    // {
    //     if ($this->role_id == )
    // }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'patronymic' => 'Patronymic',
            'login' => 'Login',
            'email' => 'Email',
            'password' => 'Password',
            'dateUnlock' => 'Date Unlock',
            'token' => 'Token',
            'role_id' => 'Role ID',
        ];
    }

    public function tempBlock(string $dateUnlock): bool
    {
        $result = false;
        $date = new DateTime($dateUnlock);
        $this->dateUnlock = $date->format('Y-m-d H:i:s');
        if ($this->save()) {
            $result = true;
        }
        return $result;
    }

    /**
     * Gets query for [[Avatars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvatars()
    {
        return $this->hasMany(Avatar::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Banforevers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBanforevers()
    {
        return $this->hasMany(Banforever::class, ['admin_id' => 'id']);
    }

    /**
     * Gets query for [[Banforevers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBanforevers0()
    {
        return $this->hasMany(Banforever::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }
}
