<?php

namespace app\controllers;

use app\models\CoinLog;
use app\models\User;
use Yii;
use yii\rest\Controller;

class CoinController extends Controller
{
    public function actionCreate()
    {
        $user = new User();
        $data = Yii::$app->request->post();

        if (!isset($data['Coin'])) {
            Yii::$app->response->statusCode = 202;
            return ['status' => 'no_coins', 'message' => 'Münte ei sisestatud. Masin on nüüd kurb :('];
        }

        if ($user->load($data) && $user->save()) {
            $coinLogs = [];
            foreach ($data['Coin'] as $coin) {
                $coinLogs[] = new CoinLog();
            }

            if (CoinLog::loadMultiple($coinLogs, $data, 'Coin')) {
                /** @var CoinLog $coinLog */
                foreach ($coinLogs as $coinLog) {
                    $coinLog->link('user', $user);
                }
            }
        }

        return $user;
    }
}
