<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\SearchDishesIngredients */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поиск Блюд';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dishes-ingredients-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'value' => function(\common\models\DishesIngredients $dishesIngredients) {
                    return $dishesIngredients->dish->id;
                }
            ],
            [
                'attribute' => 'Dish',
                'label' => 'Название Блюда',
                'filter' => false,
                'value' => function(\common\models\DishesIngredients $dishesIngredients) {
                    return $dishesIngredients->dish->name;
                }
            ],
            [
                'attribute' => 'ingname',
                'label' => 'Ингредиенты',
                'filter' => false,
                'value' => function($model) {
                    return $model->ingname;
                }
            ],

            /*[
                'attribute' => 'name',
                'label' => 'Dish Name',
                'value' => 'dish.name',
                'filter' => false,
                /*'value' => function($model) {
                    return $model->dish->name;
                }
            ],*/

            ['class' => 'yii\grid\ActionColumn','template' => '',],
        ],
    ]); ?>
</div>
