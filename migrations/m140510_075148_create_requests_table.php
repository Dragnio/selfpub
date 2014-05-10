<?php

use yii\db\Schema;

class m140510_075148_create_requests_table extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(
            'Requests',
            array(
                'id'           => Schema::TYPE_PK,
                'userId'      => Schema::TYPE_INTEGER . ' NOT NULL',
                'bookName'     => Schema::TYPE_STRING . ' NOT NULL',
                'authorName'   => Schema::TYPE_STRING . ' NOT NULL',
                'synopsis'     => Schema::TYPE_STRING . ' NOT NULL',
                'participants' => Schema::TYPE_TEXT . ' NOT NULL',
                'language'     => Schema::TYPE_INTEGER . ' NOT NULL',
                'license'      => Schema::TYPE_STRING,
                'category'     => Schema::TYPE_STRING,
                'tags'        => Schema::TYPE_TEXT . ' NOT NULL',
                'cover'       => Schema::TYPE_STRING . ' NOT NULL',
                'file'       => Schema::TYPE_STRING . ' NOT NULL',
                'platforms'  => Schema::TYPE_TEXT . ' NOT NULL',
                'cost'      => Schema::TYPE_FLOAT . ' NOT NULL',
                'dateAdded'    => Schema::TYPE_INTEGER . ' NOT NULL',
                'status'       => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
            )
        );
        $this->createIndex("Requests_userId", "Requests", "userId");
        $this->createIndex("Requests_status", "Requests", "status");
        $this->createIndex("Requests_dateAdded", "Requests", "dateAdded");
        $this->addForeignKey("fk_Requests_userId", "Requests", "userId", "Users", "id", "CASCADE", "CASCADE");
    }

    public function down()
    {
        $this->dropTable("Requests");
    }
}
