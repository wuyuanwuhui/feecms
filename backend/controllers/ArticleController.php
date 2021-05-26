<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2016-03-23 15:13
 */

namespace backend\controllers;

use Yii;
use common\services\CategoryServiceInterface;
use common\models\Article;
use common\services\ArticleServiceInterface;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\ViewAction;
use backend\actions\DeleteAction;
use backend\actions\SortAction;
use yii\helpers\ArrayHelper;

/**
 * Article management
 * - data:
 *          table article article_content
 * - description:
 *          article management
 *
 * Class ArticleController
 * @package backend\controllers
 */
class ArticleController extends \yii\web\Controller
{

    /**
     * @auth
     * - item group=内容 category=文章 description-get=列表 sort=300 method=get
     * - item group=内容 category=文章 description-get=查看 sort=301 method=get  
     * - item group=内容 category=文章 description=创建 sort-get=302 sort-post=303 method=get,post  
     * - item group=内容 category=文章 description=修改 sort=304 sort-post=305 method=get,post  
     * - item group=内容 category=文章 description-post=删除 sort=306 method=post  
     * - item group=内容 category=文章 description-post=排序 sort=307 method=post  
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actions()
    {
        /** @var ArticleServiceInterface $service */
        $service = Yii::$app->get(ArticleServiceInterface::ServiceName);
        /** @var CategoryServiceInterface $categoryService */
        $categoryService = Yii::$app->get(CategoryServiceInterface::ServiceName);

        return [
            'index' => [
                'class' => IndexAction::className(),
                'data' => function($query) use($service, $categoryService){
                    $result = $service->getList($query, ['type'=>Article::ARTICLE]);
                    return [
                        'dataProvider' => $result['dataProvider'],
                        'searchModel' => $result['searchModel'],
                        'categories' => ArrayHelper::getColumn($categoryService->getLevelCategoriesWithPrefixLevelCharacters(), "prefix_level_name"),
                        'frontendURLManager' => $service->getFrontendURLManager()
                    ];
                }
            ],
            'view-layer' => [
                'class' => ViewAction::className(),
                'data' => function($id) use($service){
                    return [
                        'model' => $service->getDetail($id),
                    ];
                },
            ],
            'create' => [
                'class' => CreateAction::className(),
                'doCreate' => function($postData) use($service){
                    return $service->create($postData, ['scenario'=>ArticleServiceInterface::ScenarioArticle]);
                },
                'data' => function($createResultModel,  CreateAction $createAction) use($service, $categoryService){
                    return [
                        'model' => $createResultModel === null ? $service->newModel(['scenario'=>ArticleServiceInterface::ScenarioArticle]) : $createResultModel['articleModel'],
                        'contentModel' => $createResultModel === null ? $service->newArticleContentModel() : $createResultModel['articleContentModel'] ,
                        'categories' => ArrayHelper::getColumn($categoryService->getLevelCategoriesWithPrefixLevelCharacters(), "prefix_level_name"),
                    ];
                },
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'doUpdate' => function($id, $postData) use($service){
                    return $service->update($id, $postData, ['scenario'=>ArticleServiceInterface::ScenarioArticle]);
                },
                'data' => function($id, $updateResultModel) use($service, $categoryService){
                    return [
                        'model' => $updateResultModel === null ? $service->getDetail($id, ['scenario'=>ArticleServiceInterface::ScenarioArticle]) : $updateResultModel['articleModel'],
                        'contentModel' => $updateResultModel === null ? $service->getArticleContentDetail($id) : $updateResultModel['articleContentModel'],
                        'categories' => ArrayHelper::getColumn($categoryService->getLevelCategoriesWithPrefixLevelCharacters(), "prefix_level_name"),
                    ];
                }
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'doDelete' => function($id) use($service){
                    return $service->delete($id);
                },
            ],
            'sort' => [
                'class' => SortAction::className(),
                'doSort' => function($id, $sort) use($service){
                    return $service->sort($id, $sort, ['scenario'=>ArticleServiceInterface::ScenarioArticle]);
                }
            ],
        ];
    }

    public $enableCsrfValidation = false;

    public function actionTest()
    {
        if (Yii::$app->getRequest()->isPost) {
            print_r($_POST);
            print_r($_FILES);

            die;
        }


        return $this->renderPartial('test');
    }

    public function actionUpload()
    {
        if (Yii::$app->getRequest()->isPost) {
            print_r($_POST);
            print_r($_FILES);

            if ($_FILES['ImgeData']['tmp_name']) {
                move_uploaded_file($_FILES['ImgeData']['tmp_name'], dirname(__FILE__) . '/11111.webp');
            }

            die;
        }
        exit('nothing');
    }

    // http://www.manongjc.com/article/26865.html
    function toWebp($base_img)
    {
        $base_img = str_replace('data:image/webp;base64,', '', $base_img);

        //  设置文件路径和文件前缀名称

        $path = "./";

        $prefix='nx_';

        $output_file = $prefix.time().rand(100,999).'.jpg';

        $path = $path.$output_file;

        //  创建将数据流文件写入我们创建的文件内容中

        $ifp = fopen( $path, "wb" );

        fwrite( $ifp, base64_decode( $base_img) );

        fclose( $ifp );

        // 第二种方式

        // file_put_contents($path, base64_decode($base_img));

        // 输出文件

        print_r($output_file);
    }

}