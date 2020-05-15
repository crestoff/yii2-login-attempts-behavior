<?php

use yii\db\Migration;

class m200516_131221_change_datetime_format extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('bad_login', 'reset_at', $this->integer(11));
        $this->alterColumn('bad_login', 'created_at', $this->integer(11));
        $this->alterColumn('bad_login', 'updated_at', $this->integer(11));
    }

    public function safeDown()
    {
        return false;
    }
}
