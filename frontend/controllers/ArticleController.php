<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2016-04-02 22:48
 */

namespace frontend\controllers;

use common\helpers\StringHelper;
use Yii;
use frontend\controllers\helpers\Helper;
use common\services\CommentServiceInterface;
use common\services\AdServiceInterface;
use common\services\ArticleServiceInterface;
use common\libs\Constants;
use frontend\models\form\ArticlePasswordForm;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use common\models\Article;
use common\models\Category;
use common\models\Comment;
use yii\data\ActiveDataProvider;
use common\models\meta\ArticleMetaLike;
use yii\web\NotFoundHttpException;
use yii\filters\HttpCache;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\XmlResponseFormatter;

class ArticleController extends Controller
{


    public function behaviors()
    {
        return [
            [
                'class' =>VerbFilter::className(),
                'actions' => [
                    'comment'  => ['POST'],
                ]
            ],
            [
                'class' => HttpCache::className(),
                'only' => ['view'],
                'lastModified' => function ($action, $params) {
                    $id = Yii::$app->getRequest()->get('id');
                    $model = Article::findOne(['id' => $id, 'type' => Article::ARTICLE, 'status' => Article::ARTICLE_PUBLISHED]);
                    if( $model === null ) throw new NotFoundHttpException(Yii::t("frontend", "Article id {id} is not exists", ['id' => $id]));
                    Article::updateAllCounters(['scan_count' => 1], ['id' => $id]);
                    if($model->visibility == Constants::ARTICLE_VISIBILITY_PUBLIC) return $model->updated_at;
                },
            ],
        ];
    }

    /**
     * article list page
     *
     * @param string $cat category name
     * @return string
     * @throws NotFoundHttpException
     * @throws yii\base\InvalidConfigException
     */
    public function actionIndex($cat = '')
    {
        if ($cat == '') {
            $cat = Yii::$app->getRequest()->getPathInfo();
        }
        $where = ['type' => Article::ARTICLE, 'status' => Article::ARTICLE_PUBLISHED];
        $descendants = '';
        if ($cat != '' && $cat != 'index') {
            if ($cat == Yii::t('app', 'UnClassified')) {
                $where['cid'] = 0;
            } else {
                if (! $category = Category::findOne(['alias' => $cat])) {
                    throw new NotFoundHttpException(Yii::t('frontend', 'None category named {name}', ['name' => $cat]));
                }
                $descendants = $category->getDescendants($category['id']);
                if( empty($descendants) ) {
                    $where['cid'] = $category['id'];
                }else{
                    $cids = ArrayHelper::getColumn($descendants, 'id');
                    $cids[] = $category['id'];
                    $where['cid'] = $cids;
                }
            }
        }
        $query = Article::find()->with('category')->where($where);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5],
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $template = "index";
        isset($category) && $category->template != "" && $template = $category->template;

        // recommendArticles 、 news
        $recommendArticles = $news = [];
        if (in_array($cat, ['index', '', 'news'])) {
            $recommendArticles = Article::find()->select(['id', 'title', 'thumb', 'game_icon'])->where(['flag_recommend' => 1])->asArray()->all();
            $news = Article::find()->select(['id', 'title', 'updated_at'])->where(['cid' => 3])->asArray()->all();
        }

        $data = array_merge([
            'recommendArticles' => $recommendArticles,
            'news' => $news,
            // 'descendants' => $descendants,
            'dataProvider' => $dataProvider,
            'type' => ( !empty($cat) ? Yii::t('frontend', 'Category {cat} articles', ['cat'=>$cat]) : Yii::t('frontend', 'Latest Articles') ),
            'category' => isset($category) ? $category->name : "",
        ], Helper::getCommonInfos());

        return $this->render($template, $data);
    }

    /**
     * article detail page
     *
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView($id)
    {
        /** @var ArticleServiceInterface $articleService */
        $articleService = Yii::$app->get(ArticleServiceInterface::ServiceName);
        $model = $articleService->getArticleById($id);
        /** @var Article $model */
        if( $model === null ) throw new NotFoundHttpException(Yii::t("frontend", "Article id {id} is not exists", ['id' => $id]));
        $prev = Article::find()
            ->where(['cid' => $model->cid, 'type' => Article::ARTICLE, 'status' => Article::ARTICLE_PUBLISHED])
            ->andWhere(['>', 'id', $id])
            ->orderBy("sort asc,id asc")
            ->limit(1)
            ->one();
        $next = Article::find()
            ->where(['cid' => $model->cid, 'type' => Article::ARTICLE, 'status' => Article::ARTICLE_PUBLISHED])
            ->andWhere(['<', 'id', $id])
            ->orderBy("sort asc,id desc")
            ->limit(1)
            ->one();//->createCommand()->getRawSql();
        $commentModel = new Comment();
        $commentList = $commentModel->getCommentByAid($id);
        $recommends = Article::find()
            ->where(['type' => Article::ARTICLE, 'status' => Article::ARTICLE_PUBLISHED])
            ->andWhere(['<>', 'thumb', ''])
            ->orderBy("scan_count")
            ->limit(8)
            ->with('category')
            ->all();
        switch ($model->visibility){
            case Constants::ARTICLE_VISIBILITY_COMMENT://评论可见
                if( Yii::$app->getUser()->getIsGuest() ){
                    $result = Comment::find()->where(['aid'=>$model->id, 'ip'=>Yii::$app->getRequest()->getUserIP()])->one();
                }else{
                    $result = Comment::find()->where(['aid'=>$model->id, 'uid'=>Yii::$app->getUser()->getId()])->one();
                }
                if( $result === null ) {
                    $model->articleContent->content = "<p style='color: red'>" . Yii::t('frontend', "Only commented user can visit this article") . "</p>";
                }
                break;
            case Constants::ARTICLE_VISIBILITY_SECRET://加密文章
                $authorized = Yii::$app->getSession()->get("article_password_" . $model->id, null);
                if( $authorized === null ) $this->redirect(Url::toRoute(['password', 'id'=>$id]));
                break;
            case Constants::ARTICLE_VISIBILITY_LOGIN://登录可见
                if( Yii::$app->getUser()->getIsGuest() ) {
                    $model->articleContent->content = "<p style='color: red'>" . Yii::t('frontend', "Only login user can visit this article") . "</p>";
                }
                break;
        }
        $template = "view";
        isset($model->category) && $model->category->article_template != "" && $template = $model->category->article_template;
        $model->template != "" && $template = $model->template;
        /** @var AdServiceInterface $adService */
        $adService = Yii::$app->get(AdServiceInterface::ServiceName);

        // var_dump($model);; die;

        return $this->render($template, [
            'model' => $model,
            'prev' => $prev,
            'next' => $next,
            'recommends' => $recommends,
            //'commentModel' => $commentModel,
            //'commentList' => $commentList,
            //'rightAd1' => $adService->getAdByName("sidebar_right_1"),
            //'rightAd2' => $adService->getAdByName("sidebar_right_2"),
        ]);
    }

    /**
     * article likes, scan, comment count
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionViewAjax($id)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        /** @var ArticleServiceInterface $articleService */
        $articleService = Yii::$app->get(ArticleServiceInterface::ServiceName);
        $model = $articleService->getArticleById($id);
        if( $model === null ) throw new NotFoundHttpException("None exists article id");
        return [
            'likeCount' => (int)$model->getArticleLikeCount(),
            'scanCount' => $model->scan_count * 100,
            'commentCount' => $model->comment_count,
        ];
    }

    /**
     * comment
     *
     */
    public function actionComment()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        /** @var CommentServiceInterface $service */
        $service = Yii::$app->get(CommentServiceInterface::ServiceName);
        $commentModel = $service->newModel();
        if ($commentModel->load(Yii::$app->getRequest()->post()) && $commentModel->save()) {
            $avatar = 'https://secure.gravatar.com/avatar?s=50';
            if ($commentModel->email != '') {
                $avatar = "https://secure.gravatar.com/avatar/" . md5($commentModel->email) . "?s=50";
            }
            $tips = '';
            if (Yii::$app->feehi->website_comment_need_verify) {
                $tips = "<span class='c-approved'>" . Yii::t('frontend', 'Comment waiting for approved.') . "</span><br />";
            }
            $commentModel->afterFind();
            return "<li class='comment even thread-even depth-1' id='comment-{$commentModel->id}'>
                        <div class='c-avatar'><img src='{$avatar}' class='avatar avatar-108' height='50' width='50'>
                            <div class='c-main' id='div-comment-{$commentModel->id}'><p>{$commentModel->content}</p>
                                {$tips}
                                <div class='c-meta'><span class='c-author'><a href='{$commentModel->website_url}' rel='external nofollow' class='url'>{$commentModel->nickname}</a></span>  (" . Yii::t('frontend', 'a minutes ago') . ")</div>
                            </div>
                        </div>
                    </li>";
        } else {
            $temp = $commentModel->getErrors();
            $str = '';
            foreach ($temp as $v) {
                $str .= $v[0] . "<br>";
            }
            return "<font color='red'>" . $str . "</font>";
        }
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionPassword($id)
    {
        /** @var ArticleServiceInterface $articleService */
        $articleService = Yii::$app->get(ArticleServiceInterface::ServiceName);
        $article = $articleService->getArticleById($id);
        if( $article === null ) {
            throw new NotFoundHttpException(Yii::t("frontend", "Article id {id} is not exists", ['id' => $id]));
        }
        if( $article->visibility !== Constants::ARTICLE_VISIBILITY_SECRET ){
            return $this->redirect(Url::to(['article/view', 'id'=>$id]));
        }
        $model = new ArticlePasswordForm();

        if ($model->load(Yii::$app->getRequest()->post()) && $model->checkPassword($id)) {
            return $this->redirect(Url::toRoute(['view', 'id'=>$id]));
        } else {
            return $this->render("password", [
                'model' => $model,
                'article' => Article::findOne($id),
            ]);
        }
    }

    /**
     * like
     *
     * @return int|string
     */
    public function actionLike()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        $aid = Yii::$app->getRequest()->post("aid");
        $model = new ArticleMetaLike();
        $model->setLike($aid);
        return $model->getLikeCount($aid);

    }

    /**
     * rss
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRss()
    {
        $xml['channel']['title'] = Yii::$app->feehi->website_title;
        $xml['channel']['description'] = Yii::$app->feehi->seo_description;
        $xml['channel']['lin'] = Yii::$app->getUrlManager()->getHostInfo();
        $xml['channel']['generator'] = Yii::$app->getUrlManager()->getHostInfo();
        $models = Article::find()->limit(10)->where(['status'=>Article::ARTICLE_PUBLISHED, 'type'=>Article::ARTICLE])->orderBy('id desc')->all();
        foreach ($models as $model){
            $xml['channel']['item'][] = [
                'title' => $model->title,
                'link' => Url::to(['article/view', 'id'=>$model->id]),
                'pubData' => date('Y-m-d H:i:s', $model->created_at),
                'source' => Yii::$app->feehi->website_title,
                'author' => $model->author_name,
                'description' => $model->summary,
            ];
        }
        Yii::configure(Yii::$app->getResponse(), [
            'formatters' => [
                Response::FORMAT_XML => [
                    'class' => XmlResponseFormatter::className(),
                    'rootTag' => 'rss',
                    'version' => '1.0',
                    'encoding' => 'utf-8'
                ]
            ]
        ]);
        Yii::$app->getResponse()->format = Response::FORMAT_XML;
        return $xml;
    }

    public function actionIndex2()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionDetail()
    {
        return $this->render('detail');
    }

}