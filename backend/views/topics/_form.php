<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Topics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topics-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php 
    $subjectsList = \yii\helpers\ArrayHelper::map(backend\models\Subjects::find()->all(),'id', 'name');    
    echo $form->field($model, 'subject_id')->dropDownList($subjectsList, ['prompt'=>'Select...', 'options'=>[$model->subject_id=>["Selected"=>true]]])->label('Subject');    
    //$form->field($model, 'subject_id')->textInput() 
    if($fromaction == "update"){
        echo $form->field($modelTopicsmedias, 'uploadFile')->fileInput();
        echo Html::hiddenInput('oldUploadedFile', $modelTopicsmedias->name);
        if($modelTopicsmedias->path != ""){
            $mediaPath = \yii\helpers\Url::base(true).$modelTopicsmedias->path;            
            echo "<p><a href='$mediaPath'>".$modelTopicsmedias->name."</a></p>";
        }
    }else{
        echo $form->field($modelTopicsmedias, 'uploadFile')->fileInput(); 
    }
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
