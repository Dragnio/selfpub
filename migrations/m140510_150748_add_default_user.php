<?php

class m140510_150748_add_default_user extends \yii\db\Migration
{
    public function up()
    {
        $user = new \app\models\User();
        $user->login = "admin";
        $user->role = "admin";
        $user->passwordSalt = \app\helpers\PasswordHelper::generatePasswordSalt(8);
        $user->passwordHash = \app\helpers\PasswordHelper::generateCompiledPassHash("123", $user->passwordSalt);
        $user->name = "Admin";
        $user->authKey = \app\helpers\PasswordHelper::generatePasswordSalt(32);
        $user->dateAdded = time();
        if ($user->validate()) {
            $user->save(false);
            return true;
        } else {
            return false;
        }
    }

    public function down()
    {
        $users = \app\models\User::find()->all();
        foreach ($users as $user) {
            $user->delete();
        }
    }
}
