<?php

use yii\db\Migration;

class m200516_131221_change_datetime_format extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('queue', 'reset_at', $this->integer(11));
        $this->alterColumn('queue', 'created_at', $this->integer(11));
        $this->alterColumn('queue', 'updated_at', $this->integer(11));
    }

    public function safeDown()
    {
        return false;
    }
}
