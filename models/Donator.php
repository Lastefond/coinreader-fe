<?php

namespace app\models;

use SidekiqJob\Client;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for donators.
 *
 * @property string $name
 * @property array $coins
 */
class Donator extends Model
{
    const COIN_VALUES = [200,100,50,20,10,5];

    public $name;
    public $coins;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['coins'], 'required'],
            [['coins'], 'each', 'rule' => ['integer']],
            [['coins'], 'each', 'rule' => ['in', 'range' => self::COIN_VALUES]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return ['name', 'coins'];
    }

    /**
     * Pushes job to sidekiq
     * @return bool
     */
    public function push()
    {
        if (!isset(Yii::$app->params['coinSender']['boxId'])) {
            throw new \InvalidArgumentException('box_id is missing');
        }
        if (!$this->validate()) {
            return false;
        }

        array_walk($this->coins, function (&$coin) {
            $coin = (int) $coin;
        });

        /** @var Client $sidekiq */
        $sidekiq = Yii::$app->sidekiq;
        return !!$sidekiq->push(Yii::$app->params['coinSender']['worker'], [
            ArrayHelper::merge([
                'box_id' => Yii::$app->params['coinSender']['boxId'],
                'timestamp' => time(),
            ], $this->getAttributes())
        ]);
    }
}
