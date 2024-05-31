<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = 'Заказ №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои заказ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                    'attribute' =>   'status_id',
                'value' => $status[$model->status_id],
            ] ,
            'time',
            ['attribute' => 'user_id',
            'value' => $model->user->name . ' ' . $model->user->surname . ' ' . $model->user->patronymic,
            ],
            'count',
            'sum',
            [
                    'label' => 'Состав заказа',
                'format' => 'html',
                'value' => function() use($products){
                          //  var_dump($products);die;
                           $s = '<table><tr><td>№</td><td>Наименование</td><td>Количество</td><td>Цена</td><td>Сумма</td></tr>';
                           $i = 0;
                            foreach ($products as $val)
                            {
                               $sum = $val['price'] * $val['count_product'];
                               $i++;
                               $s .=   "<tr><td>$i</td><td>{$val['product_title']}</td><td>{$val['count_product']}</td><td>{$val['price']}</td><td>{$sum}</td></tr>";
                            }
                            $s .= '</table>';
                             // var_dump($s);die;
                                return $s;
                },
            ],

        ],
    ]) ?>

</div>
