<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;

class CoinController extends ActiveController
{
    public $modelClass = 'app\models\Donator';
}
