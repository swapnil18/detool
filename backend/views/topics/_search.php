<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TopicsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topics-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?php
        $subjectsList = \yii\helpers\ArrayHelper::map(backend\models\Subjects::find()->all(),'id', 'name');    
        echo $form->field($model, 'subject_id')->dropDownList($subjectsList, ['prompt'=>'Select...', 'options'=>[$model->subject_id=>["Selected"=>true]]])->label('Subject');    
//$form->field($model, 'subject_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
