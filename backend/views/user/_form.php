<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'PAID' => 'PAID', 'FREE' => 'FREE', ], ['prompt' => '']) ?>
    
   <?php echo '<p><label>Due Date</label>';
    echo DatePicker::widget([
        'name' => 'User[due_date]', 
        'id'=>'user-due_date',        
        'value' => $model->due_date == "0000-00-00" ? "" : $model->due_date,
        'type' => DatePicker::TYPE_INPUT,
    'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-m-dd'
        ]
    ]).'</p>'; ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>