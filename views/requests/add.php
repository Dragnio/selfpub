<?php
/**
 * @var Request       $request
 * @var \yii\web\View $this
 */
use app\models\Request;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = $request->isNewRecord ? "Добавить запрос" : "Запрос " . $request->bookName;
$form = ActiveForm::begin(
    [
        'action'  => \Yii::$app->urlManager->createUrl(['requests/request-form', 'requestId' => $request->id]),
        'options' => array('enctype' => 'multipart/form-data')
    ]
);
$canEdit = $this->context->user->can(
        "admin"
    ) || ($this->context->user->id == $request->userId && $request->status < Request::STATUS_IN_PROCESS);
?>
<?php
if ($request->errors) {
    ?>
    <div class="well">
        <?php
        foreach ($request->errors as $field => $error) {
            ?>
            <p><?= implode("<br />", $error) ?></p>
        <?php
        }
        ?>
    </div>
<?php
}
?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'bookName')->textInput(['disabled' => !$canEdit]) ?>
        </div>
        <div class="col-lg-6">
            <p class="pull-right"><span
                    class="label label-<?= Request::$statusClasses[$request->status] ?>"><?= Request::$statuses[$request->status] ?></span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'authorName')->textInput(['disabled' => !$canEdit]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'synopsis')->textarea(['disabled' => !$canEdit]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?=
            $form->field($request, 'participants')->hint('Вася - Художник<br />Петя - Писатель')->textarea(
                ['disabled' => !$canEdit]
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?=
            $form->field($request, 'language')->dropDownList(
                [
                    'Русский',
                    'Английский'
                ],
                ['disabled' => !$canEdit]
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?=
            $form->field($request, 'license')->radioList(
                ['Своя книга', 'Общественное достояние'],
                ['disabled' => !$canEdit]
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'category')->textInput(['disabled' => !$canEdit]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'tags')->hint('Через запятую')->textInput(['disabled' => !$canEdit]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <?php
            $coverField = $form->field($request, 'cover');
            if ($request->cover != '') {
                $coverField->hint('Загружен файл ' . $request->cover);
            }
            echo $coverField->fileInput(['disabled' => !$canEdit]);
            ?>
            <?php
            $fileField = $form->field($request, 'file');
            if ($request->file != '') {
                $fileField->hint('Загружен файл ' . $request->file);
            }
            echo $fileField->fileInput(['disabled' => !$canEdit]);
            if ($request->file != '') {
                ?>
                <a class="btn btn-success" href="<?= $request->getFileUrl() ?>">Скачать</a><br/><br/>
            <?php
            }
            ?>
            <?=
            $form->field($request, 'platforms')->checkboxList(
                [
                    'Amazon',
                    'Amazon2',
                    'Amazon3'
                ],
                ['disabled' => !$canEdit]
            ) ?>
        </div>
        <div class="col-lg-6">
            <?php
            if ($request->cover != '') {
                ?>
                <img src="<?= $request->getCoverUrl() ?>" alt="Обложка"/>
            <?php
            }
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'cost')->textInput(['disabled' => !$canEdit]) ?>
        </div>
    </div>
<?php
if ($this->context->user->can("admin")) {
    ?>
    <h2>Управление</h2>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'status')->dropDownList(Request::$statuses) ?>
        </div>
    </div>
<?php
}
?>
<?=
Html::submitButton(
    $request->isNewRecord ? 'Добавить' : 'Сохранить',
    ['class' => 'btn btn-primary', 'disabled' => !$canEdit]
) ?>
<?php
$form->end();
if (!$request->isNewRecord) {
    ?>
    <h2>Комментарии</h2>
    <?php
    $comments = $request->getComments()->where(['parentId' => 0])->all();
    if ($comments) {
        foreach ($comments as $comment) {
            echo $this->render('@app/views/requests/comment', ['request' => $request, 'comment' => $comment]);
        }
    } else {
        ?>
        <p>Будьте первым, кто оставит комментарий</p>
    <?php
    }
    ?>
    <div class="form" style="margin-top: 15px">
        <?php
        $newComment = new \app\models\Comment();
        $form = ActiveForm::begin(
            [
                'action' => \Yii::$app->urlManager->createUrl(['requests/comment-form', 'requestId' => $request->id]),
            ]
        );
        echo $form->field($newComment, 'comment')->textarea();
        echo Html::submitButton(
            'Добавить',
            ['class' => 'btn btn-primary']
        );
        $form->end();
        ?>
    </div>
<?php
}