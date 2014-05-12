<?php

namespace app\models;

use app\components\ActiveRecord;
use app\components\UserIdentity;
use app\helpers\PasswordHelper;
use Yii;

/**
 * This is the model class for table "Users".
 *
 * @property integer   $id
 * @property string    $login
 * @property string    $role
 * @property string    $passwordHash
 * @property string    $passwordSalt
 * @property string    $authKey
 * @property string    $name
 * @property string    $email
 * @property string    $avatar
 * @property integer   $dateAdded
 * @property integer   $status
 *
 * @property Request[] $requests
 * @property Comment[] $comments
 */
class User extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'role', 'passwordHash', 'passwordSalt', 'authKey', 'name', 'dateAdded'], 'required'],
            [['dateAdded', 'status'], 'integer'],
            [['login', 'role', 'passwordSalt', 'name', 'email', 'avatar'], 'string', 'max' => 255],
            [['passwordHash', 'authKey'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('app', 'ID'),
            'login'        => Yii::t('app', 'Login'),
            'role'         => Yii::t('app', 'Role'),
            'passwordHash' => Yii::t('app', 'Password Hash'),
            'passwordSalt' => Yii::t('app', 'Password Salt'),
            'authKey'      => Yii::t('app', 'Auth Key'),
            'name'         => Yii::t('app', 'Name'),
            'email'        => Yii::t('app', 'Email'),
            'avatar'       => Yii::t('app', 'Avatar'),
            'dateAdded'    => Yii::t('app', 'Date Added'),
            'status'       => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['userId' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['userId' => 'id'])->inverseOf('user');
    }

    /**
     * @param $login
     * @param $password
     *
     * @return bool
     */
    public static function auth($login, $password)
    {
        $user = self::getUserByLogin($login);
        if ($user) {
            $identity = new UserIdentity(
                [
                    'username' => $user->login,
                    'password' => $password,
                    'user'     => $user
                ]
            );
            if ($identity->validatePassword()) {
                \Yii::$app->user->login($identity, 86400);

                return true;
            }
        }

        return false;
    }

    /**
     * @param $login
     *
     * @return static
     * @throws \yii\base\InvalidConfigException
     */
    public static function getUserByLogin($login)
    {
        return User::findOne(['login' => $login]);
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        $hash = PasswordHelper::generateCompiledPassHash($password, $this->passwordSalt);

        return $hash == $this->passwordHash;
    }
}
