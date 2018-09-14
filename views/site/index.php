<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'MDC Attendance System | Home';

?>

<div class="row">

    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="large-text">Individual</div>
            </div>
            <div class="panel-body">
                <p>View individual attendance logs within a specified date range.</p>
                <p>
                    <?= Html::a('View Individual Log',['/report/individual'], ['class'=>'btn btn-info btn-lg btn-block']); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="large-text">Daily Summary</div>
            </div>
            <div class="panel-body">
                <p>View a summary log for all employees on a daily basis.</p>
                <p>
                    <?= Html::a('View Daily Summary',['/report/daily'], ['class'=>'btn btn-warning btn-lg btn-block']); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="large-text">Administrator</div>
            </div>
            <div class="panel-body">
                <p>Access Administrative functions and system configurations.</p>
                <p>
                    <?= Html::a('Access Admin',['/admin'], ['class'=>'btn btn-danger btn-lg btn-block']); ?>
                </p>
            </div>
        </div>
    </div>
</div>
