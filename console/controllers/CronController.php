<?php

namespace console\controllers;
 
use yii\console\Controller;

class CronController extends Controller {
 
    public function actionUpdateType(){
        $dataExpirs = \console\models\User::getAllUserDueDateExpires();
        
        foreach ($dataExpirs as $d){
            $id =$d['id'];
            $user =  \console\models\User::findIdentity($id);            
            $user->type = "FREE";
            $user->due_date = null;
            $user->save();
        }
    }
}

