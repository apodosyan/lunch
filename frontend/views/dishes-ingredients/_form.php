<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DishesIngredients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dishes-ingredients-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dish_id')->textInput() ?>

    <?= $form->field($model, 'ingredient_id')->textInput() ?>

    <?= $form->field($model, 'size')->textInput() ?>

    <?= $form->field($model, 'measurement_type')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
