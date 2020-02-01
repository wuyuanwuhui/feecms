<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2017-12-03 21:58
 */

namespace backend\controllers;

use Yii;
use backend\actions\ViewAction;
use common\services\BannerServiceInterface;
use backend\actions\IndexAction;
use backend\actions\SortAction;
use backend\actions\CreateAction;
use backend\actions\DeleteAction;
use backend\actions\UpdateAction;

class BannerController extends \yii\web\Controller
{
    /**
     * @auth
     * - item group=运营管理 category=banner类型 description-get=列表 sort=600 method=get
     * - item group=运营管理 category=banner类型 description=创建 sort-get=601 sort-post=602 method=get,post  
     * - item group=运营管理 category=banner类型 description=修改 sort-get=603 sort-post=604 method=get,post  
     * - item group=运营管理 category=banner类型 description-post=删除 sort=605 method=post  
     * - item group=运营管理 category=banner description-get=列表 sort=610 method=get  
     * - item group=运营管理 category=banner description=创建 sort-get=611 sort-post=612 method=get,post  
     * - item group=运营管理 category=banner description-get=查看 sort=613 method=get
     * - item group=运营管理 category=banner description=修改 sort-get=614 sort-post=615 method=get,post  
     * - item group=运营管理 category=banner description-post=排序 sort=616 method=post  
     * - item group=运营管理 category=banner description=删除 sort=617 method=post  
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actions()
    {
        /** @var BannerServiceInterface $service */
        $service = Yii::$app->get(BannerServiceInterface::ServiceName);
        return [
            'index' => [
                'class' => IndexAction::className(),
                'data' => function() use($service){
                    $result = $service->getBannerTypeList();
                    return [
                        'dataProvider' => $result['dataProvider'],
                    ];
                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'create' => function($postData) use($service){
                    return $service->createBannerType($postData);
                },
                'data' => function($createResultModel) use($service){
                    $model = $createResultModel === null ? $service->getNewBannerTypeModel() : $createResultModel;
                    return [
                        'model' => $model,
                    ];
                }
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'update' => function($id, $postData) use($service){
                    return $service->updateBannerType($id, $postData);
                },
                'data' => function($id, $updateResultModel) use($service){
                    $model = $updateResultModel === null ? $service->getBannerTypeDetail($id) : $updateResultModel;
                    return [
                        'model' => $model,
                    ];
                },
                'successRedirect' => ["banner/index"]
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'delete' => function($id) use($service){
                    return $service->deleteBannerType($id);
                },
            ],

            'banners' => [
                'primaryKeyIdentity' => 'id',
                'class' => IndexAction::className(),
                'data' => function($id, $query) use($service){
                    $result = $service->getBannerList($query);
                    return [
                        'dataProvider' => $result['dataProvider'],
                        'bannerType' => $service->getBannerTypeDetail($id),
                    ];
                }
            ],
            'banner-create' => [
                'primaryKeyIdentity' => 'id',
                'class' => CreateAction::className(),
                'create' => function($id, $postData) use($service){
                    return $service->createBanner($id, $postData);
                },
                'data' => function($id, $createResultModel) use($service){
                    $model = $createResultModel === null ? $service->getNewBannerModel() : $createResultModel;
                    return [
                        'model' => $model,
                        'bannerType' => $service->getBannerTypeDetail($id),
                    ];
                },
                'successRedirect' => ['banner/banners', 'id'=>Yii::$app->getRequest()->get('id'), 'sign'=>Yii::$app->getRequest()->get('sign')]
            ],
            'banner-view-layer' => [
                'primaryKeyIdentity' => ['id', 'sign'],
                'class' => ViewAction::className(),
                'data' => function($id, $sign) use($service){
                    return [
                        'model' => $service->getBannerDetail($id, $sign),
                    ];
                },
                'viewFile' => 'view',
            ],
            'banner-update' => [
                'primaryKeyIdentity' => ['id', 'sign'],
                'class' => UpdateAction::className(),
                'update' => function($id, $sign, $postData) use($service){
                     return $service->updateBanner($id, $sign, $postData);
                },
                'data' => function($id, $sign, $updateResultModel) use($service) {
                    $model = $updateResultModel === null ? $service->getBannerDetail($id, $sign) : $updateResultModel;
                    return [
                        'model' => $model,
                        'bannerType' => $service->getBannerTypeDetail($id),
                    ];
                },
                'successRedirect' => ['banner/banners', 'id'=>Yii::$app->getRequest()->get('id'), 'sign'=>Yii::$app->getRequest()->get('sign')]
            ],
            'banner-sort' => [
                'class' => SortAction::className(),
                'sort' => function($param, $value) use($service){
                    return $service->sortBanner($param['id'], $param['sign'], $value);
                },
            ],
            'banner-delete' => [
                'primaryKeyIdentity' => "sign",
                'class' => DeleteAction::className(),
                'delete' => function($sign) use($service){
                    $id = Yii::$app->getRequest()->get("id", null);
                    return $service->deleteBanner($id, $sign);
                },
            ],
        ];
    }
}