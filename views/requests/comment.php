<?php
use app\models\Comment;
use app\models\Request;

/**
 * @var Request $request
 * @var Comment $comment
 */

?>
<div class="comment" id="comment<?= $comment->id ?>">
    <p class="author"><?= $comment->user->getDisplayName() ?>, <?= date('d.m.Y H:i:s', $comment->dateAdded) ?></p>

    <p><?= $comment->comment ?></p>
</div>