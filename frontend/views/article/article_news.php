<?php
/**
 * @var $this yii\web\View
 * @var $model
 */

?>

<body id="main">
<div class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="images/banner/new_banner01.jpg" alt="" />
        </div>
    </div>
</div>
<section class="wrapper" id="news-detail">
    <div class="container">
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
