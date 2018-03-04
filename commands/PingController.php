<?php

namespace app\commands;

use GuzzleHttp\Client;
use Yii;
use yii\console\Controller;

class PingController extends \yii\console\Controller
{
    /**
     * Pings the server to show I'm alive
     */
    public function actionIndex()
    {
        $boxId = Yii::$app->params['coinSender']['boxId'];
        $apiUrl = Yii::$app->params['coinSender']['apiUrl'];

        if (!isset($boxId) || !$apiUrl) {
            throw new \InvalidArgumentException('Box ID and api URL must be set!');
        }

        $client = new Client([
            'base_uri' => $apiUrl
        ]);
        try {
            $client->patch(strtr('boxes/{id}.json', ['{id}' => $boxId]), [
                'json' => [
                    'last_online' => time(),
                ],
            ]);
        } catch (\Exception $e) {
            \Yii::$app->errorHandler->logException($e);
            echo 'No ping :(' . PHP_EOL;
            return Controller::EXIT_CODE_ERROR;
        }

        echo 'PONG!' . PHP_EOL;
        return Controller::EXIT_CODE_NORMAL;
    }
}
