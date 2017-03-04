<?php

// comment out the following two lines when deployed to production
#defined('YII_DEBUG') or define('YII_DEBUG', true);
#defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
if (file_exists(__DIR__ . '/web-local.php') || is_link(__DIR__ . '/web-local.php')) {
    $config = \yii\helpers\ArrayHelper::merge($params, require(__DIR__ . '/web-local.php'));
}

(new yii\web\Application($config))->run();
