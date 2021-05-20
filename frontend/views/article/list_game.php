<?php
/**
 * @var $this yii\web\View
 * @var $descendants
 * @var $dataProvider
 */
use common\models\Category;
$games_category = Category::find()->where(['parent_id' => 1])->asArray()->all();
// var_dump($dataProvider->getPagination());
// die;
?>

<body id="games">
<div class="bg-game">
    <div class="wrapper wrapper--narrow container">
        <section id="kontent">
            <header class="tabs clearfix">
                <ul id="toggle-tab">

                    <?php foreach ($games_category as $key => $val ) {
                        $cat = Yii::$app->request->get('cat');
                        $active = ($cat == $val['alias']) ? 'active' : '';
                        if ($cat == 'games' && $key == 0) {
                            $active = 'active';
                        }
                    ?>

                        <li class="<?=$active?>">
                            <a href="/index.php?r=article/index&pid=<?=$val['parent_id']?>&parent=games&cat=<?=$val['alias']?>">
                                <p><?=$val['name']?></p>
                                <span><?=!empty($val['name_en']) ? $val['name_en'] : $val['name']?></span>
                            </a>
                        </li>

                    <?php } ?>

                </ul>
            </header>

            <ul class="gameList">
                <?php
                $i = 0;
                foreach($dataProvider->getModels() as $model) {
                    static $i;
                    $i++;
                ?>
                    <li>
                        <a href="/index.php?r=article/view&parent=<?=Yii::$app->request->get('cat');?>&id=<?=$model->id?>" class="game_banner" title="密室逃脱13">
                            <div class="game_tlo clearfix">
                                <h2><?=$model->title?></h2>
                                <img
                                        src="<?=$model->thumb?>"
                                        class="game_tlo_img"
                                />
                            </div>
                            <?php if($i == 1) { ?> <div class="games__new"></div> <?php } ?>
                        </a>

                        <div class="clear"></div>
                    </li>
                <?php } ?>

            </ul>


        </section>
    </div>
</div>

</body>