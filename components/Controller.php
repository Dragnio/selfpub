<?php

namespace app\components;

use app\models\User;

/**
 * Class Controller
 *
 * @package app\components
 * @property User $user
 */
class Controller extends \yii\web\Controller
{

    public $user;

    public function init()
    {
        parent::init();
        if (!\Yii::$app->user->isGuest) {
            $this->user = \Yii::$app->user->identity->user;
        }
    }

} 