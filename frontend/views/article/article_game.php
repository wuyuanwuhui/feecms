<?php

/**
 * @var $this yii\web\View
 * @var $model
 */

$this->title = $model->title;
$categoryName = $model->category ? $model->category->name : Yii::t('app', 'UnClassified');
$categoryAlias = $model->category ? $model->category->alias : Yii::t('app', 'UnClassified');

?>

<body id="games">
<div class="bg-game" id="detailPage">
    <div class="wrapper wrapper--narrow container" style="padding-top: 1rem">
        <header class="detail-head">
            <ul id="game_menu">
                <li><a href="<?= yii\helpers\Url::to(['article/index', 'cat' => $categoryAlias]) ?>"><?= $categoryName ?></a></li>
                <li> > </li>
                <li><a href=""><?=$model->title?></a></li>
            </ul>
        </header>
        <div class="content-block">
            <div class=" ">
                <div class="row content-head">
                    <div class="col-xs-7 col-md-7 content-head-left clearfix">
                        <div class="game-icon">
                            <img  src="<?=$model->game_icon?>" />
                        </div>
                        <div class="titles">
                            <ul class="titles-details">
                                <li style="margin-bottom: 30px">
                                    <h5 style="font-weight: bold"><?=$model->title?></h5>
                                </li>
                                <li>
                                    <ul class="list-inline safety">
                                        <li><i class="safe"></i></li>
                                        <li>无广告</li>
                                        <li><i class="safe"></i></li>
                                        <li>安全</li>
                                    </ul>
                                </li>
                                <li>
                                    <ul class="list-inline game-details">
                                        <li>大小：<span>待更新</span></li>
                                        <li>版本：<span><?=$model->game_version?></span></li>
                                    </ul>
                                </li>
                                <li>
                                    <ul class="list-inline game-details">
                                        <li>下载次数：<span><?=$model->down_counts?>次</span></li>
                                        <li>更新时间：<span><?=date('Y-m-d', $model->updated_at)?></span></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-5 col-md-5 content-head-right">
                        <div>
                            <div class="qr-code"></div>
                            <input type="hidden" value="" id="fileId" />
                            <a href="" id="download" style="display: none">
                                <span>click</span></a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-block detail-content-floor1">
            <div><h5 class="images-title"><?=$model->title?></h5></div>
            <br />
            <div
                    class="slider-content"
                    style="width: 100%; position: relative; overflow: hidden"
            >
            <ul class="slider" id="slider" style="position: relative; left: 0px">
                    <li>
                        <div class="game-video">
                        </div>
                    </li>
                    <?php foreach ($model->images as $key => $val ) { ?>

                        <li class="spic">
                            <img class="spic_img"
                                 src="<?=$val?>"
                            />
                        </li>

                    <?php } ?>

                </ul>
            </div>
            <a class="slider-control control-prev" data-slide="prev">
                <i class="fa fa-angle-left fa-3x"></i>
            </a>
            <a class="slider-control control-next" data-slide="next">
                <i class="fa fa-angle-right fa-3x"></i>
            </a>
        </div>

        </div>
        <div class="content-block detail-content-floor2">
            <div class="game-introduction">
                <p>
                    <?=$model->articleContent->content?>
                </p>
                <p><br /></p>
            </div>
        </div>
    </div>
</div>
</body>

<?php
use yii\helpers\Url;

$this->registerJsFile(Url::base().'/static/js/website/gamesdetail.js', ['depends'=>['frontend\assets\AppNewAsset']]);

?>