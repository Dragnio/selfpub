<?php

namespace app\models;

use amnah\yii2\user\models\User;
use app\components\ActiveRecord;
use app\helpers\TwitterHelper;
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
 * @property Comment   $lastComment
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

    public static $languages = [
        'Russian',
        'English',
        'Ukrainian'
    ];

    public static $platforms = [
        'Amazon',
        'Google Books',
        'Barnes & Noble',
        'SelfPub Inc'
    ];

    const STATUS_WAITING = 0;
    const STATUS_IN_PROCESS = 1;
    const STATUS_IN_PROCESS_ALLOW_EDIT = 2;
    const STATUS_ACCEPTED = 3;
    const STATUS_DECLINED = 4;

    public static $statuses = [
        self::STATUS_WAITING               => 'Not processed',
        self::STATUS_IN_PROCESS            => 'In the process',
        self::STATUS_IN_PROCESS_ALLOW_EDIT => 'In the process - Changes Allowed',
        self::STATUS_ACCEPTED              => 'Completed and accepted',
        self::STATUS_DECLINED              => 'Completed and declined',
    ];

    public static $statusClasses = [
        self::STATUS_WAITING               => 'default',
        self::STATUS_IN_PROCESS            => 'warning',
        self::STATUS_IN_PROCESS_ALLOW_EDIT => 'info',
        self::STATUS_ACCEPTED              => 'success',
        self::STATUS_DECLINED              => 'danger',
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
                    'dateAdded',
                    'license'
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
            [['license', 'category', 'cover', 'file'], 'string', 'max' => 255],
            [['bookName', 'authorName'], 'string', 'max' => 100],
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
            'bookName'     => 'Title of book',
            'authorName'   => 'Author\'s name',
            'synopsis'     => 'Synopsis',
            'participants' => 'Participants',
            'language'     => 'Language',
            'license'      => 'License ',
            'category'     => 'Category',
            'tags'         => 'Tags',
            'cover'        => 'Cover',
            'file'         => 'Book file',
            'platforms'    => 'Platforms',
            'cost'         => 'Price',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastComment()
    {
        return $this->hasOne(Comment::className(), ['requestId' => 'id'])->inverseOf('request')->andWhere(
            ['parentId' => 0]
        )->orderBy(['dateAdded' => SORT_DESC]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (isset($this->dirtyAttributes['status']) && $this->status == self::STATUS_ACCEPTED) {
                $status = "We have new book - \"" . $this->bookName . "\". See it here - " . $this->publicUrl;
                $tags = explode(",", $this->tags);
                foreach ($tags as $tag) {
                    $status .= " #" . trim($tag);
                }
                TwitterHelper::post($status);
            }
            return true;
        } else {
            return false;
        }
    }

    public function getPublicUrl()
    {
        return \Yii::$app->urlManager->createAbsoluteUrl(
            ['requests/view', 'requestId' => $this->id]
        );
    }
}
