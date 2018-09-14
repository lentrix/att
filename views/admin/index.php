<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = "MDC Attendance System | Admin";

?>
<h1>System Administration</h1>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="large-text">Upload Attendance Log</div>
            </div>
            <div class="panel-body">
                <p>Upload attedance file from flash drive or local storaget.</p>
                <?= Html::a('Upload Attendance Log', 
                    ['/admin/upload'],['class'=>'btn btn-success btn-block']); ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="large-text">Employee Info</div>
            </div>
            <div class="panel-body">
                <p>Access / Modify Employee details.</p>
                <div>&nbsp;</div>
                <?= Html::a('Access Employee Info',
                    ['/user-info'],['class'=>'btn btn-warning btn-block']); ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="large-text">Holidays</div>
            </div>
            <div class="panel-body">
                <p>Manage Holiday Information</p>
                <div>&nbsp;</div>
                <?= Html::a('Manage Holidays',
                    ['/holidays'],['class'=>'btn btn-primary btn-block']); ?>
            </div>
        </div>
    </div>
</div>