<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "holiday".
 *
 * @property int $id
 * @property string $name
 * @property string $date
 */
class Holiday extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'holiday';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'date'], 'required'],
            [['date'], 'safe'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'date' => 'Date',
        ];
    }

    public static function getByDate($date) {
        return $holiday = Holiday::find()->where(['date'=>$date])->one();
    }
}
