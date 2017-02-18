<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coin_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $coin_value
 * @property User $user
 */
class CoinLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coin_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'coin_value'], 'required'],
            [['user_id', 'coin_value'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'coin_value' => 'Coin Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
