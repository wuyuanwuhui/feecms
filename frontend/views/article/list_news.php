<?php

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $news
 */
?>

<body id="main">
<div class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="/static/images/banner/new_banner01.jpg" alt="" />
        </div>
    </div>
</div>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 content-floor2">
                <h4>
                    <span class="left-icon"></span>
                    <span class="mtitle">新闻</span> 动态
                    <span class="subtitle">NEWS</span>
                </h4>

                <ul class="news news-content">
                    <?php foreach ($news as $key => $val ) { ?>
                        <li>
                            <a href="/index.php?r=article/view&parent=<?=Yii::$app->request->get('cat');?>&id=<?=$val['id']?>">
                                <span><?=$val['title']?></span>
                                <span class="item-time"><?=date('Y-m-d', $val['updated_at'])?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>