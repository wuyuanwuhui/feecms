<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2020-01-30 14:40
 */

namespace common\services;


use backend\models\search\ArticleSearch;
use common\models\Article;

class ArticleService extends Service implements ArticleServiceInterface
{

    public function getSearchModel(array $query, array $options = [])
    {
        return new ArticleSearch();
    }

    public function getModel($id, array $options = [])
    {
        $model = Article::findOne($id);
        if( isset( $options['scenario']) ){
            $model->setScenario( $options['scenario'] );
        }
        return $model;
    }

    public function getNewModel(array $options = [])
    {
        $type = Article::ARTICLE;
        isset($options['scenario']) && $type = $options['scenario'];
        $model = new Article(['scenario' => $type]);
        $model->loadDefaultValues();
        return $model;
    }
}