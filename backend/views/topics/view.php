<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Topics */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Topics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topics-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    
    <?php 
        $mediaPath = \yii\helpers\Url::base(true).$modelTopicsmedias->path;    
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [          
            'name',
             [
                'attribute' => 'subject_id', 
                'label' => 'Subject',               
                'value'=>  $model->subject->name
                
            ],
            [
                'format'=>'raw',
                'label' => 'Media File',               
                'value'=>  "<p><a href='$mediaPath'>".$modelTopicsmedias->name."</a></p>"           
            ],
        ],
    ]) ?>

</div>
