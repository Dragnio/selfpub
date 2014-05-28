<?php

use yii\db\Schema;

class m140528_185148_drop_table_users extends \yii\db\Migration
{
    public function up()
    {
        $this->dropForeignKey("fk_Comments_userId", "Comments");
        $this->dropTable("Users");
    }

    public function down()
    {
        echo "m140528_185148_drop_table_users cannot be reverted.\n";

        return false;
    }
}
