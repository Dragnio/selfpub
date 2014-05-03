<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Users".
 *
 * @property integer $id
 * @property string $login
 * @property string $role
 * @property string $passwordHash
 * @property string $passwordSalt
 * @property string $authKey
 * @property string $name
 * @property string $email
 * @property string $avatar
 * @property integer $dateAdded
 * @property integer $status
 */
class User extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'role', 'passwordHash', 'passwordSalt', 'authKey', 'name', 'dateAdded'], 'required'],
            [['dateAdded', 'status'], 'integer'],
            [['login', 'role', 'passwordSalt', 'name', 'email', 'avatar'], 'string', 'max' => 255],
            [['passwordHash', 'authKey'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('app', 'ID'),
            'login'        => Yii::t('app', 'Login'),
            'role'         => Yii::t('app', 'Role'),
            'passwordHash' => Yii::t('app', 'Password Hash'),
            'passwordSalt' => Yii::t('app', 'Password Salt'),
            'authKey'      => Yii::t('app', 'Auth Key'),
            'name'         => Yii::t('app', 'Name'),
            'email'        => Yii::t('app', 'Email'),
            'avatar'       => Yii::t('app', 'Avatar'),
            'dateAdded'    => Yii::t('app', 'Date Added'),
            'status'       => Yii::t('app', 'Status'),
        ];
    }
}
