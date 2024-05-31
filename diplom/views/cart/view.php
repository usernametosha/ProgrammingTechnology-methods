<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\LinkPager;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$fill=!empty($cart['count']) ? "cart-fill": "";
?>
<div class="cart-content <?=$fill?>">

<?php if (!empty($cart['count'])) : ?>
    <?php ActiveForm::begin([
    'action' => $url,
    'id' => 'from-cart',
    'options' => ['data-pjax' => true],
    ]) ?>
    <?= GridView::widget([
'dataProvider' => $dataProvider,
'showFooter' => true,
    'footerRowOptions' => ['class' => "font-weight-bold", 'style' => 'font-size: 1.2em'],
'pager' => ['class' => \yii\bootstrap4\LinkPager::class],
'columns' => [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function($model){
            return Html::a("<img src='/uploads/{$model['photo']}' style='width: 100px;'>
            " , ['/katalog/view?id=' . $model['id'], 'data-pjax' => 0 ])
            . Html::a( $model['title'], [ '/katalog/view?id=' . $model['id'], ['data-pjax' => 0]]);
        },
        'label' => 'Наименование',
        'footer' => 'Итого',
],
[
    'attribute' => 'price',
    'label' => 'Стоимость (руб.)',
    'contentOptions' => ['class' => "text-center"],
],
[
        'attribute' => 'count',
        'format' => 'raw',
        'label' => 'Количество',
        'value' => function($model) use ($url, $btn_no){
            $minus = $url . 'btn=minus&id=' . $model['id'];
            $plus = $url . 'btn=plus&id=' . $model['id'];
            return '<div class="d-flex justify-content-center block-value">'
            .( !empty($btn_empty) ? Html::a('<div>-</div>', $minus, ['class' =>'btn-delete btn-minus btn-rnd', 'data-method' => 'post']) : "" )
            . "<span>{$model['count']}</span>"
            .( !empty($btn_empty) ? Html::a('<div>+</div>', $plus, ['class' =>'btn-plus btn-minus btn-rnd cart-add ml-2', 'data-method' => 'post']) : "")
            . "</div>";
        },
        'footer' => $cart['count'],
        'footerOptions' => ['class' => "text-center"],
],
[
    'attribute' => 'sum',
    'label' => 'Сумма (руб.)',
    'contentOptions' => ['class' => "text-center"],
    'footer' => $cart['sum'],
    'footerOptions' => ['class' => "text-center"],
],
[
    'format' => 'raw',
    'value' => function($model) use ($url) {
        $url .= 'id=' . $model['id'] . '&btn-trash';
        return Html::a('<svg aria-hidden-"true" style="display:inline-block;font-size:inherin;height:lem;overflow:visible;vertical-align:-,125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><pathfill="currontColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>', [$url],
            [
                'class' => "text-danger btn-del-item",
                'data-data-id' => $model['id'],
                'data-method' => 'post',
            ]);
    }
],
],
]); ?>
<?php ActiveForm::end(); ?>
<?php else: ?>
        Ваша корзина пуста
<?php endif;?>


</div>
<?php if($res === false): ?>
<script>
    $('#modal-error').modal('show');
    setTimeout(function() {
        $('#modal-error').css('opacity', 1)
        $('#modal-error').fadeTo(4000, 0, function(){
            $('#modal-error').modal('hide');
        });
        }, 1000);
        </script>
        <?php endif; ?>