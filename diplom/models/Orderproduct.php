<?php

namespace app\models;

use Yii;
use app\models\Cart;
/**
 * This is the model class for table "orderproduct".
 *
 * @property int $id
 * @property int $product_id
 * @property float $price
 * @property int $count
 * @property string $title
 *  @property int $order_id
 */
class Orderproduct extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'price', 'count', 'title','order_id'], 'required'],
            [['product_id', 'count','order_id'], 'integer'],
            [['price'], 'number'],
            [['title'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'order_id' => 'Order_id',
            'price' => 'Price',
            'count' => 'Count',
            'title' => 'Title',
        ];
    }
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    public static function orderItemsCreate($cart,$order_id)
    {
        foreach ($cart['products'] as $val)
        {
            if ($product = Product::findOne($val['id']))
            {
                $order_product = new static();
                // var_dump(1);die;

                $order_product->order_id = $order_id;
                $order_product->product_id = $val['id'];
                $order_product->price = $val['price'];
                $order_product->title = $val['title'];
                $order_product->count = $val['count'];
                //var_dump($order_product->attributes);die;
                if ($res = $order_product->save())
                {
                    $product->count -= $val['count'];
                    $res = $product->save();

                } else
                {
                    var_dump($order_product->errors);die;
                }
                if (!$res)
                    return $res;
            }
        }
        return true;
    }
}
