<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Comment;
use app\models\Request;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\imagine\Image;
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
            if ($request->userId != $this->user->id && !$this->user->can("admin")) {
                throw new HttpException(403);
            }
            $request->platforms = json_decode($request->platforms, true);
        } else {
            $request = new Request();
            $request->userId = $this->user->id;
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
                    'Book file should in one if this formats: ' . implode(',', Request::$fileExtensions)
                );
            } elseif ($file->size == 0 && !$request->isNewRecord) {
                $request->file = $oldFile;
            } else {
                $request->file = $file->name;
            }
            $cover = UploadedFile::getInstance($request, 'cover');
            if ($cover->size > 0) {
                if (!in_array($cover->extension, Request::$coverExtensions)) {
                    $request->addError(
                        'cover',
                        'Cover file should in one if this formats: ' . implode(
                            ',',
                            Request::$coverExtensions
                        )
                    );
                }
                $imagine = Image::getImagine();
                $image = $imagine->open($cover->tempName);
                $size = $image->getSize();
                if ($size->getHeight() > 1000) {
                    $request->addError(
                        'cover',
                        'Cover height should be <= 1000px'
                    );
                }
                if ($size->getWidth() > 1000) {
                    $request->addError(
                        'cover',
                        'Cover width should be <= 1000px'
                    );
                }
                if (!$request->hasErrors('cover')) {
                    $request->cover = $cover->name;
                }
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

    public function actionList()
    {
        $query = Request::find();
        if (!$this->user->can("admin")) {
            $query->andWhere(['userId' => $this->user->id]);
        }
        $sort = new Sort();
        $sort->attributes = [
            'id',
            'dateAdded'
        ];
        $sort->defaultOrder = ['dateAdded' => SORT_DESC];

        $sort = new Sort(
            [
                'attributes'   => [
                    'id',
                    'dateAdded',
                    'bookName',
                    'status'
                ],
                'defaultOrder' => [
                    'dateAdded' => SORT_DESC
                ]
            ]
        );


        $dataProvider = new ActiveDataProvider(
            [
                'sort'       => $sort,
                'query'      => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]
        );

        return $this->render('list', ['dataProvider' => $dataProvider]);
    }

    public function actionRequestDelete($requestId)
    {
        $request = Request::findOne($requestId);
        if (!$request) {
            throw new HttpException(404);
        }
        if ($request->userId != $this->user->id && !$this->user->can("admin")) {
            throw new HttpException(403);
        }
        $request->delete();
        $this->redirect(['list']);
    }

    public function actionCommentForm($requestId)
    {
        $request = Request::findOne($requestId);
        if (!$request) {
            throw new HttpException(404);
        }
        if ($request->userId != $this->user->id && !$this->user->can("admin")) {
            throw new HttpException(403);
        }

        $comment = new Comment();
        $comment->requestId = $request->id;
        $comment->dateAdded = time();
        $comment->parentId = 0;
        $comment->userId = $this->user->id;

        if (\Yii::$app->request->isPost) {
            $comment->load(\Yii::$app->request->post());
            if ($comment->validate()) {
                $comment->save();
            } else {
                \Yii::$app->session->setFlash('commentError', $comment->getErrors());
            }
        }
        return $this->redirect(['request-form', 'requestId' => $request->id, '#' => 'comment' . $comment->id]);
    }

    public function actionView($requestId)
    {
        $request = Request::findOne($requestId);
        if (!$request || $request->status != Request::STATUS_ACCEPTED) {
            throw new HttpException(404);
        }

        return $this->render('request', ['request' => $request]);
    }
} 