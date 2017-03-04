<?php

namespace app\controllers;

use GuzzleHttp\Client;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        die('testing update one more time');
        return $this->render('index', ['donations_sum' => $this->getDonationsSum()]);
    }

    private function getDonationsSum()
    {
        $boxId = Yii::$app->params['coinSender']['boxId'];
        if (!empty($boxId)) {
            return 0;
        }

        $client = new Client();
        try {
            $res = $client->get(strtr('https://lastefond.cariba.ee/boxes/{box_id}.json', ['{box_id}' => $boxId]));
            $box = Json::decode($res->getBody()->getContents(), false);
            if (!!$box->donations_sum) {
                Yii::$app->cache->set('donations_sum', $box->donations_sum, 999999999);
            }
        } catch (\Exception $e) {
            // handle gracefully
            Yii::$app->errorHandler->logException($e);
        }

        return (int) Yii::$app->cache->get('donations_sum');
    }
}
