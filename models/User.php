<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property CoinLog[] $coins
 * @property int $created_at
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => time(),
                'updatedAtAttribute' => false,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'string', 'max' => 255],
        ];
    }

    public function getName()
    {
        $names = [$this->first_name];
        if (!empty($this->last_name)) {
            $names[] = $this->last_name;
        }
        return join(' ', $names);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoins()
    {
        return $this->hasMany(CoinLog::className(), ['user_id' => 'id'])->inverseOf('user');
    }
}
