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
            'brandLabel' => 'My Company',
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
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
                ['label'   => 'Logout (' . \Yii::$app->user->identity->user->name . ')',
                 'url'     => ['/site/logout'],
                 'visible' => !\Yii::$app->user->isGuest
                ],
            ],
        ]
    );
    if (\Yii::$app->user->isGuest) {
        ?>
        <div class="pull-right">
            <?php
            echo Html::beginForm(
                \Yii::$app->urlManager->createUrl("site/login"),
                'post',
                ['class' => 'form-horizontal']
            );
            echo Html::textInput("login", '', ['placeholder' => 'Login', 'class' => 'form-control']);
            echo Html::passwordInput("password", '', ['placeholder' => 'Password', 'class' => 'form-control']);
            echo Html::submitButton("Login", ['class' => 'form-control btn btn-success']);
            echo Html::endForm();
            ?>
        </div>
    <?php
    }

    NavBar::end();
    ?>

    <div class="container">
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
        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
