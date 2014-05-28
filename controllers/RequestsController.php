<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Request;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

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

    public function actionRequestForm($requestId = null)
    {
        if ($requestId) {
            $request = Request::findOne($requestId);
            if (!$request) {
                throw new HttpException(404);
            }
            if ($request->userId != \Yii::$app->user->identity->user->id || \Yii::$app->user->identity->user->role != 'admin') {
                throw new HttpException(403);
            }
            $request->platforms = json_decode($request->platforms, true);
        } else {
            $request = new Request();
            $request->userId = \Yii::$app->user->identity->user->id;
            $request->dateAdded = time();
            $request->status = Request::STATUS_WAITING;
        }
        $oldFile = $request->file;
        $oldCover = $request->cover;

        if (\Yii::$app->request->isPost) {
            $request->load(\Yii::$app->request->post());
            $file = UploadedFile::getInstance($request, 'file');
            if ($file->size > 0 && !in_array($file->extension, Request::$fileExtensions)) {
                $request->addError(
                    'file',
                    'Файл книги должен быть в одном из следующих форматов: ' . implode(',', Request::$fileExtensions)
                );
            } elseif ($file->size == 0 && !$request->isNewRecord) {
                $request->file = $oldFile;
            } else {
                $request->file = $file->name;
            }
            $cover = UploadedFile::getInstance($request, 'cover');
            if ($cover->size > 0 && !in_array($cover->extension, Request::$coverExtensions)) {
                $request->addError(
                    'cover',
                    'Файл обложки должен быть в одном из следующих форматов: ' . implode(',', Request::$coverExtensions)
                );
            } elseif ($cover->size == 0 && !$request->isNewRecord) {
                $request->cover = $oldCover;
            } else {
                $request->cover = $cover->name;
            }
            $request->platforms = json_encode($request->platforms);
            if ($request->validate(null, false)) {
                $request->save(false);
                $dirName = ROOT_DIR . '/content/books/' . $request->id;

                $cover->saveAs($dirName . "/" . $cover->name);
                $file->saveAs($dirName . "/" . $file->name);
                return $this->redirect(['request-form', 'requestId' => $request->id]);
            } else {
                $request->platforms = json_decode($request->platforms, true);
            }
        }

        return $this->render('add', ['request' => $request]);
    }
} 