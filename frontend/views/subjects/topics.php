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
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
            [
               'label'=>'download',
               'format'=>'raw',
               'value'=>function($data){
               
                    $topicsMedia = new frontend\models\Topicmedias();
                    $topicDetail = $topicsMedia->getDetail($data->id);
                    if(Yii::$app->user->identity->type == "PAID" && Yii::$app->user->identity->download_cnt < '2'  ){
                        $mediaPath = Yii::$app->urlManagerBackEnd->baseUrl.$topicDetail->path;
                        return '<a href="javascript:void(0)" data-url="'.$mediaPath.'" onclick="javascript:clickCount('.Yii::$app->user->identity->id.',this)">'.$topicDetail->name.'</a>';
                    }else{
                        return $topicDetail->name;
                    }
               }
            ]
        ],
    ]); ?>
</div>
<script type="text/javascript">
    function clickCount(id,obj){
        $.ajax({
        type: "POST",
        url: "download-count",
        data: {id:id},
        cache: false,
        success: function(data){
            window.location = obj.getAttribute('data-url');
        }
        });
    }
</script>