<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2016-06-19 10:44
 */

namespace frontend\widgets;


use yii;
use yii\helpers\StringHelper;
use common\models\Menu;

class MenuView extends \yii\base\Widget
{

    public $template = "<ul>{lis}</ul>";

    public $liTemplate = "
<li id='menu-item-{menu_id}'>
    <a href='{url}' class='{current_menu_class}' target='{target}'>{title}</a>
</li>";


    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();
        static $menus = null;
        if( $menus === null ) {
            $menus = Menu::find()
                ->where(['type' => Menu::TYPE_FRONTEND, 'is_display' => Menu::DISPLAY_YES])
                ->orderBy("sort asc,parent_id asc")
                ->all();
        }
        $content = '';
        foreach ($menus as $key => $menu) {
            /** @var $menu Menu */
            if ($menu->parent_id == 0) {
                $url = $menu->getMenuUrl();
                $urlComponents = json_decode($menu->url, true);
                $currentMenuClass = '';
                $parent = Yii::$app->request->get('parent');
                if (!empty($urlComponents['cat'])) {
                    $currentMenuClass = ($urlComponents['cat'] == $parent) ? 'active' : '';
                }
                if (StringHelper::endsWith($url, 'index') == true && yii::$app->getRequest()->getUrl() == '/') {
                    $currentMenuClass = 'active';
                }
                if ($url == yii::$app->getRequest()->getUrl()) {
                    $currentMenuClass = 'active';
                }
                $content .= str_replace([
                    '{menu_id}',
                    '{current_menu_class}',
                    '{url}',
                    '{target}',
                    '{title}'
                ], [
                    $menu->id,
                    $currentMenuClass,
                    $url,
                    $menu->target,
                    $menu->name,
                ], $this->liTemplate);
            }
        }
        return str_replace('{lis}', $content, $this->template);
    }

    /**
     * @param $menus
     * @param $cur_id
     * @return mixed|string
     * @throws yii\base\InvalidConfigException
     */
    private function getSubMenu($menus, $cur_id)
    {
        $content = '';
        foreach ($menus as $key => $menu) {
            /** @var $menu Menu */
            if ($menu['parent_id'] == $cur_id) {
                $url = $menu->getMenuUrl();
                $currentMenuClass = '';
                if ($menu['url'] == Yii::$app->controller->id . '/' . Yii::$app->controller->action->id) {
                    $currentMenuClass = ' current-menu-item ';
                } else {
                    if (yii::$app->request->getPathInfo() == $menu['url']) {
                        $currentMenuClass = ' current-menu-item ';
                    }
                }
                $content .= str_replace([
                    '{menu_id}',
                    '{current_menu_class}',
                    '{url}',
                    '{target}',
                    '{title}'
                ], [$menu['id'], $currentMenuClass, $url, $menu->target, $menu->name], $this->subLitemplate);
            }
        }
        if ($content != '') {
            return str_replace('{lis}', $content, $this->subTemplate);
        } else {
            return '';
        }
    }


}