<?php

use yii\db\Migration;

class m200516_151221_change_key_col extends Migration
{
    public function safeUp()
    {
        $this->renameColumn('bad_login', 'key', 'username');

    }

    public function safeDown()
    {
        return false;
    }
}
