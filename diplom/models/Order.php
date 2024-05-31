<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status_id
 * @property float $sum
 * @property int $count
 * @property string $time
 *
 * @property Status $status
 * @property User $user
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status_id', 'sum', 'count'], 'required'],
            [['user_id', 'status_id', 'count'], 'integer'],
            [['sum'], 'number'],
            [['time'], 'safe'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Заказ №',
            'user_id' => 'Пользователь',
            'status_id' => 'Статус',
            'sum' => 'Цена',
            'count' => 'Количество',
            'time' => 'Время',
        ];
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }
    
    public function orderCreate($cart)
    {   

        $this->user_id = Yii::$app->user->id;
        $this->status_id = Status::getIdStatusName('Новый');
        $this->sum = $cart['sum'];
        $this->count = $cart['count'];
//        var_dump($this->attributes);die;
//         if( !$this->save() )
//         {
//             var_dump($this->errors);
//             var_dump(__LINE__);die;
//         } else{
//             var_dump($this->errors);
//             var_dump(__LINE__);die;
//         }
//      var_dump($this->attributes);die;

        return $this->save();

    }

}
