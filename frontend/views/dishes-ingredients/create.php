<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DishesIngredients */

$this->title = 'Create Dishes Ingredients';
$this->params['breadcrumbs'][] = ['label' => 'Dishes Ingredients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dishes-ingredients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
