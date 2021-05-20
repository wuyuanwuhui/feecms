<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2017-03-15 21:16
 */

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppNewAsset;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use frontend\widgets\MenuView;

AppNewAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php !isset($this->metaTags['keywords']) && $this->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->feehi->seo_keywords], 'keywords');?>
    <?php !isset($this->metaTags['description']) && $this->registerMetaTag(['name' => 'description', 'content' => Yii::$app->feehi->seo_description], 'description');?>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=10,IE=9,IE=8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
</head>
<?php $this->beginBody() ?>
<body class="home blog">

<header class="wrapper header">
    <div class="contaniner" id="naglowek">
        <a href="/" id="logo">Martian</a>
        <nav id="menu">
            <?= MenuView::widget(); ?>
        </nav>
    </div>
</header>


<?= $content ?>

<footer class="footer">
    <div class="container">
        <ul class="row">
            <li class="col-xs-4 offset-1">
                <img
                        src="/images/home/logo2.png "
                        class="logo"
                        alt="Puzzle Craft 2"
                />
            </li>
            <li class="col-xs-7 footer-left">
                <ul>
                    <li>
                        深圳市火星人互动娱乐版权所有&copy;2015-2018中国网络游戏版权保护联盟举报中心
                    </li>
                    <li>
                        COPYRIGHT&copy;2015-2018. ALL RIGHTS
                        RESERVED.粤ICP备16040979号-1
                    </li>

                </ul>
            </li>
        </ul>
    </div>
</footer>

<div class="rollto" style="display: none;">
    <button class="btn btn-inverse" data-type="totop" title="back to top"><i class="fa fa-arrow-up"></i></button>
</div>

</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>