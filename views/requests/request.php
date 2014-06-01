<?php
use app\models\Request;

/**
 * @var Request $request
 */

$this->title = $request->bookName;
?>
<div class="row">
    <div class="col-lg-2"><a href="<?= $request->getCoverUrl() ?>" data-toggle="lightbox"
                             data-title="<?= $request->bookName ?>"><img
                src="<?= $request->getCoverUrl() ?>" alt="<?= $request->bookName ?>"
                class="thumbnail" style="max-width: 100%"/></a></div>
    <div class="col-lg-8">
        <p>Author: <b><?= $request->authorName ?></b></p>

        <p><?= $request->synopsis ?></p>

        <p>Language: <b><?= Request::$languages[$request->language] ?></b></p>
        <a class="btn btn-success" href="<?= $request->getFileUrl() ?>">Download</a>
    </div>
</div>