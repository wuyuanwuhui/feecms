<?php
/**
 * @var $this yii\web\View
 * @var $model
 */

?>

<body id="games">
<div class="bg-game" id="detailPage">
    <div class="wrapper wrapper--narrow container" style="padding-top: 1rem">
        <header class="detail-head">
            <ul id="game_menu">
                <li><a href="/games?dictionaryId=8">独立游戏</a></li>
                <li>></li>
                <li><a href="">无人星空</a></li>
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
                <ul class="slider" style="position: relative; left: 0px">
                    <li>
                        <div class="game-video">
                        </div>
                    </li>
                    <?php foreach ($model->images as $key => $val ) { ?>

                        <li>
                            <img
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
        <div class="content-block detail-content-floor2">
            <div class="game-introduction">
                <p>
                    探索宇宙，几代人奋斗的目标！<br />征服星球，现在你就可以做到！<br />《无人星空》，一款高度自由的沙盒射击游戏，享受第一人称视角的真实体验，穿梭于各种奇异的星球之间，四处寻找奇特而稀有的外星资源，消灭所有的星球上的生物，让每个星球上都留下你的传说，拿起武器，操控飞船，开始一趟停不下来了的征服之旅。<br />▲人气端游《Morphite》国服代理移植，贴近端游操作手感，让经典再次展现<br />▲丰富多样的升级拓展功能，拿起你独一无二的武器，穿上量身定制的战甲，驾驶着最炫酷的飞船，开启不一样的星际旅程<br />▲深度定制的单人故事情节，超多的星球与星系，丰富的内容，让你没有停下来的理由&nbsp;&nbsp;<br />
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