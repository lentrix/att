<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class IndividualForm extends Model
{
    public $user_id;
    public $from;
    public $to;

    public function rules()
    {
        return [
            [['user_id', 'from', 'to'], 'required'],
            [['user_id'], 'integer'],
            //[['from', 'to'], 'date'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User',
            'from'  => 'Date From',
            'to' => 'Date To',
        ];
    }
}