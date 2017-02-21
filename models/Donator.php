<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;
use yii\redis\ActiveRecord;

/**
 * This is the model class for donators.
 *
 * @property integer $id
 * @property string $name
 * @property array $coins
 * @property integer $timestamp
 */
class Donator extends ActiveRecord
{
    const COIN_VALUES = [200,100,50,20,10,5];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'timestamp',
                'updatedAtAttribute' => false,
                'value' => time(),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['coins'], 'each', 'rule' => ['integer']],
            [['coins'], 'each', 'rule' => ['in', 'range' => self::COIN_VALUES]],
            [['timestamp'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return ['id', 'name', 'coins', 'timestamp'];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->coins = Json::encode($this->coins);
        return true;
    }
}
