<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = ['label' => 'Мои заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">
    <h3><?=Html::encode($this->title)?></h3>
    <?= $this->render('../cart/view.php',['cart' => $cart, 'dataProvider' => $dataProvider, 'btn_no' => true]) ?>
    <div class="mt-3" id="agree">
        <?php $form = ActiveForm::begin([
                'id' => 'confirm-form',
            'enableAjaxValidation' => true,
        ]);?>
        <div class="offset-7 col-5 align-items-start justify-content-end">
            <?= $form->field($login,'password')->passwordInput(['placeholder' => 'Введите ваш пароль', 'id' => 'paw'])->label('Для подтверждения заказа введите свой пароль') ?>
            <?=Html::submitButton('Сформировать заказ', ['class' => 'btn btn-primary flex-fill', 'name' => 'confirm-button','id' => 'agree'])?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
