<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "student_subject".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $subject_id
 */
class StudentSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'subject_id'], 'required'],
            [['student_id', 'subject_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student ID',
            'subject_id' => 'Subject ID',
        ];
    }
    
    public function getAllStudentsFromSubject($id){
        if($id == null){
            return;
        }
        return $this->find()->where(['subject_id'=>$id])->all();
    }
}
