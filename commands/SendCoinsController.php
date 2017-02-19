<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\CoinLog;
use app\models\User;
use GuzzleHttp\Client;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

/**
 * Class SendCoinsController
 * @package app\commands
 * @author Kaupo Juhkam <kaupo@credy.eu>
 */
class SendCoinsController extends Controller
{
    public function init()
    {
        parent::init();

        if (!Yii::$app->params['coinSender']['boxId']) {
            echo 'Box ID must be set!';
            exit(Controller::EXIT_CODE_ERROR);
        }

        if (!Yii::$app->params['coinSender']['apiUrl']) {
            echo 'API url must be set!';
            exit(Controller::EXIT_CODE_ERROR);
        }
    }
    /**
     * Yes, send coins!
     */
    public function actionIndex()
    {
        echo 'Sending coins..' . "\n";

        $payload = [
            'box_id' => Yii::$app->params['coinSender']['boxId'],
        ];
        $users = User::find()->all();
        /** @var User $user */
        foreach ($users as $user) {
            $coins = [];
            /** @var CoinLog $coin */
            foreach ($user->coins as $coin) {
                $coins[] = $coin->coin_value;
            }

            $payload[] = [
                'name' => $user->getName(),
                'coins' => $coins,
                'timestamp' => $user->created_at,
            ];
        }

        $client = new Client([
            'base_uri' => Yii::$app->params['coinSender']['apiUrl'],
        ]);
        $res = $client->post('donators.json', [
            'json' => $payload,
        ]);

        if ($res->getStatusCode() == 200) {
            echo 'Many coins sent!' . "\n";
            User::deleteAll(['id' => ArrayHelper::map($users, 'primaryKey', 'primaryKey')]);
        }

        echo 'Not so many coins sent :(' . "\n";
    }
}
