<?php

use yii\db\Schema;

class m140510_075158_create_comments_table extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable(
            'Comments',
            array(
                'id'        => Schema::TYPE_PK,
                'userId'   => Schema::TYPE_INTEGER . ' NOT NULL',
                'requestId' => Schema::TYPE_INTEGER . ' NOT NULL',
                'parentId'  => Schema::TYPE_INTEGER . ' NOT NULL',
                'comment'   => Schema::TYPE_TEXT . ' NOT NULL',
                'dateAdded' => Schema::TYPE_INTEGER . ' NOT NULL',
            )
        );
        $this->createIndex("Comments_userId", "Comments", "userId");
        $this->createIndex("Comments_requestId", "Comments", "requestId");
        $this->createIndex("Comments_parentId", "Comments", "parentId");
        $this->createIndex("Comments_dateAdded", "Comments", "dateAdded");
        $this->addForeignKey("fk_Comments_userId", "Comments", "userId", "Users", "id", "CASCADE", "CASCADE");
        $this->addForeignKey("fk_Comments_requestId", "Comments", "requestId", "Requests", "id", "CASCADE", "CASCADE");
    }

    public function down()
    {
        $this->dropTable("Comments");
    }
}
