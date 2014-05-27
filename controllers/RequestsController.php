<?php

namespace app\controllers;

use app\models\Request;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;

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

    public function actionRequestFormProceed($requestId = null)
    {
        if ($requestId) {
            $request = Request::findOne($requestId);
            if (!$request) {
                throw new HttpException(404);
            }
            if ($request->userId != \Yii::$app->user->identity->user->id || \Yii::$app->user->identity->user->role != 'admin') {
                throw new HttpException(403);
            }
        } else {
            $request = new Request();
            $request->userId = \Yii::$app->user->identity->user->id;
            $request->dateAdded = time();
        }

        if (\Yii::$app->request->isPost) {
            $request->load(\Yii::$app->request->post());
            if ($request->save()) {
                return $this->redirect(['view', 'requestId' => $request->id]);
            } else {
                return $this->render('add', ['request' => $request]);
            }
        }
    }
} 