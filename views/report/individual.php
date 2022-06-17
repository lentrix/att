<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;
use app\models\Holiday;

$this->title = "MDC Attendance System | Individual";

?>

<h1>Individual</h1>

<div class="row">

    <div class="col-md-3 alert alert-info">
    
        <p class="large-text">Fill up the following:<br /><br /></p>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($formData, 'user_id')->dropDownList($employeeList,['prompt'=>'Select an employee']); ?>

        <?= $form->field($formData, 'from')->widget(
            DatePicker::className(), [
                // inline too, not bad
                'inline' => false, 
                // modify template for custom rendering
                //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
        ]);?>


        <?= $form->field($formData, 'to')->widget(
            DatePicker::className(), [
                // inline too, not bad
                'inline' => false, 
                // modify template for custom rendering
                //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
        ]);?>
        
        <div>
            <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i> View Individual', 
                ['class'=>'btn btn-primary pull-right']); ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

    <div class="col-md-9">
    
        <?php if($selectedUser) : ?>

        <div class="pull-right">
            <?= Html::a('View Profile', 
                ['/user-info/view', 'id'=>$selectedUser->user_id], 
                ['class'=>'btn btn-success no-print']); ?>
            <button class="btn btn-info" onclick="printThisDiv('xframe', 'report')">
                <i class="glyphicon glyphicon-print"></i> Print
            </button>
            <iframe id="xframe" style="position: absolute; left:-9999px"></iframe>
        </div>

        <div class="report" id="report">

            <div class="alert alert-success">
                <p class="large-text center-print">
                    Daily Time Record<br />
                    Individual Log of <?= $selectedUser->personalName; ?>
                </p>
                <p class="center-print">
                    <?php $fromStr = date('M d, Y', strtotime($formData->from)); ?>
                    <?php $toStr = date('M d, Y', strtotime($formData->to)); ?>
                    From <?= $fromStr ?>
                    to <?= $toStr ?>
                </p>
                
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Hours Worked</th>
                        <th>Evaluation</th>
                    </tr>
                </thead>
                <tbody>
            <?php $cumulativeMins = 0; ?>
            <?php $begin = new DateTime($fromStr); ?>
            <?php $end = new DateTime($toStr); ?>
            <?php for($i=$begin; $i<=$end; $i->modify('+1 day')) : ?>
                    <?php $dayReport = $selectedUser->getDayReport($i->format('Y-m-d')); ?>
                    <tr>
                        <td>
                            <?= $i->format('M d, Y | l') ?>
                            <?php $holiday = Holiday::getByDate(date('Y-m-d', $i->getTimeStamp())); ?>
                            <?= $holiday ? '<span style="color: red"> | '.$holiday->name.'</span>' : '' ?>
                        </td>
                        <td>
                            <?php foreach($dayReport['logs']['ins'] as $in) : ?>
                            <div>
                                <?= date('g:i:s a', strtotime($in->check_time)); ?>
                                <?= Html::a("<i class='glyphicon glyphicon-remove'></i>",
                                    ['/report/toggle-active', 'id'=>$in->id, 'user_id'=>$formData->user_id,'from'=>$formData->from,'to'=>$formData->to],
                                    ['class'=>'noprint', 'style'=>'color: red']
                                ); ?>
                                <?= Html::a("<i class='glyphicon glyphicon-arrow-right'></i>",
                                    ['/report/switch', 'id'=>$in->id, 'user_id'=>$formData->user_id,'from'=>$formData->from,'to'=>$formData->to],
                                    ['class'=>'noprint']
                                ); ?>
                            </div>
                            <?php endforeach; ?>

                            <?php foreach($dayReport['logs']['discard_ins'] as $din) : ?>
                            <div>
                                <span class="discard"><?= date('g:i:s a', strtotime($din->check_time)); ?></span>
                                <?= Html::a("<i class='glyphicon glyphicon-check'></i>",
                                    ['/report/toggle-active', 'id'=>$din->id, 'user_id'=>$formData->user_id,'from'=>$formData->from,'to'=>$formData->to],
                                    ['class'=>'noprint', 'style'=>'color: #888']
                                ); ?>
                                <?= Html::a("<i class='glyphicon glyphicon-arrow-right'></i>",
                                    ['/report/switch', 'id'=>$din->id, 'user_id'=>$formData->user_id,'from'=>$formData->from,'to'=>$formData->to],
                                    ['class'=>'noprint']
                                ); ?>
                            </div>
                            <?php endforeach; ?>

                        </td>
                        <td>
                            <?php foreach($dayReport['logs']['outs'] as $out) : ?>
                            <div>
                                <?= Html::a("<i class='glyphicon glyphicon-arrow-left'></i>",
                                    ['/report/switch', 'id'=>$out->id, 'user_id'=>$formData->user_id,'from'=>$formData->from,'to'=>$formData->to],
                                    ['class'=>'noprint']
                                ); ?>
                                <?= Html::a("<i class='glyphicon glyphicon-remove'></i>",
                                    ['/report/toggle-active', 'id'=>$out->id, 'user_id'=>$formData->user_id,'from'=>$formData->from,'to'=>$formData->to],
                                    ['class'=>'noprint', 'style'=>'color: red']
                                ); ?>
                                <?= date('g:i:s a', strtotime($out->check_time)); ?>
                            </div>
                            <?php endforeach; ?>

                            <?php foreach($dayReport['logs']['discard_outs'] as $dout) : ?>
                            <div>
                                <?= Html::a("<i class='glyphicon glyphicon-arrow-left'></i>",
                                    ['/report/switch', 'id'=>$dout->id, 'user_id'=>$formData->user_id,'from'=>$formData->from,'to'=>$formData->to],
                                    ['class'=>'noprint']
                                ); ?>
                                <?= Html::a("<i class='glyphicon glyphicon-check'></i>",
                                    ['/report/toggle-active', 'id'=>$dout->id, 'user_id'=>$formData->user_id,'from'=>$formData->from,'to'=>$formData->to],
                                    ['class'=>'noprint', 'style'=>'color: #888']
                                ); ?>
                                <span class="discard"><?= date('g:i:s a', strtotime($dout->check_time)); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php $totMins = $dayReport['totalMinutes'] ?>
                            <?php $cumulativeMins += $totMins; ?>
                            <?= floor($totMins/60) . " hrs. " . $totMins%60 . "mins." ?>
                        </td>
                        <td style="text-align: center">
                            <?= $dayReport['eval']; ?>
                        </td>
                    </tr>
            <?php endfor; ?>
                    <tr>
                        <th colspan="3">
                            TOTAL HOURS
                        </th>
                        <th>
                            <?= floor($cumulativeMins/60) . " hrs. " . $cumulativeMins%60 . "mins." ?>
                        </th>
                        <th>
                            -
                        </th>
                    </tr>
                </tbody>
            </table>
            
        </div>

        <?php endif; ?>

    </div>
    
</div>

