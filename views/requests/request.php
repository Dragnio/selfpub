<?php
use app\models\Request;

/**
 * @var Request $request
 */

$this->title = $request->bookName;
$langs = [
    'Русский',
    'Английский'
];
?>
<div class="row">
    <div class="col-lg-2"><img src="<?= $request->getCoverUrl() ?>" alt="<?= $request->bookName ?>"
                               class="thumbnail"/></div>
    <div class="col-lg-8">
        <p>Автор: <b><?= $request->authorName ?></b></p>

        <p><?= $request->synopsis ?></p>

        <p>Язык: <b><?= $langs[$request->language] ?></b></p>
    </div>
</div>