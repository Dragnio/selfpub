<?php
use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string        $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin(
        [
            'brandLabel' => \Yii::$app->name,
            'brandUrl'   => Yii::$app->homeUrl,
            'options'    => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]
    );
    echo Nav::widget(
        [
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items'   => [
                ['label' => 'Home', 'url' => ['/site/index']],
                [
                    'label'   => 'List of requests',
                    'url'     => ['/requests/list'],
                    'visible' => !\Yii::$app->user->isGuest
                ],
                [
                    'label'   => 'Add request',
                    'url'     => ['/requests/request-form'],
                    'visible' => !\Yii::$app->user->isGuest
                ],
                [
                    'label'   => 'Account',
                    'url'     => ['/user/account'],
                    'visible' => !\Yii::$app->user->isGuest
                ],
                Yii::$app->user->isGuest ?
                    ['label' => 'Login', 'url' => ['/user/login']] :
                    [
                        'label'       => 'Logout',
                        'url'         => ['/user/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ],
            ],
        ]
    );
    NavBar::end();
    ?>

    <div class="container">
        <?php
        if (!($this->context->module instanceof \amnah\yii2\user\Module)) {
            ?>
            <h1><?= $this->title ?></h1>
        <?php
        }
        ?>

        <?php if ($error = \Yii::$app->session->getFlash('error')) {
            echo Alert::widget(
                [
                    'body'    => $error,
                    'options' => [
                        'class' => 'alert-danger'
                    ]
                ]
            );
        } ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; SelfPub Inc. <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
