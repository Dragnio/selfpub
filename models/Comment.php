<?php

namespace app\models;

use amnah\yii2\user\models\User;
use app\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "Comments".
 *
 * @property integer      $id
 * @property integer      $userId
 * @property integer      $requestId
 * @property integer      $parentId
 * @property string       $comment
 * @property integer      $dateAdded
 *
 * @property Request      $request
 * @property User         $user
 * @property Comment      $parent
 * @property Comment[]    $children
 */
class Comment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'requestId', 'parentId', 'comment', 'dateAdded'], 'required'],
            [['userId', 'requestId', 'parentId', 'dateAdded'], 'integer'],
            [['comment'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'userId'    => 'User ID',
            'requestId' => 'Request ID',
            'parentId'  => 'Parent ID',
            'comment'   => 'Comment',
            'dateAdded' => 'Date Added',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Request::className(), ['id' => 'requestId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Comment::className(), ['id' => 'parentId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(User::className(), ['parentId' => 'id'])->inverseOf('parent');
    }
}
