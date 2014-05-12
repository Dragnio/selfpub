<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (\Yii::$app->request->post('login', false) && \Yii::$app->request->post('password', false)) {
            $login = \Yii::$app->request->post('login', false);
            $password = \Yii::$app->request->post('password', false);
            if (User::auth($login, $password)) {
                if (Yii::$app->user->returnUrl != "/") {
                    return $this->goBack();
                } else {
                    return $this->redirect("/");
                }
            } else {
                \Yii::$app->session->setFlash('error', 'Неверный логин или пароль, попробуйте еще раз.');
                return $this->goHome();
            }
        } else {
            \Yii::$app->session->setFlash('error', 'Отсутвует логин или пароль');
            return $this->goHome();
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
