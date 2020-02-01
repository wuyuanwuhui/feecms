<?php
return [
    'name' => 'Feehi CMS',
    'version' => '2.0.8.1',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'db' => [//数据库配置，这里的配置可能会被conf/db.local main-local.php配置覆盖
            'class' => yii\db\Connection::className(),
            'dsn' => 'mysql:host=localhost;dbname=feehi',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'cdn' => [//支持使用 七牛 腾讯云 阿里云 网易云 具体配置请参见 http://doc.feehi.com/cdn.html
            'class' => feehi\cdn\DummyTarget::className(),//不使用cdn
        ],
        'cache' => [//缓存组件 具体配置请参考 http://doc.feehi.com/configs.html
            'class' => yii\caching\DummyCache::className(),//不使用缓存
        ],
        'formatter' => [//格式显示配置
            'dateFormat' => 'php:Y-m-d H:i',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CHY',
            'nullDisplay' => '-',
        ],
        'mailer' => [//邮箱发件人配置，会被main-local.php以及后台管理页面中的smtp配置覆盖
            'class' => yii\swiftmailer\Mailer::className(),
            'viewPath' => '@common/mail',
            /*特别注意：如果useFileTransport为true，并不会真发邮件，只会把邮件写入runtime目录，很有可能造成您的磁盘使用飙升。
                        如果为false，当您配置的smtp地址不存在或错误，页面会一直等到连接邮件服务器超时才会输出页面。*/
            'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.feehi.com',  //每种邮箱的host配置不一样
                'username' => 'admin@feehi.com',
                'password' => 'password',
                'port' => '586',
                'encryption' => 'tls',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['admin@feehi.com' => 'Feehi CMS robot ']
            ],
        ],
        'feehi' => [
            'class' => feehi\components\Feehi::className(),
        ],
        \common\services\MenuServiceInterface::MenuService => [
            'class' => \common\services\MenuService::className(),
        ],
        \common\services\FriendlyLinkServiceInterface::ServiceName => [
            'class' => \common\services\FriendlyLinkService::className(),
        ],
        \common\services\CommentServiceInterface::ServiceName => [
            'class' => \common\services\CommentService::className(),
        ],
        \common\services\LogServiceInterface::ServiceName => [
            'class' => \common\services\LogService::className(),
        ],
        \common\services\SettingServiceInterface::ServiceName => [
            'class' => \common\services\SettingService::className(),
        ],
        \common\services\AdServiceInterface::ServiceName => [
            'class' => \common\services\AdService::className(),
        ],
        \common\services\AdminUserServiceInterface::ServiceName => [
            'class' => \common\services\AdminUserService::className(),
        ],
        \common\services\UserServiceInterface::ServiceName => [
            'class' => \common\services\UserService::className(),
        ],
        \common\services\RBACServiceInterface::ServiceName => [
            'class' =>\common\services\RBACService::className(),
        ],
        \common\services\CategoryServiceInterface::ServiceName => [
            'class' => \common\services\CategoryService::className(),
        ],
        \common\services\ArticleServiceInterface::ServiceName => [
            'class' => \common\services\ArticleService::className(),
        ],
        \common\services\BannerServiceInterface::ServiceName => [
            'class' => \common\services\BannerService::className(),
        ],
        'authManager' => [
            'class' => yii\rbac\DbManager::className(),
        ],
        'assetManager' => [
            'linkAssets' => false,
            'bundles' => [
                yii\widgets\ActiveFormAsset::className() => [
                    'js' => [
                        'a' => 'yii.activeForm.js'
                    ],
                ],
                yii\bootstrap\BootstrapAsset::className() => [
                    'css' => [],
                    'sourcePath' => null,
                ],
                yii\captcha\CaptchaAsset::className() => [
                    'js' => [
                        'a' => 'yii.captcha.js'
                    ],
                ],
                yii\grid\GridViewAsset::className() => [
                    'js' => [
                        'a' => 'yii.gridView.js'
                    ],
                ],
                yii\web\JqueryAsset::className() => [
                    'js' => [
                        'a' => 'jquery.js'
                    ],
                ],
                yii\widgets\PjaxAsset::className() => [
                    'js' => [
                        'a' => 'jquery.pjax.js'
                    ],
                ],
                yii\web\YiiAsset::className() => [
                    'js' => [
                        'a' => 'yii.js'
                    ],
                ],
                yii\validators\ValidationAsset::className() => [
                    'js' => [
                        'a' => 'yii.validation.js'
                    ],
                ],
            ],
        ],
    ],
];