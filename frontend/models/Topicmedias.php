<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "topicmedias".
 *
 * @property integer $id
 * @property string $path
 * @property string $name
 * @property integer $topic_id
 */
class Topicmedias extends \yii\db\ActiveRecord
{
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
            [['path', 'name', 'topic_id'], 'required'],
            [['topic_id'], 'integer'],
            [['path', 'name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'topic_id' => 'Topic ID',
        ];
    }
    
    public function getDetail($id){
        return $this->find()->where(['topic_id' => $id])->one();
    }
}
