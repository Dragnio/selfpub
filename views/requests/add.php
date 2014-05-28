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
<?= $form->field($request, 'bookName')->textInput() ?>
<?= $form->field($request, 'authorName')->textInput() ?>
<?= $form->field($request, 'synopsis')->textarea() ?>
<?= $form->field($request, 'participants')->textInput() ?>
<?= $form->field($request, 'language')->dropDownList(
    [
        'Русский',
        'Английский'
    ]
) ?>
<?= $form->field($request, 'license')->textInput() ?>
<?= $form->field($request, 'category')->textInput() ?>
<?= $form->field($request, 'tags')->textInput() ?>
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
<?= $form->field($request, 'platforms')->checkboxList(
    [
        'Amazon',
        'Amazon2',
        'Amazon3'
    ]
) ?>
<?= $form->field($request, 'cost')->textInput() ?>
<?= Html::submitButton('Add', ['class' => 'btn btn-primary']) ?>
<?php
$form->end();