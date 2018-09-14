<?php

namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class AdminController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','upload','user-info'],
                'rules' => [
                    [
                        'allow'=>true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpload()
    {
        $state = null;

        $errors = [];

        if(!$lastCheck = \app\models\CheckInOut::getLastCheck()) {
            $lastCheck = new \app\models\CheckInOut;
            $lastCheck->check_time = "1900 01 01 01:01:01";
            $lastCheck->check_type = "I";
            $lastCheck->user_id = 36;
            $lastCheck->sensor_id = 64;
        }
        
        $newChecks = 0;

        if(Yii::$app->request->post()) {
            try {
                $file = UploadedFile::getInstanceByName('datFile');
                
                if($file) {
                    $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);

                    $content = fopen('uploads/' . $file->baseName . '.' . $file->extension,'r');
                    
                    while(!feof($content)) {
                        $l = fgets($content);
                        
                        $user_id = trim(substr($l, 0,  10));
                        $check_time = trim(substr($l, 10, 19));
                        $check_type = trim(substr($l, 33, 1));
                        $sensor_id = trim(substr($l, 30, 2));

                        if(strtotime($lastCheck->check_time)<strtotime($check_time) ) {
                            //add to db
                            $c = new \app\models\CheckInOut;
                            $c->user_id = $user_id;
                            $c->check_time = $check_time;
                            $c->check_type = $check_type==0?"I":"O";
                            $c->sensor_id = $sensor_id;
                            if($c->validate()) {
                                $c->save();
                                $newChecks++;
                            }else {
                                $errors[] = $c->errors;
                                $state = ['type'=>'error', 'message'=>'Some data cannot be saved.'];
                                //die($user_id);
                            }
                            
                        } 

                    }
                    fclose($content);
                    $state = ['type'=>'success', 'message'=>'Uploaded successfully!!!'];
                }else {
                    $state = ['type'=>'error', 'message'=>'Unable to upload'];
                }
            }catch(Exception $ex) {
                $state = ['type'=>'error', 'message'=>$ex->getMessage()];
            }
        }

        return $this->render('upload',['state'=>$state,'lastCheck'=>$lastCheck,'newChecks'=>$newChecks,'errors'=>$errors]);
    }

    public function actionUserInfo()
    {
        return $this->render('user-info');
    }
}
