<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->title;

\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

        <div class="card">
  <div class="card-body" id = "product-view">
    
    
  <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'title',

            [
                'attribute'=>'fileUser',
                'value'=> Html::img(Yii::getAlias('@web') . '/uploads/' . $model->photo , ['class' => 'photo_view']),
                'format' => 'raw',

            ],
            

            'price',
            ['attribute'=>'category_id', 'value' =>function($model){
            return Category::findCategory()[$model->category_id];
            }],
            'model',
            'country',
            'year',
            'description',
        ],
    ]) ?>
    <p><a class="btn btn-primary" href="/katalog">Каталог</a></p>
    <a data-product-id ='<?= $model->id ?>' class='btn btn-primary cart-add' href=/cart/view>В корзину</a>
  </div>
</div>

    
</div>
