<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\SearchDishesIngredients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dishes-ingredients-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ingredient_id')->widget(\kartik\select2\Select2::className(),[
        'data' => \yii\helpers\ArrayHelper::map(\common\models\Ingredients::find()->all(),'id','name'),
        'options' => [
            'placeholder' => 'Ингредиенты',
            'multiple' => true
        ],
        'pluginOptions' => [
            'tags' => true,
            'allowClear' => true
        ],
    ])->label('Ингредиент') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
