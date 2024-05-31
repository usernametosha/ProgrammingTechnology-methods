<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['attribute'=>'status_id', 'value' =>function($model){
                return \app\models\Status::getStatus()[$model->status_id];
            }],
            'time',
            ['attribute' => 'user_id',
                'value' => 'user.name',
            ],
//            ['attribute' => 'user_id',
//                'value' => $model->user->name . ' ' . $model->user->surname . ' ' . $model->user->patronymic,
//            ],
            'count',
            'sum',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }

            ],
        ],
    ]); ?>


</div>
