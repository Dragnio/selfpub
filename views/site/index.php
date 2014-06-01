<?php
/**
 * @var yii\web\View          $this
 * @var \app\models\Request[] $requests
 */
$this->title = \Yii::$app->name . " - Published Books";
?>
<div class="site-index">
    <div class="row">
        <?php
        foreach ($requests as $request) {
            ?>
            <div class="request col-lg-3">
                <h3>
                    <a href="<?= $request->publicUrl ?>"> <?= $request->bookName ?></a>
                </h3>
                <img src="<?= $request->getCoverUrl() ?>" alt="<?= $request->bookName ?>"/><br/>

                <p>Author's name: <b><?= $request->authorName ?></b></p>
            </div>
        <?php
        }
        ?>
    </div>
</div>
