<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Subjects;
use frontend\models\SubjectsSearch;
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
        return [
             'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                    ],
                ],
                 
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'delete' => ['POST'],
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
    public function actionTopics()
    {
        $topicsModel = new \frontend\models\Topics();
        $searchModel = new \frontend\models\TopicsSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('topics', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
    
    public function actionDownloadCount(){
        $postValue = Yii::$app->request->post();
        $id = $postValue['id'];
        
        $user = new \frontend\models\User();        
        $userDetail = $user->findIdentity($id);
        $userDetail->download_cnt = $userDetail->download_cnt + 1;
        return $userDetail->save();
    }
}
