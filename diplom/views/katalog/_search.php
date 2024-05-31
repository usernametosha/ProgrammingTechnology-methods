<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */

?>



<div class="row">
    <div class="col-md-6">
        <div>Сортировать по:</div>
        <div class="row sort">
            <div class="sort-item first-item "> <?= $dataProvider->sort->link('title')?></div>
            <div style="margin-left: 10px" class="sort-item"> <?= $dataProvider->sort->link('price')?></div>
            <div style="margin-left: 10px" class="sort-item last-item "> <?= $dataProvider->sort->link('year')?></div>
        </div>
    </div>



<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <?php
    $params = 
    [
        'prompt' => 'Все категории'
    ];
    echo $form->field($model, 'category_id')->dropDownList($categories, $params)->label('Выбрать категорию');
    ?>
    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сброс', ['/katalog'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
    </div>
    </div>
    <div class="row">
        <div class="col">

    </div>
    </div>


   
