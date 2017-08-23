<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DishesIngredients */

$this->title = 'Update Dishes Ingredients: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dishes Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dishes-ingredients-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
