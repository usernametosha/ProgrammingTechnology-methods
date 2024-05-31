<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $title
 * @property string $photo
 * @property float $price
 * @property int $category_id
 * @property string $model
 * @property string $country
 * @property string $year
 * @property int $count
 *  @property string $description
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{
    public $fileUser;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public function rules()
    {
        return [
            [['title', 'price', 'category_id', 'model', 'country', 'year', 'count','description'], 'required'],
            [['price', 'count'], 'number'],
            [['category_id'], 'integer'],
            [['year'], 'safe'],
            [['description'], 'string'],
            [['fileUser'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, jpeg, bmp, png', 'maxSize' => 1024*1024*10, 'on' => self::SCENARIO_CREATE],
            [['fileUser'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, bmp, png', 'maxSize' => 1024*1024*10, 'on' => self::SCENARIO_UPDATE],
            [['title', 'photo', 'model', 'country', ], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'photo' => 'Фото',
            'fileUser' => 'Фото',
            'price' => 'Цена',
            'category_id' => 'Категория',
            'model' => 'Модель',
            'country' => 'Страна - производитель',
            'year' => 'Год релиза',
            'count' => 'Количество',
            'description' => 'Описание',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    public function upload()
    {
        if ($this->validate()) {

            $file_name =  Yii::$app->user->id . '_' . time()  . '.' . $this->fileUser->extension;
            $this->fileUser->saveAs('uploads/' . $file_name);
            $this->photo = $file_name;
            return true;
        } else {
            return false;
        }
    }

    public static function findProduct() {
        return (new \yii\db\Query())
            ->select(['title', 'photo'])
            ->from(static::tableName())
            ->orderBy('id DESC')
            ->limit(5)
            ->all();
    }
}
