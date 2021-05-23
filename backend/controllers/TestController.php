<?php

namespace backend\controllers;

use yii;
use backend\actions\IndexAction;
use common\models\Article;
use common\services\ArticleServiceInterface;
use common\services\CategoryServiceInterface;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

class TestController extends \yii\web\Controller
{
    public function actionIndex()
    {
        // $query = Article::find()->asArray()->limit(2);
        // $query = Article::find()->asArray()->with('category')->limit(2);
        $query = Article::find()->where(['type' => 0])->limit(2)->all();

//        $dataProvider = new ActiveDataProvider([
//            'query' => $query
//        ]);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query
        ]);

//        $dataProvider = new SqlDataProvider([
//
//        ]);

        foreach ($dataProvider->getModels() as $model) {
            print_r($model->title);
            print_r($model->category);

            die;
        }
        print_r($dataProvider->getModels());

        exit;
        return $this->render('index');
    }

    public function actionQuery()
    {
        /** @var ArticleServiceInterface $service */
        $service = Yii::$app->get(ArticleServiceInterface::ServiceName);
        /** @var CategoryServiceInterface $categoryService */
        $categoryService = Yii::$app->get(CategoryServiceInterface::ServiceName);

        $class = IndexAction::className();
        $data = function($query) use($service, $categoryService) {

        $result = $service->getList($query, ['type'=>Article::ARTICLE]);
        $all = [
                'dataProvider' => $result['dataProvider'],
                'searchModel' => $result['searchModel'],
                'categories' => ArrayHelper::getColumn($categoryService->getLevelCategoriesWithPrefixLevelCharacters(), "prefix_level_name"),
                'frontendURLManager' => $service->getFrontendURLManager()
            ];
        };

    }

    public function actionCs()
    {

    }



}
