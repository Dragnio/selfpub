<?php
/**
 * @var \app\models\Request $request
 */
use yii\bootstrap\ActiveForm;

$this->title = "Добавить запрос";
$form = ActiveForm::begin(['layout' => 'horizontal']);
?>
<?= $form->field($request, 'bookName')->textInput() ?>
<?php
$form->end();