<?php
/**
 * @var \app\models\Request $request
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = "Добавить запрос";
$form = ActiveForm::begin();
?>
    <h1>Add Request</h1>
<?= $form->field($request, 'bookName')->textInput() ?>
<?= $form->field($request, 'authorName')->textInput() ?>
<?= $form->field($request, 'synopsis')->textInput() ?>
<?= $form->field($request, 'participants')->textInput() ?>
<?= $form->field($request, 'language')->textInput() ?>
<?= $form->field($request, 'license')->textInput() ?>
<?= $form->field($request, 'category')->textInput() ?>
<?= $form->field($request, 'tags')->textInput() ?>
<?= $form->field($request, 'cover')->textInput() ?>
<?= $form->field($request, 'file')->fileInput() ?>
<?= $form->field($request, 'platforms')->textInput() ?>
<?= $form->field($request, 'cost')->textInput() ?>
<?= Html::submitButton('Add', ['class' => 'btn btn-primary']) ?>
<?php
$form->end();