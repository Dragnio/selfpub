<?php

namespace app\components;

use app\models\User;
use yii\base\Object;
use yii\rbac\Role;
use yii\web\IdentityInterface;

/**
 * Class UserIdentity
 *
 * @package app\components
 */
class UserIdentity extends Object implements IdentityInterface
{
    public $username;
    public $password;
    public $role;

    /**
     * @var User
     */
    public $user;

    public static function findIdentity($userId)
    {
        $identity = false;
        $user = User::findOne(['id' => $userId]);
        if ($user) {
            $identity = new self();
            $identity->user = $user;
            $identity->role = new Role();
            switch (true) {
                case $user->role == 'admin':
                    //admin
                    break;
                default:
                    //user
                    break;
            }
        }

        return $identity;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->user->id;
    }

    public function getAuthKey()
    {
        return $this->user->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->user->authKey === $authKey;
    }

    public function validatePassword()
    {
        return $this->user->validatePassword($this->password);
    }
}
