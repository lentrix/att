<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = "MDC Attendance System | Upload";

$this->params['breadcrumbs'][] = ['label'=>'Administrator', 'url'=>['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>Upload Attendance Log</h1>

<div class="row">
    <div class="col-md-6">
        <div class="alert alert-info">
            <?= Html::beginForm('','post',['enctype'=>'multipart/form-data']) ?>
            <div class="form-group">
                <label class="form-label">
                    Select the .dat file
                </label>
                <?= Html::fileInput('datFile'); ?>
            </div>
            <div>
                <?= Html::submitButton("Upload",['class'=>'btn btn-primary pull-right']); ?>
            </div>    
            <?= Html::endForm(); ?>
            <div>&nbsp;</div>
            <div>&nbsp;</div>
        </div>

        <?php if($state) : ?>

        <div class="alert <?= $state['type']=='success' ? 'alert-success' : 'alert-danger' ?>">
            <p><?= $state['message']; ?></p>
            </p><?php if($newChecks) echo "$newChecks records added." ?></p>
            <?php if(count($errors)>0) : ?>
                <p>Erroneous rows: </p>
                <ul>
                <?php foreach($errors as $errs): ?>
                    <li>
                        <ul>
                        <?php foreach($errs as $err) : ?>
                            <ul>
                            <?php foreach($err as $errMsg): ?>
                                <li><?= $errMsg ?></li>
                            <?php endforeach; ?>
                            </ul>
                        <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>


        <?php endif; ?>
    </div>
    
    <div class="col-md-5 col-md-offset-1">
        <h3>Last Check Time</h3>
        <?php if($lastCheck->userInfo !== null): ?>
        <table class="table table-bordered">
            <tr>
                <th>Check Time</th>
                <td><?= date('M d Y g:i:s a',strtotime($lastCheck->check_time)); ?></td>
            </tr>
            <tr>
                <th>Employee</th>
                <td><?= $lastCheck->userInfo->personalName ?></td>
            </tr>
            <tr>
                <th>Check Type</th>
                <td><?= $lastCheck->check_type=="I"?"In":"Out" ?></td>
            </tr>
        </table>
	<?php else: ?>
	<div class="alert alert-warning">
		The Biometric ID associated with the last user is not found.<br>
		ID# <?= $lastCheck->user_id ?> requires data entry.
	</div>
	<?php endif; ?>
    </div>
    
</div>
