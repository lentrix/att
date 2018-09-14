<?php
namespace app\controllers;

use Yii;
use app\models\UserInfo;
use app\models\CheckInOut;
use app\models\IndividualForm;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class ReportController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['daily', 'index', 'individual'],
                'rules' => [
                    [
                        'actions' => ['index', 'individual'],
                        'allow' => true,
                        'roles' => ['?','@']
                    ],
                    [
                        'actions' => ['daily'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    public function actionDaily()
    {
        $date = date('Y-m-d');
        $logs = null;

        if($post = Yii::$app->request->post()) {
            $date = $post['date'];
            $from = $post['date'] . " 0:0:0";
            $to = $post['date'] . " 23:59:59";
            $logs = CheckInOut::find()
                ->joinWith('userInfo')
                ->where(['between', 'check_time', $from, $to])
                ->orderBy('user_info.lname, user_info.fname, check_time')
                ->all();
        }

        return $this->render('daily', 
            [
                'date'=>$date,
                'logs'=>$logs,
            ]);
    }

    public function actionIndex()
    {
        return $this->redirect(['/site/index']);
    }

    public function actionIndividual($user_id=null, $from=null, $to=null)
    {
        $employees = UserInfo::find()->orderBy('lname, fname')->all();
        $employeeList = ArrayHelper::map($employees, 'user_id', 'fullName');
        $selectedUser = null;
        $formData = new IndividualForm();
        $logs = null;

        if($user_id && $from && $to) {
            $formData->user_id = $user_id;
            $formData->from = $from;
            $formData->to = $to;
            
            $selectedUser = UserInfo::findOne($formData->user_id);
        }

        if($formData->load(Yii::$app->request->post()) && $formData->validate()){
            $selectedUser = UserInfo::findOne($formData->user_id);
        }

        return $this->render('individual', 
            [
                'employeeList'=>$employeeList,
                'selectedUser' => $selectedUser,
                'formData' => $formData,
                'logs' => $logs,
            ]
        );
    }

    public function actionSwitch($id, $user_id, $from, $to) {
        $checkInOut = CheckInOut::findOne($id);
        $checkInOut->switchType();
        return $this->redirect(['/report/individual', 'user_id'=>$user_id, 'from'=>$from, 'to'=>$to]);
    }

    public function actionToggleActive($id, $user_id, $from, $to) {
        $checkInOut = CheckInOut::findOne($id);
        $checkInOut->active = !$checkInOut->active;
        $checkInOut->save();
        return $this->redirect(['/report/individual', 'user_id'=>$user_id, 'from'=>$from, 'to'=>$to]);
    }
}
