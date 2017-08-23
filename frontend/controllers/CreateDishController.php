<?php

namespace frontend\controllers;

use common\models\DishesIngredients;

class CreateDishController extends \yii\web\Controller
{
    public function actionCreate()
    {
        $model = new DishesIngredients();

        return $this->render('create',['model' => $model]);
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

}
