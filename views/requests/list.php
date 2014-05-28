<?php
use app\models\Request;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/**
 * @var ActiveDataProvider $dataProvider
 * @var \yii\web\View      $this
 */

$this->title = "Запросы";
echo GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'columns'      => [
            'id',
            ['attribute' => 'user.displayName', 'label' => 'Пользователь'],
            'bookName',
            [
                'attribute' => 'dateAdded',
                'value'     => function ($model, $index, $widget) {
                    return date("d.m.Y H:i:s", $model->dateAdded);
                }
            ],
            [
                'attribute' => 'status',
                'format'    => 'html',
                'value'     => function ($model, $index, $widget) {
                    return '<span
                    class="label label-' . Request::$statusClasses[$model->status] . '">' . Request::$statuses[$model->status] . '</span>';
                }
            ],
            [
                'class'      => ActionColumn::className(),
                'template'   => '{update} {delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    switch ($action) {
                        case 'update':
                            return \Yii::$app->urlManager->createUrl(
                                ['requests/request-form', 'requestId' => $model->id]
                            );
                            break;
                        case 'delete':
                            return \Yii::$app->urlManager->createUrl(
                                ['requests/request-delete', 'requestId' => $model->id]
                            );
                            break;
                        default:
                            return false;
                            break;
                    }
                }
                // you may configure additional properties here
            ],
        ]
    ]
);
?>