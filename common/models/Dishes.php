<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dishes".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 *
 * @property DishesIngredients[] $dishesIngredients
 */
class Dishes extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dishes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['type'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
        ];
    }

    public function getIngredients()
    {
        return $this->hasMany(Ingredients::className(), ['id' => 'dish_id'])
            ->viaTable('dishes_ingredients', ['dish_id' => 'id']);
    }


}
