<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

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
        'css/keyboard.css',
        'css/keyboard-previewkeyset.css',
        'css/keyboard-lastefond.css',
        'css/animations.css',
        'css/sweetalert.css',
        'js/jquery.bxslider/jquery.bxslider.css',
        'css/lastefond.css',
    ];
    public $js = [
        ['js/coinHandler.js', 'position' => View::POS_HEAD],
        'js/jquery-latest.min.js',
        'js/jquery-ui.min.js',
        'js/jquery.keyboard.js',
        'js/sweetalert.min.js',
        'js/jquery.fitvids.js',
        'js/jquery.bxslider/jquery.bxslider.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
