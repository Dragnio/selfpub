<?php

namespace app\models;

use amnah\yii2\user\models\User;
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

    const STATUS_WAITING = 0;
    const STATUS_IN_PROCESS = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_DECLINED = 3;

    public static $statuses = [
        self::STATUS_WAITING    => 'Не рассмотрено',
        self::STATUS_IN_PROCESS => 'В процессе',
        self::STATUS_ACCEPTED   => 'Завершено и принято',
        self::STATUS_DECLINED   => 'Завершено и отклонено',
    ];

    public static $statusClasses = [
        self::STATUS_WAITING    => 'default',
        self::STATUS_IN_PROCESS => 'warning',
        self::STATUS_ACCEPTED   => 'success',
        self::STATUS_DECLINED   => 'danger',
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
                    'platforms',
                    'cost',
                    'dateAdded'
                ],
                'required'
            ],
            [
                [
                    'cover',
                    'file'
                ],
                'required',
                'on' => 'create'
            ],
            [['userId', 'language', 'dateAdded', 'status'], 'integer'],
            [['participants', 'tags', 'platforms'], 'string'],
            [['cost'], 'number'],
            [['bookName', 'authorName', 'license', 'category', 'cover', 'file'], 'string', 'max' => 255],
            [['synopsis'], 'string', 'min' => 500]
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
            'dateAdded'    => 'Дата добавления',
            'status'       => 'Статус',
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

    /**
     * @return string
     */
    public function getCoverUrl()
    {
        return "/content/books/" . $this->id . "/" . $this->cover;
    }

    /**
     * @return string
     */
    public function getFileUrl()
    {
        return "/content/books/" . $this->id . "/" . $this->file;
    }
}
