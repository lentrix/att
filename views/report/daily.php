<?php
use yii\helpers\Html;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
$this->title = "MDC Attendance System | Daily Summary";

function reformat($logs) {
    $tbl = [];
    $recent = null;
    foreach($logs as $log) {
        $current = $log->userInfo;
        if($current!=$recent) {
            $recent = $current;
            $tbl[$current->user_id] = [
                'userInfo'=>$log->userInfo,
                'in'=>[],
                'out'=>[]
            ];
        }
        if($recent) {
            if($log->check_type=='I') {
                $tbl[$recent->user_id]['in'][]=$log->check_time;
            }else {
                $tbl[$recent->user_id]['out'][]=$log->check_time;
            }
        }
    }
    return $tbl;
}

?>
<h1>Daily Summary</h1>

<div class="row">
    <div class="col-md-3 alert alert-info">
        <p class="large-text">Select date</p>
        <br />
        <?= Html::beginForm(); ?>
        <div class="form-group">
        <label>Date</label>
        <?= DatePicker::widget([
            'name' => 'date',
            'value' => date('Y-m-d', strtotime($date)),
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
        ]);?>
        </div>
        <div>
            <?= Html::submitButton('View', ['class'=>'btn btn-primary pull-right']); ?>
        </div>
        <?= Html::endForm(); ?>
    </div>

    <div class="col-md-9">

    <?php if($logs) : ?>

    <p class="large-text">
        Daily Summary for <?= date('M d Y', strtotime($date)); ?>
    </p>
    
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Total</h>
            </tr>
        </thead>
        <tbody>
        <?php $tbl = reformat($logs); ?>
        <?php foreach($tbl as $id=>$log) : ?>
            <tr>
                <td><?= Html::a($log['userInfo']->fullName, 
                    ['/user-info/view', 'id'=>$log['userInfo']->user_id]
                ); ?></td>
                <td>
                    <?php if(!$log['in']) echo "-"; ?>

                    <?php foreach($log['in'] as $in) : ?>
                    <div><?= date('g:i:s a', strtotime($in)) ?></div>
                    <?php endforeach; ?>     
                </td>
                <td>
                    <?php if(!$log['out']) echo "-"; ?>

                    <?php foreach($log['out'] as $out) : ?>
                    <div><?= date('g:i:s a', strtotime($out)) ?></div>
                    <?php endforeach; ?>    
                </td>
                <td>
                    <?php $totMins = $log['userInfo']->getDayReport($date)['totalMinutes'] ?>
                    <?= floor($totMins/60) . " hrs. " . ($totMins%60) . " mins."; ?>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
    
    <?php endif; ?>

    </div>
</div>