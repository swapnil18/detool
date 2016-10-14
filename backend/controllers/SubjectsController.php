<?php

namespace backend\controllers;

use Yii;
use backend\models\Subjects;
use backend\models\SubjectsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubjectsController implements the CRUD actions for Subjects model.
 */
class SubjectsController extends Controller
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
     * Lists all Subjects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subjects model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subjects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subjects();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('SUCCESS','Saved successfully');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Subjects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('SUCCESS','Updated successfully');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Subjects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $customComponent = new \common\components\CustomComponent();
        $modelSubject = $this->findModel($id);
        if($modelSubject){
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction(); 
            $topicModel = new \backend\models\Topics();
            $studentSubjectModel = new \backend\models\StudentSubject();
            $sutudentDetails = $studentSubjectModel->getAllStudentsFromSubject($id);
           
            try {            
                $topics =$topicModel->getAllTopicsOfSubject($id);
                $uploadDirArr = array();
                foreach ($topics as $top){ 
                    $topicId = $top->id;                
                    $topicMediaModel = new \backend\models\Topicmedias();       
                    $topicMediaModel->deleteAllBYTopicId($topicId);
                    $uploadDir =  Yii::$app->basePath . '/web/uploads/topicsmedia/'.$topicId;    
                    $uploadDirArr[] =$uploadDir;                    
                    $top->delete();                  
                }
                foreach ($sutudentDetails as $sts){
                    $sts->delete();
                }
                $modelSubject->delete();
                $transaction->commit();
                foreach ($uploadDirArr as $up) {
                    if (is_dir($up)) {
                        $customComponent->emptyDir($up);       
                        rmdir($up);
                    }
                }
                Yii::$app->session->setFlash('SUCCESS','Deleted successfully');
            }  catch (\Exception $e){
                $transaction->rollback();
                Yii::$app->session->setFlash('ERROR','Something went wrong, please try again'.  json_encode($e->getMessage()));                
            }
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('ERROR','Detail not found');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Subjects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subjects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subjects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
