<?php

namespace backend\controllers;

use Yii;
use backend\models\Topics;
use backend\models\TopicsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TopicsController implements the CRUD actions for Topics model.
 */
class TopicsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $allow = false;
        if (!Yii::$app->user->isGuest) {
            $allow = true;
        }
        return [
             'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => $allow,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Topics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TopicsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Topics model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $modelTopicsmedias = new \backend\models\Topicmedias();
        $modelTopicsmediasDetails = $modelTopicsmedias->getDetail($id);       
        
        if($modelTopicsmediasDetails){
            $modelTopicsmedias = $modelTopicsmediasDetails;
        }
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'modelTopicsmedias'=>$modelTopicsmedias
        ]);
    }

    /**
     * Creates a new Topics model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionCreate()
    {             
        $model = new Topics();
        $modelTopicsmedias = new \backend\models\Topicmedias();
        if ($model->load(Yii::$app->request->post())) {
            
            if($model->save()){
                $modelTopicsmedias->uploadFile = UploadedFile::getInstance($modelTopicsmedias, 'uploadFile');
                if($modelTopicsmedias->uploadFile){
                    $topicId = $model->id;
                    $uploadDir =  Yii::$app->basePath . '/web/uploads/';
                    $topicDir = $uploadDir.'topicsmedia/'.$topicId;
                    if (!is_dir($topicDir)) {
                        mkdir($topicDir,0777);
                    }
                    $baseHttpUrl = \yii\helpers\Url::base(true);
                    $modelTopicsmedias->path = '/uploads/topicsmedia/'.$topicId.'/'.$modelTopicsmedias->uploadFile->baseName . '.' . $modelTopicsmedias->uploadFile->extension;

                    $modelTopicsmedias->topic_id = $topicId;
                    $modelTopicsmedias->name = $modelTopicsmedias->uploadFile->baseName;
                    if ($modelTopicsmedias->upload($topicDir)) {  
                        $modelTopicsmedias->uploadFile = null;
                        if($modelTopicsmedias->validate()){
                            $modelTopicsmedias->save();
                        }else{
                            Yii::$app->session->setFlash('ERROR',  json_encode($modelTopicsmedias->errors));
                        }
                    }
                }
                Yii::$app->session->setFlash('SUCCESS','Saved successfully');
            }else{ 
                 Yii::$app->session->setFlash('ERROR','Something went wrong while saving topic detail');
            }
            
            //$model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'modelTopicsmedias'=>$modelTopicsmedias
            ]);
        }
    }

    /**
     * Updates an existing Topics model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelTopicsmedias = new \backend\models\Topicmedias();
        $modelTopicsmediasDetails = $modelTopicsmedias->getDetail($id);       
        $customComponent = new \common\components\CustomComponent();
        
        if($modelTopicsmediasDetails){
            $modelTopicsmedias = $modelTopicsmediasDetails;
        }
        $postData = Yii::$app->request->post();
        if ($model->load($postData)) {
            if($model->save()){               
                $topicId = $model->id;
                $modelTopicsmedias->uploadFile = UploadedFile::getInstance($modelTopicsmedias, 'uploadFile');
                
                if($modelTopicsmedias->uploadFile && $postData['oldUploadedFile'] !=  $modelTopicsmedias->uploadFile->baseName ){                   
                    $uploadDir =  Yii::$app->basePath . '/web/uploads/';
                    $topicDir = $uploadDir.'topicsmedia/'.$topicId;
                    if (!is_dir($topicDir)) {
                        mkdir($topicDir,0777);
                    }

                    $modelTopicsmedias->path = '/uploads/topicsmedia/'.$topicId.'/'.$modelTopicsmedias->uploadFile->baseName . '.' . $modelTopicsmedias->uploadFile->extension;

                    $modelTopicsmedias->topic_id = $topicId;
                    $modelTopicsmedias->name = $modelTopicsmedias->uploadFile->baseName;
                    $uploadDir =  Yii::$app->basePath . '/web/uploads/topicsmedia/'.$topicId;    
                    if (is_dir($uploadDir)) {
                        $customComponent->emptyDir($uploadDir);                                   
                    }
                    if ($modelTopicsmedias->upload($topicDir)) {  
                        $modelTopicsmedias->uploadFile = null;
                        if($modelTopicsmedias->validate()){                       
                            $modelTopicsmedias->save();                        
                        }else{
                             Yii::$app->session->setFlash('ERROR',  json_encode($modelTopicsmedias->errors));
                        }
                    }
                }
                 Yii::$app->session->setFlash('SUCCESS','Updated successfully');
            }else{
                 Yii::$app->session->setFlash('ERROR','Something went wrong while saving topic detail');
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'modelTopicsmedias'=>$modelTopicsmedias
            ]);
        }
    }

    /**
     * Deletes an existing Topics model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $topicMediaModel = new \backend\models\Topicmedias();
        $customComponent = new \common\components\CustomComponent();
        $topicMediaModel->deleteAllBYTopicId($id);
        $uploadDir =  Yii::$app->basePath . '/web/uploads/topicsmedia/'.$id;    
        if (is_dir($uploadDir)) {
            $customComponent->emptyDir($uploadDir);       
            rmdir($uploadDir);
        }
        if($model->delete()){
            Yii::$app->session->setFlash('SUCCESS','Deleted successfully');
        }else{
            Yii::$app->session->setFlash('ERROR','Something went wrong,please try again!');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Topics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Topics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Topics::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
