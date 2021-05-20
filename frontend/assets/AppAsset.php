<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2017-03-15 21:16
 */

namespace frontend\assets;

class AppAsset extends \yii\web\AssetBundle
{

    public $css = [
//        'static/css/style.css',
//        'static/plugins/toastr/toastr.min.css',

        'static/css/tether.min.css',
        'static/css/fonts.css',
        'static/css/style_new.css',
        'static/css/bootstrap.min.css',
        'static/css/font-awesome.min.css',

    ];

    public $js = [
//        'static/js/index.js',
//        'static/plugins/toastr/toastr.min.js',

        'static/js/tether.js',
        'static/js/jquery-3.1.1.min.js',
        'static/js/bootstrap.js',
        'static/js/common.js',

    ];

//    public $depends = [
//        'yii\web\YiiAsset',
//    ];

}
