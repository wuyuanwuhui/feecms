<?php

/**
 * @var $this yii\web\View
 * @var $model
 */

$this->title = $model->title;
$categoryName = $model->category ? $model->category->name : Yii::t('app', 'UnClassified');
$categoryAlias = $model->category ? $model->category->alias : Yii::t('app', 'UnClassified');

?>

<body id="main">
<div class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="/static/images/banner/new_banner01.jpg" alt="" />
        </div>
    </div>
</div>
<section class="wrapper" id="news-detail">

    <div class="container">
        <header class="detail-head">
            <ul id="game_menu">
                <li><a href="<?= yii\helpers\Url::to(['article/index', 'cat' => $categoryAlias]) ?>"><?= $categoryName ?></a></li>
                <li> > </li>
                <li><a href=""><?=$model->title?></a></li>
            </ul>
        </header>
        <div class="news-head">
            <h3 class="news-title">
                <?=$model->title?>
            </h3>
            <p>发布时间： <span class="news-date"><?=date('Y-m-d', $model->updated_at)?></span></p>
        </div>

        <article class="news-content">
            <p><?=$model->articleContent->content?></p>
            <p></p>
        </article>
        <a class="back" href="javascript:;" onClick="javascript:history.back(-1)">
            返回 <i class="fa fa-chevron-circle-right"></i>
        </a>
    </div>
</section>
</body>
