<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "topics".
 *
 * @property integer $id
 * @property string $name
 * @property integer $subject_id
 *
 * @property Subjects $subject
 */
class Topics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'subject_id'], 'required'],
            [['subject_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subjects::className(), 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'subject_id' => 'Subject ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subjects::className(), ['id' => 'subject_id']);
    }
    
    public function getAllTopicsOfSubject($id){
        if($id==null){
            return;
        }
        return $this->find()->where(['subject_id'=>$id])->all();
    }
}
