<?php

namespace app\models;

use Yii;
use app\models\UserInfo;

/**
 * This is the model class for table "check_in_out".
 *
 * @property integer $user_id
 * @property string $check_time
 * @property string $check_type
 * @property string $sensor_id
 */
class CheckInOut extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'check_in_out';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'check_time', 'active'], 'required'],
            [['id', 'user_id'], 'integer'],
            [['check_time'], 'safe'],
            [['check_type'], 'string', 'max' => 1],
            [['sensor_id'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'check_time' => 'Check Time',
            'check_type' => 'Check Type',
            'sensor_id' => 'Sensor ID',
        ];
    }

    public function getUserInfo() {
        return $this->hasOne(UserInfo::className(), ['user_id'=>'user_id']);
    }

    public function switchType() {
        if($this->check_type=="I") {
            $this->check_type="O";
        }else {
            $this->check_type="I";
        }
        $this->save();
    }

    public static function getLastCheck() {
        return self::find()
                ->orderBy('check_time DESC')
                ->limit(1)
                ->one();
    }
}
