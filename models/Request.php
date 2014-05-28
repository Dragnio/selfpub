<?php

namespace app\models;

use app\components\ActiveRecord;
use Yii;
use yii\helpers\FileHelper;

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

    public static $fileExtensions = [
        'pdf',
        'epub',
        'fb2',
        'doc',
        'docx'
    ];

    public static $coverExtensions = [
        'jpg',
        'jpeg',
        'bmp',
        'png',
        'gif'
    ];

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
            'bookName'     => 'Название книги',
            'authorName'   => 'Имя автора',
            'synopsis'     => 'Синопсис',
            'participants' => 'Участники',
            'language'     => 'Язык',
            'license'      => 'Лицензия',
            'category'     => 'Категория',
            'tags'         => 'Тэги',
            'cover'        => 'Обложка',
            'file'         => 'Файл книги',
            'platforms'    => 'Платформы',
            'cost'         => 'Цена',
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

    public function afterSave($insert)
    {
        parent::afterSave($insert);
        if ($insert) {
            $dirName = ROOT_DIR . '/content/books/' . $this->id;
            FileHelper::createDirectory($dirName);
        }
    }

    public function getCoverUrl()
    {
        return "/content/books/" . $this->id . "/" . $this->cover;
    }
}
