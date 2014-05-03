<?php

use yii\db\Schema;

class m140503_160913_create_users_table extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(
            'Users',
            array(
                'id'           => Schema::TYPE_PK,
                'login'        => Schema::TYPE_STRING . ' NOT NULL',
                'role'         => Schema::TYPE_STRING . ' NOT NULL',
                'passwordHash' => Schema::TYPE_STRING . '(32) NOT NULL',
                'passwordSalt' => Schema::TYPE_STRING . ' NOT NULL',
                'authKey'      => Schema::TYPE_STRING . '(32) NOT NULL',
                'name'         => Schema::TYPE_STRING . ' NOT NULL',
                'email'        => Schema::TYPE_STRING,
                'avatar'       => Schema::TYPE_STRING,
                'dateAdded'    => Schema::TYPE_INTEGER . ' NOT NULL',
                'status'       => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
            )
        );
        $this->createIndex("Users_login", "Users", "login");
    }

    public function down()
    {
        $this->dropTable("Users");
    }
}
