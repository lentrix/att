<?php

namespace app\models;

use Yii;
use app\models\CheckInOut;

/**
 * This is the model class for table "user_info".
 *
 * @property integer $user_id
 * @property string $name
 * @property string $gender
 * @property string $title
 * @property string $birth_date
 * @property string $hire_day
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $phone
 * @property string $mobile
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','lname'], 'required'],
            [['user_id'], 'integer'],
            [['birth_date', 'hire_day'], 'safe'],
            [['lname','fname'], 'string', 'max' => 40],
            [['gender'], 'string', 'max' => 8],
            [['title', 'phone', 'mobile'], 'string', 'max' => 20],
            [['street'], 'string', 'max' => 80],
            [['city', 'state'], 'string', 'max' => 2],
            [['zip'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'lname' => 'Last Name',
            'fname' => 'First Name',
            'gender' => 'Gender',
            'title' => 'Title',
            'birth_date' => 'Birth Date',
            'hire_day' => 'Hire Day',
            'street' => 'Street',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
        ];
    }

    public function getCheckInOut() {
        return $this->hasMany(CheckInOut::className(),['user_id'=>'user_id']);
    }

    public function getFormalName() {
        return "$this->title $this->lname, $this->fname";
    }

    public function getPersonalName() {
        return "$this->title $this->fname $this->lname";
    }

    public function getFullName() {
        return "$this->lname, $this->fname";
    }

    public function getLogCount() {
        return count($this->checkInOut);
    }

    public function getDayReport($date) {
        $from = $date . " 0:0:0";
        $to = $date . " 23:59:59";

        $ins = CheckInOut::find()
            ->where(['user_id'=>$this->user_id])
            ->andWhere(['check_type'=>'I'])
            ->andWhere(['between', 'check_time', $from, $to])
            ->andWhere(['active'=>1])
            ->orderBy('check_time')
            ->all();
        
        $outs = CheckInOut::find()
            ->where(['user_id'=>$this->user_id])
            ->andWhere(['check_type'=>'O'])
            ->andWhere(['between', 'check_time', $from, $to])
            ->andWhere(['active'=>1])
            ->orderBy('check_time')
            ->all();

        $discard_ins = CheckInOut::find()
            ->where(['user_id'=>$this->user_id])
            ->andWhere(['check_type'=>'I'])
            ->andWhere(['between', 'check_time', $from, $to])
            ->andWhere(['active'=>0])
            ->orderBy('check_time')
            ->all();
        
        $discard_outs = CheckInOut::find()
            ->where(['user_id'=>$this->user_id])
            ->andWhere(['check_type'=>'O'])
            ->andWhere(['between', 'check_time', $from, $to])
            ->andWhere(['active'=>0])
            ->orderBy('check_time')
            ->all();

        $totMinutes = 0;
        $cin = count($ins);
        $cout = count($outs);

        $totMinutes = 0;

        $n = $cin<$cout ? $cin : $cout;

        for($i=0; $i<$n; $i++) {
            $start = date_create($ins[$i]->check_time);
            $end = date_create($outs[$i]->check_time);

            $diff = date_diff($start, $end);
            
            $totMinutes += ($diff->h*60) + $diff->i;
        }
        
        $noOfLogs = count($ins) + count($outs);
        $hoursWorked = floor($totMinutes/60);
        $day = date('N', strtotime($date));
        if($noOfLogs<4 && $day!=6) {
            $netHoursWorked = $hoursWorked-1;
        }else {
            $netHoursWorked = $hoursWorked;
        }

        if($day==6) {
            $eval = $netHoursWorked<4?"under":"over";
        }else {
            $eval = $netHoursWorked<8?"under":"over";
        }

        return [
            'totalMinutes' => $totMinutes,
            'noOfLogs' => $noOfLogs,
            'eval' => $eval,
            'logs'=>[
                'ins' => $ins,
                'outs' => $outs,
                'discard_ins' => $discard_ins,
                'discard_outs' => $discard_outs
            ]
        ];
    }
}
