<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UserInfo */

$this->title = $model->personalName;
$this->params['breadcrumbs'][] = ['label' => 'User Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-info-view">

    <h1><?= Html::encode($this->title) ?></h1>

                    

    <div class="row">
    
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="large-text">Personal Information</div>
                </div>
                <div class="panel-body">
                    <p>
                        <?= Html::a('Update', ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->user_id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::a('Create',['create'], ['class'=>'btn btn-success']); ?>
                    </p>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'user_id',
                            'lname',
                            'fname',
                            'title',
                            'gender',
                            'birth_date',
                            'hire_day',
                            'street',
                            'city',
                            'state',
                            'zip',
                            'phone',
                            'mobile',
                            'logCount',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <?php if($recent): ?>

        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="large-text">
                        Recent Logs
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($recent as $r) : ?>
                            <tr>
                                <td><?= date('M d Y', strtotime($r->check_time)) ?></td>
                                <td><?= date('g:i:s', strtotime($r->check_time)) ?></td>
                                <td><?= $r->check_type=="I"?'In':'Out' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                
                </div>
            </div>
        </div>

        <?php endif; ?>

    </div>

    

</div>
