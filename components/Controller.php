<?php

namespace app\components;

use amnah\yii2\user\models\User;


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
        $this->user = \Yii::$app->user;
    }

} 