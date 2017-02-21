<?php

namespace app\controllers;

use app\models\Donator;
use Yii;
use yii\rest\Controller;

class CoinController extends Controller
{
    public function actionCreate()
    {
        $donator = new Donator();

        if ($donator->load(Yii::$app->request->post())) {
            $donator->save();
        }

        return $donator;
    }
}
