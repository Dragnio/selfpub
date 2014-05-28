<?php
/**
 * @var \app\models\Request $request
 * @var \yii\web\View       $this
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = $request->isNewRecord ? "Добавить запрос" : "Запрос " . $request->bookName;
$form = ActiveForm::begin(
    [
        'action'  => \Yii::$app->urlManager->createUrl(['requests/request-form', 'requestId' => $request->id]),
        'options' => array('enctype' => 'multipart/form-data')
    ]
);
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
            <?= $form->field($request, 'bookName')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'authorName')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'synopsis')->textarea() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'participants')->hint('Вася - Художник<br />Петя - Писатель')->textarea() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?=
            $form->field($request, 'language')->dropDownList(
                [
                    'Русский',
                    'Английский'
                ]
            ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'license')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'category')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($request, 'tags')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <?php
            $coverField = $form->field($request, 'cover');
            if ($request->cover != '') {
                $coverField->hint('Загружен файл ' . $request->cover);
            }
            echo $coverField->fileInput();
            ?>
            <?php
            $fileField = $form->field($request, 'file');
            if ($request->file != '') {
                $fileField->hint('Загружен файл ' . $request->file);
            }
            echo $fileField->fileInput();
            ?>
            <?=
            $form->field($request, 'platforms')->checkboxList(
                [
                    'Amazon',
                    'Amazon2',
                    'Amazon3'
                ]
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
            <?= $form->field($request, 'cost')->textInput() ?>
        </div>
    </div>
<?= Html::submitButton('Add', ['class' => 'btn btn-primary']) ?>
<?php
$form->end();