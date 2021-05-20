<?php

/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $type string
 * @var $category string
 * @var $indexBanners []
 * @var $recommendArticles
 * @var $news
 */

$bannerCounts = count($indexBanners);

?>


<body id="main">
<div id="main-banner" class="carousel slide" data-ride="carousel">
    <!---->

    <ul class="carousel-indicators">
        <?php
            for($i=0; $i<$bannerCounts; $i++) {
                $active = ($i == 0) ? "active" : "";
        ?>
    <li data-target="#main-banner" data-slide-to="<?=$i?>" class="<?=$active?>"></li>
        <?php } ?>

    </ul>
    <!-- -->
    <div class="carousel-inner">
        <?php foreach ($indexBanners as $key => $val ) { ?>

        <div class="carousel-item <?=($key === 0) ? 'active' : ''?>">
            <a href="<?=$val['link']?>">
                <img src="<?=$val['img']?>" title="<?=$val['tips'] ?? ''?>" />
            </a>
        </div>
        <?php } ?>
    </div>
    <!--  -->
    <a class="carousel-control-prev" href="#main-banner" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </a>
    <a class="carousel-control-next" href="#main-banner" data-slide="next">
        <span class="carousel-control-next-icon"></span>
    </a>
</div>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 content-floor1">
                <h4>
                    <span class="left-icon"></span>
                    <span class="mtitle">推荐</span> 游戏
                    <span class="subtitle">RECOMMEND</span>
                    <a class="more" href="/index.php?r=article&cat=games"
                    >更多<i class="fa fa-chevron-circle-right"></i
                        ></a>
                </h4>
                <div class="recommend-games">
                    <?php foreach ($recommendArticles as $key => $val ) { ?>

                        <div>
                            <a class="games__item  effect" href="/index.php?r=article/view&id=<?=$val['id']?>">
                                <img  src="<?=$val['game_icon']?>"  title="<?=$val['title']?>"  class="games__photo"  />
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="col-xs-12 content-floor2">
                <h4>
                    <span class="left-icon"></span>
                    <span class="mtitle">新闻</span> 动态
                    <span class="subtitle">NEWS</span>
                    <a class="more" href="/index.php?r=article&cat=news"
                    >更多<i class="fa fa-chevron-circle-right"></i
                        ></a>
                </h4>

                <ul class="news">
                    <?php foreach ($news as $key => $val ) { ?>
                    <li>
                        <a href="/index.php?r=article/view&id=<?=$val['id']?>">
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
