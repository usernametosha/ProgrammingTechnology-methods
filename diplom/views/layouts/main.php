<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;
use yii\helpers\Url;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <link rel="shortcut icon" href="<?= Url::base() ?>/web/favicon.png" type="image/x-icon">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => 'ПКБоярин',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top navbar-center ',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav '],
        'items' => [
            ['label' => 'Каталог', 'url' => ['/katalog/index']],
            ['label' => 'О нас', 'url' => ['/site/about']],
            ['label' => 'Где нас найти?', 'url' => ['/site/location']],
            Yii::$app->user->isGuest ? ['label' => 'Регистрация', 'url' => ['/site/registration']] : '',
            Yii::$app->user->identity->isAdmin ? ['label' => 'Панель управления', 'url' => ['/profile/index']] :'',
            !Yii::$app->user->isGuest || Yii::$app->user->identity->isAdmin ? ['label' => 'Личный кабинет', 'url' => ['/lk/index']] :'',
            Yii::$app->user->isGuest ? (
                ['label' => 'Авторизация', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    'Выход (' . Yii::$app->user->identity->login . ')',
                    ['class' => 'btn btn-success logout']
                )
                . Html::endForm()
                . '</li>'
            ),
            !Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin ? ['label' => 'Корзина' , 'url' => '','linkOptions' => ['id' => 'cart-link'],'options' => [ 'class' => 'float-right']] : '',
        ],
    ]);
//    if ( !(Yii::$app->user->isGuest || Yii::$app->user->identity->isAdmin)):
//    ?>
<!--        <div class="flex-grow-1">-->
<!--            <div id="cart-link" class="text-center" style = "cursor: pointer"> <img src="/web/cart.png"></div>-->
<!--        </div>-->
<?php
//endif;
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php

if( !(Yii::$app->user->isGuest || Yii::$app->user->identity->isAdmin) ):
     Modal::begin([
         'title' => 'Корзина',
         'options' => ['id' => 'cart', 'class' => 'footer--modal'],
         'size' => 'modal-xl',
         'bodyOptions' => ['id' => 'body-cart'],
         'footer' => '
         <div><a href="/cart/view?btn=trash&id=1" class="btn btn-danger disabled m-1" id="clear">Очистить корзину</a></div>
         <div><button class="btn btn-primary m-1" data-dismiss="modal">Продолжить покупки</button></div>
         <div><a href="/lk/create" class="btn btn-success disabled m-1" id="order">Оформить заказ</a></div>',
     ]);
     Pjax::begin([ 'id' => 'cart-pjax', 'enablePushState' => false, 'timeout' => 5000]);
     Pjax::end();
Modal::end();

$this->registerJsFile('/js/cart.js',
[
    'depends' => ['yii\web\YiiAsset', 'yii\bootstrap4\BootstrapAsset'],
    'position' => yii\web\View::POS_END
]
);
Modal::begin([
    'title' => 'Информация',
    'headerOptions'=>['class'=> 'text-light bg-primary'],
    'options'=> ['id' => 'modal-error'],
    ]);
    echo "<p>Добавлено доступное количество товара.</p>";
    Modal::end();
    endif;
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

