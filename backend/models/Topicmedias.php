<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "topicmedias".
 *
 * @property integer $id
 * @property string $path
 * @property string $name
 * @property integer $topic_id
 *
 * @property Topics $topic
 */
class Topicmedias extends \yii\db\ActiveRecord
{
    public $uploadFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topicmedias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path'], 'required'],
            [['topic_id'], 'integer'],
            [['path','name'], 'string', 'max' => 255],
            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Topics::className(), 'targetAttribute' => ['topic_id' => 'id']],
            [['uploadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'topic_id' => 'Topic ID',
            'name'=> 'Name'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topics::className(), ['id' => 'topic_id'])->inverseOf('topicmedias');
    }
    
    public function upload($topicDir)
    {
        if ($this->validate()) {
            $this->uploadFile->saveAs($topicDir.'/'. $this->uploadFile->baseName . '.' . $this->uploadFile->extension);
            return true;
        } else {  
            return false;
        }
    }
    
    public function deleteAllBYTopicId($id){
        if($id == null){
            return;
        }
        return $this->deleteAll(['topic_id' => $id]);        
    }
    
    public function getAllMediasOfTopic($id){
        if($id == null){
            return;
        }
        return $this->find()->where(['topic_id' => $id])->all();
    }
    
    public function getDetail($id){
        return $this->find()->where(['topic_id' => $id])->one();
    }
    
}
