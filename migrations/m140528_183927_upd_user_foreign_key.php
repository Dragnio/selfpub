<?php

use yii\db\Schema;

class m140528_183927_upd_user_foreign_key extends \yii\db\Migration
{
    public function up()
    {
        $this->dropForeignKey("fk_Requests_userId", "Requests");
        $this->alterColumn(\app\models\Request::tableName(), 'userId', Schema::TYPE_INTEGER . " unsigned NOT NULL");
        $this->addForeignKey(
            "fk_Requests_userId",
            "Requests",
            "userId",
            \amnah\yii2\user\models\User::tableName(),
            "id",
            "CASCADE",
            "CASCADE"
        );
    }

    public function down()
    {
        $this->dropForeignKey("fk_Requests_userId", "Requests");
        $this->addForeignKey("fk_Requests_userId", "Requests", "userId", "Users", "id", "CASCADE", "CASCADE");
    }
}
