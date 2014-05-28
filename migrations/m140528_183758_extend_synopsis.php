<?php

use yii\db\Schema;

class m140528_183758_extend_synopsis extends \yii\db\Migration
{
    public function up()
    {
        $this->alterColumn(\app\models\Request::tableName(), 'synopsis', Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->alterColumn(\app\models\Request::tableName(), 'synopsis', Schema::TYPE_STRING);
    }
}
