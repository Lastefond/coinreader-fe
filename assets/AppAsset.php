<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'demo/css/lastefond.css',
        'demo/css/keyboard.css',
        'demo/css/keyboard-previewkeyset.css',
        'demo/css/keyboard-lastefond.css',
        'demo/css/animations.css',
    ];
    public $js = [
        'demo/js/coinHandler.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
