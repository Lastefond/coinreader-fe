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
        'css/lastefond.css',
        'css/keyboard.css',
        'css/keyboard-previewkeyset.css',
        'css/keyboard-lastefond.css',
        'css/animations.css',
    ];
    public $js = [
        'js/coinHandler.js',
        'js/jquery-latest.js',
        'js/jquery-ui.js',
        'js/jquery.keyboard.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
