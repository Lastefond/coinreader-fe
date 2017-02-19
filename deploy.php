<?php
/*
 * This file has been generated automatically.
 * Please change the configuration for correct use deploy.
 */

require 'recipe/yii.php';

// Set configurations
set('repository', 'https://github.com/Lastefond/coinreader-fe.git');
set('writable_dirs', ['runtime', 'web/assets']);

// Configure servers
localServer('production')
    ->env('deploy_path', '/var/www/html/coinreader-fe');
