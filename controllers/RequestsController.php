<?php

namespace app\controllers;

use app\models\Request;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class RequestsController
 *
 * @package app\controllers
 */
class RequestsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['*'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd()
    {
        $request = new Request();
        return $this->render('add', ['request' => $request]);
    }
} 