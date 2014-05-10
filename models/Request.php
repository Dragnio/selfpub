<?php

namespace app\models;

use app\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "Requests".
 *
 * @property integer   $id
 * @property integer   $userId
 * @property string    $bookName
 * @property string    $authorName
 * @property string    $synopsis
 * @property string    $participants
 * @property integer   $language
 * @property string    $license
 * @property string    $category
 * @property string    $tags
 * @property string    $cover
 * @property string    $file
 * @property string    $platforms
 * @property double    $cost
 * @property integer   $dateAdded
 * @property integer   $status
 *
 * @property Comment[] $comments
 * @property User      $user
 */
class Request extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'userId',
                    'bookName',
                    'authorName',
                    'synopsis',
                    'participants',
                    'language',
                    'tags',
                    'cover',
                    'file',
                    'platforms',
                    'cost',
                    'dateAdded'
                ],
                'required'
            ],
            [['userId', 'language', 'dateAdded', 'status'], 'integer'],
            [['participants', 'tags', 'platforms'], 'string'],
            [['cost'], 'number'],
            [['bookName', 'authorName', 'synopsis', 'license', 'category', 'cover', 'file'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'userId'       => 'User ID',
            'bookName'     => 'Book Name',
            'authorName'   => 'Author Name',
            'synopsis'     => 'Synopsis',
            'participants' => 'Participants',
            'language'     => 'Language',
            'license'      => 'License',
            'category'     => 'Category',
            'tags'         => 'Tags',
            'cover'        => 'Cover',
            'file'         => 'File',
            'platforms'    => 'Platforms',
            'cost'         => 'Cost',
            'dateAdded'    => 'Date Added',
            'status'       => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['requestId' => 'id'])->inverseOf("request");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
