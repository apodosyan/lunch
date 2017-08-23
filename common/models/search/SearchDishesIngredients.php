<?php

namespace common\models\search;

use common\models\Dishes;
use common\models\Ingredients;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DishesIngredients;

/**
 * SearchDishesIngredients represents the model behind the search form about `common\models\DishesIngredients`.
 */
class SearchDishesIngredients extends DishesIngredients
{

    public $dishName;
    public $ingredientname;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id', 'dish_id', 'ingredient_id', 'size'], 'integer'],
            //[['ingredient_id'], 'required'],
            [['ingredient_id'], 'each', 'rule' => ['integer']],
            [['ingredient_id'], 'checkIdCount',]  //'skipOnEmpty' => false, 'skipOnError' => false],
            //[['name'], 'each', 'rule' => ['integer']],
        ];
    }

    public function checkIdCount($attribute)
    {
        $IdArray = Yii::$app->request->get('SearchDishesIngredients');
        if (count($IdArray['ingredient_id']) < 1
            || count($IdArray['ingredient_id']) > 5) {
            $this->addError($attribute, 'Выберите не менее двух и не более пяти ингредиентов.');
        }
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DishesIngredients::find()->innerJoinWith('dish')->innerJoinWith('ingredient');
        $query = $query->select([
            'GROUP_CONCAT(`ingredients`.`name` SEPARATOR \', \') as ingname',
            'GROUP_CONCAT(`ingredients`.`status` SEPARATOR \', \') as stat',
            'ingredients.status',
            'dishes.name',
            'dishes_ingredients.*'
        ]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query->groupBy('dishes.name')
                             ->having(['<=', 'COUNT(`dishes_ingredients`.`dish_id`)', 5])
                             ->orderBy(['COUNT(`ingredients`.`name`)' => SORT_DESC]),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['in', 'dishes_ingredients.ingredient_id', $this->ingredient_id]);

        $modelArr = [];
        $models =  $dataProvider->getModels();
        foreach($models as $key => $model){
           if(substr_count($model->ingname, ",") == 4 && substr_count($model->stat, "0") > 0) {
               $modelArr[] = $model;
           }
           else {
                unset($models[$key]);
           }
        }
        if(!empty($modelArr)) {
            $dataProvider->setModels($modelArr);
        }
        else {
            $models = $dataProvider->getModels();
            foreach($models as $model){
                if(substr_count($model->ingname, ",") !== 0 && substr_count($model->stat, "0") == 0) {
                    $modelArr[] = $model;
                }
            }
            $dataProvider->setModels($modelArr);
        }

        $test =  $dataProvider->getModels();
        //$sql = $query->createCommand()->getRawSql();
        //var_dump($sql);die;
        return $dataProvider;
    }
}
