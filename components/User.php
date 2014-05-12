<?php

namespace app\components;

/**
 * Class User
 *
 * @package common\modules\users\components
 * @property \app\components\UserIdentity $identity
 */
class User extends \yii\web\User
{

    public function getUser()
    {
        return $this->identity ? $this->identity->user : null;
    }
}
