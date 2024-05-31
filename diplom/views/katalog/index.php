<?php

use yii\bootstrap4\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Каталог';
?>


<div class="product-index" id = 'pjax-catalog'>

    <h1><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(['id'=>'pjax-block-katalog', 'enablePushState' => false, 'timeout' => 5000]); ?>
    <?php echo $this->render('_search', ['dataProvider' => $dataProvider, 'categories' => $categories, 'model' => $searchModel]); ?>
    
    
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) { 
            $a = "<a data-product-id = '$model->id' class=\"btn btn-primary cart-add \" href=''>В корзину</a>";
            $content = "<div class=\"card\">
            <div style=\"text-align:center;\" class=\"card-body\"> 
            <a style='text-decoration: none' href=' " . Url::to(['view', 'id'=>$model->id]) . " '><h3 class='card-title'>$model->title</h3></a> 
            <div><a href='view?id=$model->id'></div><img src='/uploads/{$model['photo']}' class=fileUse></a>
            <div><li>Цена: $model->price ₽.</li></div>
            <div><li>Год выхода: $model->year</li></div>
            <div style=\"padding:15px; text-align:center;\"><p>$a</p></div>
        </div></div>";
        

            return $content;
        },
        'layout' => '{pager}<div class="row">{items}</div>{pager}',
        'pager' => ['class' => \yii\bootstrap4\LinkPager::class],
    ]) ?>

    <?php Pjax::end(); ?>

</div>
