<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TopicsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Topics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topics-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Topics', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
            [
                'attribute' => 'subject_id', 
                'label' => 'Subject',               
                'value'=>  function ($data) {                   
                    if(isset($data->subject)){
                        return $data->subject->name;                          
                    }else{
                        return "-"; 
                    }
                },
                'filter' => yii\bootstrap\Html::activeDropDownList($searchModel, 'subject_id',\yii\helpers\ArrayHelper::map(backend\models\Subjects::find()->all(),'id', 'name'),['class'=>'form-control','prompt' => 'Select Subject']),        
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
