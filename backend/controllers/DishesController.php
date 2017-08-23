<?php

namespace backend\controllers;

use common\models\DishesIngredients;
use Yii;
use common\models\Dishes;
use common\models\search\SearchDishes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DishesController implements the CRUD actions for Dishes model.
 */
class DishesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Dishes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchDishes();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dishes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Dishes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $rows = [];
        $model = new Dishes();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $dishes = Yii::$app->request->post('Dishes');

            $model->type = implode(',',$dishes['type']);
            if($model->save(false)){
                foreach($dishes['type'] as $key => $ingredient_id){
                    $rows[$key]['dish_id'] = $model->id;
                    $rows[$key]['ingredient_id'] = $ingredient_id;
                }
                Yii::$app->db->createCommand()->batchInsert(DishesIngredients::tableName(), ['dish_id','ingredient_id'], $rows)->execute();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Dishes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $rows = [];
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $dishes = Yii::$app->request->post('Dishes');

            $model->type = implode(',',$dishes['type']);
            if($model->save(false)){
                foreach($dishes['type'] as $key => $ingredient_id){
                    $rows[$key]['dish_id'] = $model->id;
                    $rows[$key]['ingredient_id'] = $ingredient_id;
                }
                DishesIngredients::deleteAll(['dish_id' => $id]);
                Yii::$app->db->createCommand()->batchInsert(DishesIngredients::tableName(), ['dish_id','ingredient_id'], $rows)->execute();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Dishes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Dishes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dishes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dishes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
